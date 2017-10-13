<?php
namespace Home\Controller;
use think\Controller;
use Service\Wechat as Wechats;
use Service\File;

class LoginController extends Controller{	
	//获取验证码
    public function getCode(){
        $phone = I('phone');
        if(!preg_match("/^1[34578]\d{9}$/",$phone)){
            jsonpReturn('0','手机号不规范');
        }
        if (isset($_SESSION['time'])) {
            if ($_SESSION['time'] <= time()) {
                $_SESSION['time'] = time() + 60;	
                $data = NewSms($phone);
                $_SESSION['code'] = $data['code'];                   
                jsonpReturn('1','验证码发送成功!',array('code'=>$data['code']));                     
            }else{
                jsonpReturn('0','一分钟之内不可重复获得验证码');
            }
        }else{
            $_SESSION['time'] = time() + 60; 
            $data = NewSms($phone);    
           	$_SESSION['code'] = $data['code'];                   
            jsonpReturn('1','验证码发送成功!',array('code'=>$data['code']));  
        }
    }
	//登录
	public function login(){
        $phone    = I('phone');
        $password = MD5(I('password'));
        $user = M('user')->where("phone='%s'",$phone)->find();
        $a = M('user')->where("phone='%s'",$phone)->find()['status'];
        if($a['status'] == '1'){
            jsonpReturn('0','此号已被冻结,请先解冻！','');
        }
        if (empty($user) || $user == false) {
            jsonpReturn('0','此号未注册,请先注册','');
        }
        if ($password != $user['password']) {
            jsonpReturn('0','密码错误!','');
        }
        $_SESSION['userid'] = $user['id'];  //用户id 写入session
        $_SESSION['user'] = $user; 
        jsonpReturn('1','登录成功!');
    }     
    //注册
    public function rigister(){
        $phone    = I('phone');
        $password = I('password');
        $rpassword= I('rpassword');
        $userid   = I('userid');
        $ppid      = I('pid');
        $code     = I('code');
        if ($code != $_SESSION['code']) {
            jsonpReturn('0','验证码错误');
        }
        if ( (int)$ppid >0 ) {   //上级
        	$Puser = M('user')->where('id='.$ppid)->find();
        	$data['pid'] = $Puser['id'];
        	$data['parent'] = $Puser['parent'].','.$Puser['id'];
        }
        if ($password != $rpassword ) {
            jsonpReturn('0','两次输入密码不一致','');
        }
        if($userid!=0){
            $xx = M('user')->where('id=%d',$userid)->find();
            $data['phone'] = $phone;
            $data['password'] = MD5($password);
            $uxx = M('user')->where('phone='.$phone)->find();      
            if($uxx){ 
                if($uxx['password'] != $data['password']){
                    jsonpReturn('0','密码错误!','');
                }else{
                   /* if($uxx['openid']){
                        jsonpReturn('2','你已绑定过微信号','lf_login.html');
                    }*/
                    $dat['openid'] = $xx['openid'];
                    $dat['sex'] = $xx['sex'];
                    $dat['remark'] = $xx['remark'];
                    $dat['nickname'] = $xx['nickname'];
                    $dat['headimg'] = $xx['headimg'];
                    M('user')->where('id=%d',$userid)->delete();
                    $res = M('user')->where('phone='.$phone)-> save($dat);
                }     
            }else{
                $data['openid'] = $xx['openid'];
                $data['sex'] = $xx['sex'];
                $data['remark'] = $xx['remark'];
                $data['nickname'] = $xx['nickname'];
                $data['headimg'] = $xx['headimg'];
                $data['createtime'] = time();
                M('user')->where('id=%d',$userid)->delete();
                $res = M('user') -> add($data);
            }
            if ($res>0) {
                jsonpReturn('1','绑定成功!','');
            }else{
                jsonpReturn('0','绑定失败!','');
            }
        }else{
            $user  = M('user');
            $users = $user->where('phone=%s',$phone)->find();
            //dump($users);exit;
            if (!empty($users)) {
                jsonpReturn('0','手机号已存在','');
            }
            $data['phone'] = $phone;
            $data['password'] = MD5($password);
            $data['nickname'] = $phone;
            $data['headimg']  = 'http://wap.jinxinenjoy.com/images/wy_headImg.png';
            $data['createtime'] = time();
            $result = $user->add($data);
            if ($result>0) {
                jsonpReturn('1','注册成功!','');
            }else{
                jsonpReturn('0','注册失败!','');
            }
        }     
    }
    /**
    * 忘记密码
    */
    public function forgetPwd(){
        $phone    = I('phone');
        $password = I('password');
        $rpassword= I('rpassword');
        $code     = I('code');
        $user  = M('user');
        $users = $user->where("phone='%s'",$phone)->find();
        if (empty($users) || $users == false) {
            jsonpReturn('3','此账号不存在');
        }
        if ($password != $rpassword ) {
            jsonpReturn('0','两次输入密码不一致');
        }
        if ($code != $_SESSION['code']) {
            jsonpReturn('2','验证码错误');
        }
        $data['password'] = MD5($password);
        $password = MD5($password);
        $tmd = $user->where("phone='%s'",$phone)->find()['password'];
        if($tmd == $password){
            jsonpReturn('1','修改成功!');
        }else{
            $result = $user->where("phone='%s'",$phone)->setField($data);
            // var_dump($result);die;
            if ($result) {
                jsonpReturn('1','修改成功!');
            }else{
                jsonpReturn('0','修改失败!');
            }
        }
                
    }
   
	# 绑定已有账号
	public function bind(){
		$this->assign('session',$_SESSION);
		$this->display();
	}
	public function binds(){
		$code = I('code');
		//var_dump($_SESSION);die();
		# 判断用户是否已经绑定过了
		if($_POST['uph']!=''){
			# 中断程序回到上一页
			$this->ajaxReturn(array("status"=>0,"msg"=>"您已经绑定过手机号了"));
		}
		if($code != $_SESSION['code']){
			$this->ajaxReturn(array("status"=>0,"msg"=>"验证码错误"));
		}
		# 表单校验
		if( !(isset($_POST['phone']) && $_POST['phone'] != '') ){
			$this->ajaxReturn(array("status"=>0,"msg"=>"账号不能为空"));
		}

		if( !(isset($_POST['password']) && $_POST['password'] != '') ){
			$this->ajaxReturn(array("status"=>0,"msg"=>"密码不能为空"));
		}

		# 验证账号密码
		if($user = M('user')->where(['phone'=>$_POST['phone']])->find()){
			$password = md5($_POST['password']);
			if($user['password'] != $password){
				$this->ajaxReturn(array("status"=>0,"msg"=>"密码不正确"));
			}
			# 检查是否已经绑定过账号了
			if($user['openid']){
				# 此账号已经绑定过其他微信了
				$this->ajaxReturn(array("status"=>0,"msg"=>"此账号已经绑定过其他微信了"));
			}else{
				# 为绑定微信号
				$users = M('user')->where(['id'=>$_POST['userid']])->find();
				$data = [];
				$data['openid'] 	= $users['openid'];
				$data['nickname'] 	= $users['niusersckname'];
				$data['headimg'] 	= $users['headimg'];	
				$data['sex']  		= $users['sex'];
				$data['remark'] 	= $users['remark'];
				$data['pid'] 	    = $users['pid'];
				$data['parent'] 	= $users['parent'];
				$res = M('user')->where(['phone'=>$_POST['phone']])->save($data);
				if($res){
					M('user')->where(['id'=>$_SESSION['userid']])->delete();
					$_SESSION['user'] = $user;
					$_SESSION['userid'] = $user['id'];
					$this->ajaxReturn(array("status"=>1,"msg"=>"绑定成功"));
				}
			}
		}
		$users = M('user')->where(['id'=>$_POST['userid']])->find();
		# 定义数据集合
		$data = [];
		$data['openid'] 	= $users['openid'];
		$data['nickname'] 	= $users['nickname'];
		$data['headimg'] 	= $users['headimg'];	
		$data['sex']  		= $users['sex'];
		$data['remark'] 	= $users['remark'];
		if($data){
			$data['phone'] 		= $_POST['phone'];
			$data['password'] 	= md5($_POST['password']);
			$data['createtime'] = time();
			# 删除当前的用户信息
			M('user')->where(['id'=>$_POST['userid']])->delete();
			$res = M('user')->add($data);
			//var_dump($res);die('7');
		}
		# 绑定
		if($res){	
			# 更新用户信息
			if($user1 = M('user') -> where(['phone'=>$_POST['phone']]) -> find()){
				$_SESSION['user'] = $user1;
				$_SESSION['userid'] = $user1['id'];
			}
			# 绑定成功
			$this->ajaxReturn(array("status"=>1,"msg"=>"绑定成功"));
		}else{
			# 绑定失败
			$this->ajaxReturn(array("status"=>0,"msg"=>"绑定失败"));
		}
	}
     //退出账号
    public function logOut(){
        unset($_SESSION);//清空指定的session
        jsonpReturn('1','退出成功!','');
    }
}