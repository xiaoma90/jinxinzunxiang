<?php
namespace Home\Controller;
use think\Controller;
use Service\Wechat as Wechats;
use Service\File;

class IndexController extends AbaseController{
    public function index1(){
         if($_SESSION['userid']<1){
             jsonpReturn('400',"请重新登录",'');
        } 
    }
	public function goodx(){
        if($_SESSION['userid']<1){
            jsonpReturn('400',"请先登录",'');
        }else{
            jsonpReturn('1',"",'');
        }
    }
	//首页
    public function index(){
        //------------轮播图------------//
        $lunbo = M('lunbo');
        $lunbotu = $lunbo->where('isdel=0')->order('sort asc')->select();
        // var_dump($lunbotu);
        // $but_lunbotu = $lunbo->where('isdel=0 and sort=2')->order('id desc')->limit(10)->select();                                      666
        $inde['lunbotu'] = $lunbotu;    //顶部轮播图
        if(is_weixin()){
            $inde['types'] = 1;
        }
        jsonpReturn('1','查询成功',$inde);
    }

    //公司简介
    public function company(){
        $classid = I('get.classid');    //需要参数：
        // $classid = 3;
        if(empty($classid)){
            jsonpReturn('2','参数不存在','');
        }
        $company = M('info');
        //文章信息
        $info = $company->where('isdel=0 and classid='.$classid)->order('id desc')->select();
        $return['info'] = $info;
        // dump($return);
        jsonpReturn('1','查询成功',$return);
    }

    //通知公告
    public function notice(){
        $classid = I('get.classid');    //需要参数：文章类别id --classid
        // $classid = 2;
        if(empty($classid)){
            jsonpReturn('2','参数不存在','');
        }
        $notice = M('info');
        //文章信息
        $info = $notice->field('*,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i:%s:") as createtime')->where('isdel=0 and classid='.$classid)->order('id desc')->select();
        $return['info'] = $info;
        // dump($return);
        jsonpReturn('1','查询成功',$return);
    }
    //新手指南
    public function novice(){
        $classid = I('get.classid');    //需要参数：文章类别id --classid
        // $classid = 1;
        if(empty($classid)){
            jsonpReturn('2','参数不存在','');
        }
        $novice = M('info');
        //文章信息
        $info = $novice->where('isdel=0 and classid='.$classid)->order('id desc')->select();

        foreach($info as $k=>$v){
            $info[$k]['createtime'] = date('Y-m-d',$v['createtime']);
        }
        $return['info'] = $info;
        // dump($return);
        jsonpReturn('1','查询成功',$return);
    }

    //商品详情
    public function goods(){
        $id = I('get.id');          //需要参数：文章id --id
        $id = 1;
        if(empty($id)){
            jsonpReturn('2','参数不存在','');
        }
        $good = M('goods');
        //文章信息
        $info = $good->where('id='.$id)->field("price,title,content,photo")->find();
        // $info['createtime'] = date('Y-m-d',$info['createtime']);
        $return['info'] = $info;
        // var_dump($return);
        jsonpReturn('1','查询成功',$return);
    }

    //购买
    public function buy(){
        // $user = user_info($_SESSION['userid']);
        // if(!$user ){
        //     jsonpReturn('5','您还没有登录','http://hr2.hongrunet.com/html/jyt_login.html');
        // }
        $danhao = orderNum();
        $order =  M('order');
        $data['uid']    = $_SESSION['userid'];
        // $data['num']    = I('num');
        $data['num']    = 10;
        // $data['money']  = I('money');
        $data['money']  = 500;
        $data['createtime'] = time();
        $data['order_num'] = $danhao;
        $data['status'] = 1;
        // var_dump($data);exit;
        if(empty($data['num'] && $data['money'])){
            jsonpReturn('0','请确认提交的信息完整','');
        }else{
            $result = $order->add($data);
            if ($result > 0) {
                jsonpReturn('1','提交成功','');
            }else{
                jsonpReturn('0','提交失败','');
            }
        }
    }

    //结算
    public function settle(){
        $id = $_SESSION['userid'];
        $goods = M('goods')->where('id=1')->find();
        $address =  M('address');
        $addre = $address->where('uid=%d and status=0',$id)->find();
        $return['info'] = $goods;
        $return['addre'] = $addre;
        jsonpReturn('1','查询成功',$return);
    }

    //提交订单
    public function tjOrder(){     
        if(I('num')>limitFor()){
            jsonpReturn('3','',limitFor());
        }
        $data['uid']        = $_SESSION['userid'];
        $data['num']        = I('num');
        $data['money']      = I('money');
        $data['getname']    = I('getname');
        $data['getphone']   = I('getphone');
        $data['status']     = 1;
        $data['type']       = 1;
        $data['createtime'] = time();
        $data['order_num']  = orderNum();
        $res = M('order')->add($data);
        if($res>0){
            jsonpReturn('1','下单成功',$res);
        }else{
            jsonpReturn('0','下单失败','');  
        }
    }

    //订单支付查询
    public function payment(){
        $userid = $_SESSION['userid'];
        $account = M('account');
        // $money= M('account')->where('uid=%d',$userid)->getField('sum(money)');  //我的余额
        $money = $account->where("uid=%d and status = '1' and message = '3'",$userid)->sum('money');   //我的余额充值
        $jiang = $account->where("uid=%d and message = '4'",$userid)->sum('money');   //我的奖学金
        $zhu = $account->where("uid=%d and message = '5'",$userid)->sum('money');   //我的助学金
        $ti = $account->where("uid=%d and message = '1' and status='3'",$userid)->sum('money');   //我的提现金额
        $yu = $account->where("uid=%d and paymenttype = '1' and status='1'",$userid)->sum('money');   //我的余额扣除
        $restmoney = $jiang+$zhu+$money+$ti+$yu;
        if($restmoney == null){
            $restmoney = '0.00';
        }
        $id = I('id');
        if(empty($id)){
            jsonpReturn('2','参数不存在','');
        }
        $order =  M('order');
        $user  = M('user');
        $info = $order->where('id='.$id)->select();
        $return['info'] = $info;
        $return['money'] = $restmoney;
        jsonpReturn('1','查询成功',$return);
    }

    //线下支付
    public function ment(){
        $userid = $_SESSION['userid'];
        $order = M('order');
        $user = M('user');
        $account = M('account');
        $id = I('id');
        //获取支付方式
        $data['payment'] = I('payment');            //支付方式
        if($data['payment'] == '微信'){
            $data['payment'] = 2;
        }elseif($data['payment'] == '支付宝'){
            $data['payment'] = 3;
        }elseif($data['payment'] == '余额充值'){
            $data['money'] = I('money');
            $data['message'] = 3;
            $data['status'] = 0;
            $data['createtime'] = time();
            $data['uid'] = $userid;
            $result = $account->add($data);
            if ($result > 0) {
                jsonpReturn('1','余额支付成功','');
            }else{
                jsonpReturn('0','余额支付失败','');
            }
        }else{
            jsonpReturn('3','请输入正确支付方式！','');
        }
        $data['transaction_number'] = I('number');  //交易号
        $data['money'] = I('money');                //付款金额
        $data['status'] = 2;
        if(empty($data['payment']) && empty($data['money']) && empty($data['transaction_number'])){
            jsonpReturn('0','请确认提交的信息完整!','');
        }else{
            $result = $order->where('id='.$id)->save($data);
            if ($result > 0) {
                jsonpReturn('1','支付成功','');
            }else{
                jsonpReturn('0','支付失败','');
            }
        }
    }
    //余额支付
    public function restmoney(){
        $userid  = $_SESSION['userid']; 
        $id         = I('id');
        $account = M('account');
        // $money      = M('account')->where('uid=%d',$userid)->getField('sum(money)');  //我的余额
        $money = $account->where("uid=%d and status = '1' and message = '3'",$userid)->sum('money');   //我的余额充值
        $jiang = $account->where("uid=%d and message = '4'",$userid)->sum('money');   //我的奖学金
        $zhu = $account->where("uid=%d and message = '5'",$userid)->sum('money');   //我的助学金
        $ti = $account->where("uid=%d and message = '1' and status='3'",$userid)->sum('money');   //我的提现金额
        $yu = $account->where("uid=%d and paymenttype = '1' and status='1'",$userid)->sum('money');   //我的余额扣除
        $restmoney = $jiang+$zhu+$money+$ti+$yu;
        $dollar     = I('money');
        if($dollar > $restmoney){
            jsonpReturn('0','余额不足，请去充值！',$restmoney);
        }
        $a['money'] = -$dollar;
        $a['message'] = 2;
        $a['uid']  =$userid;
        $a['paymenttype'] = 1; //余额支付
        $a['createtime']  = time();
        $a['status'] = 1;
        $account = M('account')->where('uid='.$userid)->add($a);
        $data['money'] = $dollar;
        $data['payment'] = 1; //余额支付
        $data['status'] = 3; //余额已支付
        $data['createtime']  = time();
        $result = M('order')->where('id='.$id)->save($data);

        if ($result && $account){
                $order = M('order')->where('id=%d',$id)->find();
                $userinfo = M('user')->where('id='.$userid)->getField("is_cost");
                if($userinfo == 0){
                    qrcode($userid);//生成推广二维码
                    M('user')->where('id='.$userid)->save(['is_cost'=>1,'qrcode'=>'http://www.jinxinenjoy.com/Uploads/qrcode/qrcode'.$userid.'.png']);
                }
                rebate($order['order_num']);//插入奖学金表
                grant($order['order_num']);//自动动态收益（助学金）
                jsonpReturn('1','支付成功！','');
        }else{
                jsonpReturn('0','支付失败！','');
            }
        }
    //线下支付方式调转
    public function offline(){
        $id = I('id');
        $pay = M('payment');
        $info = $pay->where('id=%d',$id)->field("number,imgqrcode")->find();
        $result['info'] = $info;
        jsonpReturn('1','查询成功',$result);
    }
    //线下支付方式调转
    public function zhioffline(){
        $id = I('id');
        $pay = M('payment');
        $info = $pay->where('id=%d',$id)->field("number,imgqrcode")->find();
        $result['info'] = $info;
        jsonpReturn('1','查询成功',$result);
    }
    //余额充值线下支付1
    public function chongzhi(){
        $id = I('id');
        $pay = M('payment');
        $info = $pay->where('id=%d',$id)->field("number,imgqrcode")->find();
        $result['info'] = $info;
        jsonpReturn('1','查询成功',$result);
    }
    //余额充值线下支付2
    public function chongzhi2(){
        $id = I('id');
        $pay = M('payment');
        $info = $pay->where('id=%d',$id)->field("number,imgqrcode")->find();
        $result['info'] = $info;
        jsonpReturn('1','查询成功',$result);
    }
    //余额充值线下支付
    public function yuement(){
        $userid = $_SESSION['userid'];
        $user = M('user');
        $account = M('account');
        //获取支付方式
        $data['paymenttype'] = I('payment');            //支付方式
        $data['transaction_number'] = I('number');  //交易号
        $data['money'] = I('money');                //付款金额
        $data['message'] = 3;
        $data['status'] = 0;
        $data['createtime'] = time();
        $data['uid'] = $userid;
        if(empty($data['paymenttype']) && empty($data['money']) && empty($data['transaction_number'])){
            jsonpReturn('0','请确认提交的信息完整!','');
        }else{
            $result = $account->add($data);
            if ($result > 0) {
                jsonpReturn('1','支付成功','');
            }else{
                jsonpReturn('0','支付失败','');
            }
        }
    }
}
//查询用户信息   参数 ：用户id
function user_info($uid){
    $u = M('user');
    $user = $u->where('id='.$uid)->find();
    return $user;
}