<?php
namespace Home\Controller;
use think\Controller;
use Service\Wechat;
use Service\File;
class WechatController extends Controller{
	//生成菜单
	public function menu(){
        $data = [
			['name'=>'商城首页','event'=>'view','val'=>'http://www.jinxinenjoy.com/Home/Wechat/wechat_login.html?type=1'],
				
			['name'=>'APP 下载','event'=>'view','val'=>'http://www.jinxinenjoy.com/Home/Wechat/wechat_login.html?type=2'],
				
			['name'=>'新手指南','event'=>'view','val'=>'http://www.jinxinenjoy.com/Home/Wechat/wechat_login.html?type=3']
	
		];
		dump(Wechat::menu_create($data));
  	}

//微信验证
	public function token(){
		Wechat::checkToken();
	}
	//扫码注册
	# 扫码后的第一个转折点
	public function code(){
        # 获取上级id
        $pid = I('pid');
		# 判断是否在微信中打开
        if(is_weixin()){
        	 header('location:http://www.jinxinenjoy.com/Home/Wechat/wechat_login.html?pid='.$pid);exit;         
        }else{
            # 带着上级信息直接去注册页面
             header('location:http://wap.jinxinenjoy.com/lf_register.html?pid='.$pid.'&id=0');exit;
        }
	}
	 //微信登录
    public function wechat_login(){
    	$pid = I('pid');
		// 获取用户信息
		$userinfo = Wechat::get_user_info('http://www.jinxinenjoy.com/Home/Wechat/wechat_login.html?pid='.$pid);
		// 判断用户是否已经注册过了
		if($user = M('user') -> where(['openid'=>$userinfo['openid']]) -> find()){
			if($user['phone'] != ''){
				header('location:http://wap.jinxinenjoy.com/lf_login.html');exit;
			}else{
				M('user') -> where(['openid'=>$userinfo['openid']]) -> save(['pid'=>$pid]);		
				$_SESSION['userid'] = $user['id'];// 存储用户信息
				$_SESSION['user'] = $user;
				header('location:http://wap.jinxinenjoy.com/lf_register.html?pid='.$pid.'&id='.$user['id']);exit;
			}
		}else{

			# 获取用户信息
			$data = [];
			if($pid > 0){
				
				$data['pid'] = $pid;
				$xx = M('user')->where('id='.$pid)->find();
				$data['parent'] = $xx['parent'].','.$pid;	
			}else{
			
				// 跳转到首页*/
				header('location:http://wap.jinxinenjoy.com/lf_register.html?pid=0&id=0');exit;
			}
			//dump($data);exit;
			# 用户唯一标识
			$data['openid'] = $userinfo['openid'];
			# 性别 1=男 2=女性 0=未设置
			$data['sex'] = $userinfo['sex'];
			$data['remark'] = $userinfo['country'].'-'.$userinfo['province'].'-'.$userinfo['city'];# 国籍# 省份# 城市
			# 用户昵称
			$data['nickname'] = $userinfo['nickname'];
			# 下载头像到本地
			File::_download($userinfo['headimgurl'],ROOT_PATH.'/public/headimg/',$userinfo['openid'].'.jpg');
			# 头像
			$data['headimg'] = 'http://www.jinxinenjoy.com/public/headimg/'.$userinfo['openid'].'.jpg';
			# 创建时间
			$data['createtime'] = time();
			# 插入用户数据
			$userId = M('user') -> add($data);
			if($user = M('user')->where(['id'=>$userId])->find()){
				$_SESSION['user'] = $user;
				$_SESSION['userid'] = $userId;
				// 跳转到首页*/
				header('location:http://wap.jinxinenjoy.com/lf_register.html?pid='.$pid.'&id='.$userId);exit;
			}
		}
	}
}