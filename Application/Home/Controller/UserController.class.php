<?php
namespace Home\Controller;
use think\Controller;
use Service\Wechat as Wechats;
use Service\File;

class UserController extends AbaseController{
    //首页
   
    public function index(){
        //自动返奖学金
         getmoney();
        //判断自动出局
         outorder();
        //$type = $_SESSION['type'];
        $id    = $_SESSION['userid'];
        $user  = M('user');
        $account  = M('account');
        $with = M('withdrawals');
        $userinfo = $user->field("id,headimg,is_cost")->where(['id'=>$id])->find();
        $money = $account->where("uid=%d and status = '1' and message = '3'",$id)->sum('money');   //我的余额充值
        $jiang = $account->where("uid=%d and message = '4'",$id)->sum('money');   //我的奖学金
        $zhu = $account->where("uid=%d and message = '5'",$id)->sum('money');   //我的助学金
        $ti = $account->where("uid=%d and message = '1' and status='3'",$id)->sum('money');   //我的提现金额
        $yu = $account->where("uid=%d and paymenttype = '1' and status='1'",$id)->sum('money');   //我的余额扣除
        $restmoney = $jiang+$zhu+$money+$ti+$yu;
        // var_dump($restmoney);
        if($restmoney == null){
            $restmoney = '0.00';
        }
        if($jiang == null){
            $jiang = '0.00';
        }
        if($zhu == null){
            $zhu = '0.00';
        }
        $data = array("userinfo"=>$userinfo,"restmoney"=>$restmoney,"jiang"=>$jiang,"zhu"=>$zhu);
        jsonpReturn("1","查询成功",$data);
    }
    //个人设置 编辑资料
    public function userSetting(){
        $id   = $_SESSION['userid'];
        $user = M('user');
        $users= $user -> field("headimg,rname,nickname,sex,age")->where("id=%d",$id)->find();
        jsonpReturn('1',"查询成功",$users);
    } 
     //修改资料--提交
    public function updateUserInfo(){
        $id  = $_SESSION['userid']; 
        $user= M('user');
        // $yes = $user->where("rname='%s'",$rname)->getField("id");
        // if ($id != $yes) {
        //     jsonpReturn("0","此用户已存在");
        // }
        $data['rname']   = I('rname');
        $data['nickname']    = I('nickname');
        $data['sex']     = I('sex');
        $data['age']     = I('age');
        $data['headimg'] = I('headimg');
        $result = $user->where("id=%d",$id)->save($data);
        if ($result) {
            jsonpReturn('1','修改成功','');
        }else{
            jsonpReturn('0','修改失败','');
        }
    }
    //我的账户-账户余额
    public function balance(){
        $id   = $_SESSION['userid'];
        // $user = M('user')->where('id='.$id)->find();
        $account = M('account');
        $money = $account->where("uid=%d and status = '1' and message = '3'",$id)->sum('money');   //我的余额充值
        $jiang = $account->where("uid=%d and message = '4'",$id)->sum('money');   //我的奖学金
        $zhu = $account->where("uid=%d and message = '5'",$id)->sum('money');   //我的助学金
        $ti = $account->where("uid=%d and message = '1' and status ='3'",$id)->sum('money');   //我的提现金额
        $yu = $account->where("uid=%d and paymenttype = '1' and status='1'",$id)->sum('money');   //我的余额扣除
        $restmoney = $jiang+$zhu+$money+$ti+$yu;
        if($restmoney == null){
            $restmoney = '0.00';
        }
        $users= M('user')-> field("headimg")->where("id=%d",$id)->find();
        $result = M('account')->field("money,message,createtime,status")->where("uid=%d ",$id)->order('id desc')->select(); //记录
        // $result1 = M('account')->field("money,message,createtime")->where("uid=%d and message = '3' and status = '1'",$id)->order('id desc')->select(); //记录
        
        foreach ($result as $key => $value) {
            // var_dump($value);die;
            $result[$key]['createtime'] = date("Y-m-d H:i:s",$value['createtime']);        
            if($value['message'] == '3' && $value['status'] == '0') {
                unset($result[$key]);
            }
        }
        $data = array('result'=>$result,'allmoney'=>$restmoney,'userinfo'=>$users,'tixian'=>$result1);
        jsonpReturn('1','查询成功！',$data);  
    }
    //我的账户-账户余额-余额提现
    public function withdrawals(){
        $id  = $_SESSION['userid']; 
        $user= M('user');
        $config = M('config');
        $account = M('account');
        $money = $account->where("uid=%d and status = '1' and message = '3'",$id)->sum('money');   //我的余额充值
        $jiang = $account->where("uid=%d and message = '4'",$id)->sum('money');   //我的奖学金
        $zhu = $account->where("uid=%d and message = '5'",$id)->sum('money');   //我的助学金
        $ti = $account->where("uid=%d and message = '1' and status ='3'",$id)->sum('money');   //我的提现金额
        $yu = $account->where("uid=%d and paymenttype = '1' and status='1'",$id)->sum('money');   //我的余额扣除
        $restmoney = $jiang+$zhu+$money+$ti+$yu;
        if($restmoney == null){
            $restmoney = '0.00';
        }
        $conf = $config->field('cash_commission,lower_cash')->find();
        $users= $user-> field("headimg")->where("id=%d",$id)->find();
        $data = array('allmoney'=>$restmoney,'userinfo'=>$users,'config'=>$conf);
        jsonpReturn('1','查询成功！',$data);  
        
    }
    //我的账户-账户余额-余额提现-提交资料
    public function withdrawalsinfor(){
        $id  = $_SESSION['userid']; 
        $user= M('user');
        $conf =  M('config')->find()['cash_commission']; //提现收取手续费
        $data['payment']    = I('payment');
        $dollar     = I('money');
        $a = $dollar* $conf*0.01;
        $data['fee'] = $a;
        $d     = I('money');
        $data['money']     = -$d;
        $c = $d - $a;
        $data['realmoney'] = $c;
        $data['bank']     = I('bank'); //开户行
        $data['card']     = I('card');//银行账户
        $data['name']     = I('name');//开户人
        $data['createtime']  = time();
        $data['uid']  =$id;
        $data['photo'] = I('weiimg');
        
        // if( empty($data['money']) && empty($data['photo'] && empty($data['payment']) || empty($data['bank']) || empty($data['card']) || empty($data['name']))){
        //     jsonpReturn('2','请确认提交的信息完整','');
        // }
        // var_dump($data);die;
        $res['money'] = $data['money'];
        $res['createtime'] = time();
        $res['message'] = 1;
        $res['uid'] =$id;
        $res['status'] = 0;
        $res['paymenttype'] = $data['payment'];
        $account = M('account')->add($res);
        $result = M('withdrawals')->add($data);
        if ($result && $account) {
            jsonpReturn('1','提交成功！','');
        }else{
            jsonpReturn('0','提交失败！','');
        }
    }
    //我的账户-奖学金
    public function jiang(){
        $id    = $_SESSION['userid'];
        $account  = M('account');
        $jiang = $account->where("uid=%d and message = '4'",$id)->sum('money');   //我的奖学金
        if($jiang == null){
            $jiang = '0.00';
        }
        $result = $account->where("uid=%d and message = '4'",$id)->field("id,money,createtime")->order('id desc')->select();   //我的奖学金记录
        // $num = M('order')->where("uid=%d",$id)->field("num")->find();   
        $users= M('user')-> field("headimg")->where("id=%d",$id)->find();  //我的头像
        foreach ($result as $key => $value) {
            $result[$key]['createtime'] = date("Y-m-d H:i:s",$value['createtime']);        
        } 
        // $result['num'] = $num;
        $data = array('money'=>$jiang,'result'=>$result,'userinfo'=>$users);
        jsonpReturn("1","查询成功",$data);
    }
    
    //我的账户-助学金
    public function grant(){
        $id = $_SESSION['userid'];

        // $id = 80;
        $img = M('user')->where('id=%d',$id)->getField('headimg');
        
        #下1级
        $one    = getChilden($id,1);
        $user1  = explode(',', $one);
        #下2级
        $two    = getChilden($id,2);
        $user2  = explode(',', $two);
        #下3级
        $three  = getChilden($id,3);
        $user3  = explode(',', $three);
        #下4级
        $four   = getChilden($id,4);
        $user4  = explode(',', $four);
        #下5级
        $five   = getChilden($id,5);
        $user5  = explode(',', $five);
        $data = M('account')->where('uid=%d and message="5"',$id)->order('id desc')->select();
        //dump($data);exit;
        foreach ($data as $k => $v) {
            $order = M('order')->where('order_num='.$v['source'])->find();
            if($order){
                if(in_array($order['uid'],$user1)){
                    $oo = 1;
                }else if(in_array($order['uid'],$user2)){
                    $oo = 2;
                }else if(in_array($order['uid'],$user3)){
                    $oo = 3;             
                }else if(in_array($order['uid'],$user4)){
                     $oo = 4;          
                }else if(in_array($order['uid'],$user5)){
                     $oo = 5;       
                }
          
                switch ($oo) {
                    case 1:
                        $xx = M('user')->field('id,headimg,phone,nickname')->where('id=%d',$order['uid'])->find();
                        $dd['num'] = $order['num'];
                        $dd['money'] = $v['money'];
                        $dd['createtime'] = date('Y-m-d',$order['createtime']);        
                        $order1[] = array_merge($xx,$dd);
                        break;
                    case 2:
                        $xx = M('user')->field('id,headimg,phone,nickname')->where('id=%d',$order['uid'])->find();

                        $dd['num'] = $order['num'];
                        $dd['money'] = $v['money'];
                        $dd['createtime'] = date('Y-m-d',$order['createtime']);
                        
                        $order2[] = array_merge($xx,$dd);

                        break;
                    case 3:
                        $xx = M('user')->field('id,headimg,phone,nickname')->where('id=%d',$order['uid'])->find();
                        $dd['num'] = $order['num'];
                        $dd['money'] = $v['money'];
                        $dd['createtime'] = date('Y-m-d',$order['createtime']);
                        $order3[] = array_merge($xx,$dd);
                        break;
                    case 4:
                        $xx = M('user')->field('id,headimg,phone,nickname')->where('id=%d',$order['uid'])->find();
                        $dd['num'] = $order['num'];
                        $dd['money'] = $v['money'];
                        $dd['createtime'] = date('Y-m-d',$order['createtime']);
                        $order3[] = array_merge($xx,$dd); 
                        break;
                    case 5:
                        $xx = M('user')->field('id,headimg,phone,nickname')->where('id=%d',$order['uid'])->find();
                        $dd['num'] = $order['num'];
                        $dd['money'] = $v['money'];
                        $dd['createtime'] = date('Y-m-d',$order['createtime']);
                        $order3[] = array_merge($xx,$dd);
                        
                        break;              
                }
            }    
        }
        //exit;
        $sum = M('account')->where("uid=%d and message = '5'",$id)->sum('money');
        if($sum == null){
            $sum = "0.00";
        }
        jsonpReturn('1','查询成功',['img'=>$img,'num'=>$sum,'xiaofei'=>['one'=>$order1,'two'=>$order2,'three'=>$order3]]);
    }
    #我的小伙伴
    public function partner(){
        $id = $_SESSION['userid'];
        //$id = 43;
         $img = M('user')->where('id=%d',$id)->getField('headimg');
        #扫码会员
         list($data1,$data2,$data3,$data4,$data5) =array([],[],[],[],[],[]);
         #消费会员
         list($data11,$data22,$data33,$data44,$data55) =array([],[],[],[],[],[]);
        $one    = getChilden($id,1);
        $user1  = explode(',', $one);
        foreach ($user1 as $k => $v) {
            $where['id'] = ['eq',$v];
            $where['phone'] = ['neq',''];
            $data = M('user')->field('id,headimg,phone,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i") as createtime,nickname,is_cost')->where($where)->find();
            if($data){
                if($data['is_cost'] == 1){
                $data11[]   = $data;
                }else if($data['is_cost'] == 0){
                   if($data){
                      $data1[] = $data;
                   }
                }
            }     
        }
        $two    = getChilden($id,2);
        $user2  = explode(',', $two);
        foreach ($user2 as $k => $v) {
            $where['id'] = ['eq',$v];
            $where['phone'] = ['neq',''];
            $data = M('user')->field('id,headimg,phone,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i") as createtime,nickname,is_cost')->where($where)->find();
            if($data){
                if($data['is_cost'] == 1){
                $data22[]   = $data;
                }else if($data['is_cost'] == 0){
                    if($data){
                      $data2[] = $data;
                   }
                }
            }
            
        }
        $three  = getChilden($id,3);
        $user3  = explode(',', $three);
        foreach ($user3 as $k => $v) {
             $where['id'] = ['eq',$v];
            $where['phone'] = ['neq',''];
            $data = M('user')->field('id,headimg,phone,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i") as createtime,nickname,is_cost')->where($where)->find();
            if($data){
                if($data['is_cost'] == 1){
                $data33[]   = $data;
                }else if($data['is_cost'] == 0){
                    if($data){
                      $data3[] = $data;
                   }
                }
           }
            
        }
        $four   = getChilden($id,4);
        $user4  = explode(',', $four);
        foreach ($user4 as $k => $v) {
            $where['id'] = ['eq',$v];
            $where['phone'] = ['neq',''];
            $data = M('user')->field('id,headimg,phone,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i") as createtime,nickname,is_cost')->where($where)->find();
            if($data){
                if($data['is_cost'] == 1){
                    $data33[]   = $data;
                }else if($data['is_cost'] == 0){
                    if($data){
                      $data3[] = $data;
                   }
                }
            }
        }
        $five   = getChilden($id,5);
        $user5  = explode(',', $five);
        foreach ($user5 as $k => $v) {
            $where['id'] = ['eq',$v];
            $where['phone'] = ['neq',''];
            $data = M('user')->field('id,headimg,phone,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i") as createtime,nickname,is_cost')->where($where)->find();
            if($data){
                if($data['is_cost'] == 1){
                $data33[]   = $data;
                }else if($data['is_cost'] == 0){
                    if($data){
                      $data3[] = $data;
                   }
                }
            }      
        }
         //$sum = count($user1)+count($user2)+count($user3)+count($user4)+count($user5);
         $saoma = count($data1)+count($data2)+count($data3);
         $xiaofei = count($data11)+count($data22)+count($data33);
         $sum = $saoma+$xiaofei;
        jsonpReturn('1','查询成功',['img'=>$img,'num'=>[$sum,$saoma,$xiaofei],'saoma'=>['one'=>$data1,'two'=>$data2,'three'=>$data3],'xiaofei'=>['one'=>$data11,'two'=>$data22,'three'=>$data33]]);
    }
	//我的提现
    public function withdrawalsLog(){
        $id = $_SESSION['userid'];
        $restmoney = M('withdrawals')->where("uid=%d and status ='3'",$id)->sum('money');   //我的提现金额
        if($restmoney == null){
            $restmoney = '0.00';
        }
        $test = -$restmoney;
        $users= M('user')-> field("headimg")->where("id=%d",$id)->find();  //我的头像
        $result = M('withdrawals')->where("uid=%d and status ='3'",$id)->field("money,createtime")->order('id desc')->select();
        foreach ($result as $key => $value) {
            $result[$key]['createtime'] = date("Y-m-d H:i:s",$value['createtime']);        
        }
        $data = array('result'=>$result,'money'=>$test,'userinfo'=>$users);
        jsonpReturn('1','查询成功！',$data);        
    }
	//我的订单
    public function order(){
        $id  = $_SESSION['userid']; 
        // $id=42;
        $order= M('order');
        $user = M('user');
        $users= $user-> field("headimg")->where("id=%d",$id)->find();  //我的头像
        $unit= $order->where("uid=%d and status ='3'",$id)->getField("count(1)");  //我的订单
        $info = $order->where('uid=%d and status ="3" ',$id)->field("money,createtime,num")->order('id desc')->select(); //我的订单记录
        foreach ($info as $key => $value) {
            $info[$key]['createtime'] = date("Y-m-d H:i:s",$value['createtime']);        
        } 
        $data = array('info'=>$info,'userinfo'=>$users,'unit'=>$unit);
        jsonpReturn('1',"查询成功",$data);
    }
    //新增收货地址
    public function addaddress(){
        $id  = $_SESSION['userid']; 
        $address= M('address');
        $mobile = I('mobile');      
        $yes = $address->where("mobile='%s'",$mobile)->getField("id");
        if ($id == $yes) {
            jsonpReturn("0","此地址已存在！");
        }
        // $data['rname']   = I('rname');
        $data['name']    = I('name');
        $data['province']     = I('province');
        $data['county']    =I('county');
        $data['city']    =I('city');
        $data['address'] = I('address');
        $data['mobile']  =$mobile;
        $data['uid']   =$id;
        $data['status']   = 1;
        $data['createtime']  = time();
        $result = $address->add($data);
        if ($result) {
            jsonpReturn('1','提交成功','');
        }else{
            jsonpReturn('0','提交失败','');
        }
    }
    //查询管理收货地址
    public function guanaddress(){
        $id  = $_SESSION['userid']; 
        // $id= 42;
        $address= M('address');
        $data = $address->where('uid ='.$id)->field("id,mobile,name,address,province,city,county,status")->select();
        $info['info'] = $data;
        jsonpReturn('1',"查询成功",$info);
    }
    //编辑收货地址
    public function editaddress(){
        $id = I('id');
        $address= M('address');
        $data = $address->where('id='.$id)->field("id,mobile,name,address,province,city,county,status")->find();
        // var_dump($data);die;
        $info['info'] = $data;
        jsonpReturn('1',"查询成功",$info); 
    }
    //设置默认地址
    public function defa(){
        $userid  = $_SESSION['userid'];
        // $userid = 42;
        // $id = 7;
        $id = I('id');
        $address = M('address');
        if(empty($id)){
            jsonpReturn('2','参数不存在','');
        }else{
            $da['status'] = 0;
        }
        $adre= $address->where("uid=%d",$userid)->field('status')->select();
        if($adre){
            $feidata['status'] =1;
            $fei= $address->where("uid=%d",$userid)->save($feidata);
        }
        $data= $address->where("id=%d",$id)->save($da);
        if($data){
            jsonpReturn('1','设置默认地址成功！','');
        }else{
            jsonpReturn('0','设置默认地址失败！','');
        }
    }
    //删除收货地址
    public function delete(){
        $id = I('id');
        $address = M('address');
        $data= $address->where("id=%d",$id)->delete();  
        if ($data) {
            jsonpReturn('1','删除成功','');
        }else{
            jsonpReturn('0','删除失败','');
        }
    }
    //修改收货地址
    public function edit(){
        $userid  = $_SESSION['userid']; 
        $address= M('address');
        $user = M('user');
        $data['name']    = I('name');
        $data['area']     = I('area');
        $data['address'] = I('address');
        $data['mobile']  = I('mobile');
        $data['uid']   =$userid;
        $data['status']   = 0;
        $data['createtime']  = time();
        // $result = $user->where("id=%d",$userid)->save($data);
        $result = $address->where("uid=%d",$userid)->save($data);
        if ($result) {
            jsonpReturn('1','修改成功','');
        }else{
            jsonpReturn('0','修改失败','');
        }
    }
   
	/**
    * 判断是否登录
    */
    // public function isLogin(){
    //     if (isset($_SESSION['userid']) || $_SESSION['userid'] != '') {
    //         jsonpReturn('1','登录过了');
    //     }else{
    //         jsonpReturn('11','未登录');
    //     }
    // }
   
}