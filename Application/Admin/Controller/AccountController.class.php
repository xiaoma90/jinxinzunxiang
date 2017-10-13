<?php
namespace Admin\Controller;
use Think\Controller;

class AccountController extends Controller {
    /**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
           $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }
    /**
    * 支付方式管理
    */
    public function index(){
        $state  = M('order');
        $user = M('user');
        $data = $state->order('id desc')->select();
        foreach ($data as $k => $v) {
            $data[$k]['phone'] = $user->where('id = '.$v['uid'])->find()['phone'];
            $data[$k]['nickname'] = $user->where('id = '.$v['uid'])->find()['nickname'];
            $data[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
        }
        $this->assign('data',json_encode($data));
        $this->display();
    }
    /**
    * 模糊查询会员支付方式
    */
    public function searchState(){
        $state = M('account');
        $user = M('user');
        $phone = $_GET['phone'];
        $name = $_GET['name'];
        $change = $_GET['change'];
        $where = [];
        if((I('start')) != "" && (I('end')) != ""){
            $start = strtotime(I('start'));
            $end   = strtotime(I('end'));
            $where['createtime'] = ['between',"$start,$end"];
        }   
        if(!empty($change)){
            
            $where['paymenttype'] = ['like',"%$change%"];
        }
        if(!empty($phone)){
            $users['phone'] = ['like','%'.$phone.'%'];
            $uid = $user->where($users)->select();
        }
        if(!empty($name)){
            $users['nickname'] = ['like','%'.$name.'%'];
            $uid = $user->where($users)->select();
        }  
        if(!empty($uid)){
            foreach ($uid as $k => $v) {
                $where['uid'] = $v['id'];
                $das = $state->where($where)->select();
                if(!empty($das)){
                    foreach ($das as $k => $v) {
                        $data[] = $v;
                    }
                }
            }
        }else{
            $data = $state->where($where)->select();
        }   
        foreach ($data as $k => $v) {
            $ud = $user->where('id = '.$v['uid'])->find();
            $data[$k]['phone'] = $ud['phone'];
            $data[$k]['nickname'] = $ud['nickname'];
        }
        $this->ajaxReturn($data);
    }
    /**
    * 余额支付管理
    */
    public function rest(){
        $state  = M('order');
        $user = M('user');
        $data = $state->where('payment = "1"')->order('id desc')->select();
        foreach ($data as $k => $v) {
            $data[$k]['phone'] = $user->where('id = '.$v['uid'])->find()['phone'];
            $data[$k]['nickname'] = $user->where('id = '.$v['uid'])->find()['nickname'];
            $data[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
        }
        $this->assign('data',json_encode($data));
        $this->display();
    }
    /**
    * 模糊查询会员余额支付
    */
    public function searchRest(){
        $state = M('order');
        $user = M('user');
        $phone = $_GET['phone'];
        $name = $_GET['name'];
        $where = [];
        if((I('start')) != "" && (I('end')) != ""){
            $start = strtotime(I('start'));
            $end   = strtotime(I('end'));
            $where['createtime'] = ['between',"$start,$end"];
        }   
        if(!empty($phone)){
            $users['phone'] = ['like','%'.$phone.'%'];
            $uid = $user->where($users)->select();
        } 
        if(!empty($name)){
            $users['nickname'] = ['like','%'.$name.'%'];
            $uid = $user->where($users)->select();
        }
        if(!empty($uid)){
            foreach ($uid as $k => $v) {
                $where['uid'] = $v['id'];
                $das = $state->where('payment = "1"')->where($where)->select();
                if(!empty($das)){
                    foreach ($das as $k => $v) {
                        $data[] = $v;
                    }
                }
            }
        }else{
            $data = $state->where('payment ="1"')->where($where)->select();
        }   
        foreach ($data as $k => $v) {
            $ud = $user->where('id = '.$v['uid'])->find();
            $data[$k]['phone'] = $ud['phone'];
            $data[$k]['nickname'] = $ud['nickname'];
        }
        $this->ajaxReturn($data);
    }
    /**
    * 微信支付管理
    */
    public function weixin(){
        $state  = M('order');
        $user = M('user');
        $data = $state->where('payment = "2"')->order('id desc')->select();
        foreach ($data as $k => $v) {
            $data[$k]['phone'] = $user->where('id = '.$v['uid'])->find()['phone'];
            $data[$k]['nickname'] = $user->where('id = '.$v['uid'])->find()['nickname'];
            $data[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
        }
        $this->assign('data',json_encode($data));
        $this->display();
    }
    /**
    * 模糊查询会员微信支付
    */
    public function searchWei(){
        $state = M('order');
        $user = M('user');
        $phone = $_GET['phone'];
        $name = $_GET['name'];
        $where = [];
        if((I('start')) != "" && (I('end')) != ""){
            $start = strtotime(I('start'));
            $end   = strtotime(I('end'));
            $where['createtime'] = ['between',"$start,$end"];
        }   
        if(!empty($phone)){
            $users['phone'] = ['like','%'.$phone.'%'];
            $uid = $user->where($users)->select();
        } 
        if(!empty($name)){
            $users['nickname'] = ['like','%'.$name.'%'];
            $uid = $user->where($users)->select();
        }
        if(!empty($uid)){
            foreach ($uid as $k => $v) {
                $where['uid'] = $v['id'];
                $das = $state->where('payment = "2"')->where($where)->select();
                if(!empty($das)){
                    foreach ($das as $k => $v) {
                        $data[] = $v;
                    }
                }
            }
        }else{
            $data = $state->where('payment ="2"')->where($where)->select();
        }   
        foreach ($data as $k => $v) {
            $ud = $user->where('id = '.$v['uid'])->find();
            $data[$k]['phone'] = $ud['phone'];
            $data[$k]['nickname'] = $ud['nickname'];
        }
        $this->ajaxReturn($data);
    }
    /**
    * 支付宝支付管理
    */
    public function zhi(){
        $state  = M('order');
        $user = M('user');
        $data = $state->where('payment = "3"')->order('id desc')->select();
        foreach ($data as $k => $v) {
            $data[$k]['phone'] = $user->where('id = '.$v['uid'])->find()['phone'];
            $data[$k]['nickname'] = $user->where('id = '.$v['uid'])->find()['nickname'];
            $data[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
        }
        $this->assign('data',json_encode($data));
        $this->display();
    }
    /**
    * 模糊查询会员支付宝支付
    */
    public function searchZhi(){
        $state = M('order');
        $user = M('user');
        $phone = $_GET['phone'];
        $name = $_GET['name'];
        $where = [];
        if((I('start')) != "" && (I('end')) != ""){
            $start = strtotime(I('start'));
            $end   = strtotime(I('end'));
            $where['createtime'] = ['between',"$start,$end"];
        }   
        if(!empty($phone)){
            $users['phone'] = ['like','%'.$phone.'%'];
            $uid = $user->where($users)->select();
        } 
        if(!empty($name)){
            $users['nickname'] = ['like','%'.$name.'%'];
            $uid = $user->where($users)->select();
        }
        if(!empty($uid)){
            foreach ($uid as $k => $v) {
                $where['uid'] = $v['id'];
                $das = $state->where('payment = "3"')->where($where)->select();
                if(!empty($das)){
                    foreach ($das as $k => $v) {
                        $data[] = $v;
                    }
                }
            }
        }else{
            $data = $state->where('payment ="3"')->where($where)->select();
        }   
        foreach ($data as $k => $v) {
            $ud = $user->where('id = '.$v['uid'])->find();
            $data[$k]['phone'] = $ud['phone'];
            $data[$k]['nickname'] = $ud['nickname'];
        }
        $this->ajaxReturn($data);
    }
    /**
    * 银行卡支付管理
    */
    public function bank(){
        $state  = M('order');
        $user = M('user');
        $data = $state->where('payment = "4"')->order('id desc')->select();
        foreach ($data as $k => $v) {
            $data[$k]['phone'] = $user->where('id = '.$v['uid'])->find()['phone'];
            $data[$k]['nickname'] = $user->where('id = '.$v['uid'])->find()['nickname'];
            $data[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
        }
        $this->assign('data',json_encode($data));
        $this->display();
    }
    /**
    * 模糊查询会员银行卡支付
    */
    public function searchBank(){
        $state = M('order');
        $user = M('user');
        $phone = $_GET['phone'];
        $name = $_GET['name'];
        $where = [];
        if((I('start')) != "" && (I('end')) != ""){
            $start = strtotime(I('start'));
            $end   = strtotime(I('end'));
            $where['createtime'] = ['between',"$start,$end"];
        }   
        if(!empty($phone)){
            $users['phone'] = ['like','%'.$phone.'%'];
            $uid = $user->where($users)->select();
        } 
        if(!empty($name)){
            $users['nickname'] = ['like','%'.$name.'%'];
            $uid = $user->where($users)->select();
        }
        if(!empty($uid)){
            foreach ($uid as $k => $v) {
                $where['uid'] = $v['id'];
                $das = $state->where('payment = "4"')->where($where)->select();
                if(!empty($das)){
                    foreach ($das as $k => $v) {
                        $data[] = $v;
                    }
                }
            }
        }else{
            $data = $state->where('payment ="4"')->where($where)->select();
        }   
        foreach ($data as $k => $v) {
            $ud = $user->where('id = '.$v['uid'])->find();
            $data[$k]['phone'] = $ud['phone'];
            $data[$k]['nickname'] = $ud['nickname'];
        }
        $this->ajaxReturn($data);
    }
    
    //同意
    public function resttong(){
        $id   = I('get.id');
        $user   = M('order');
        if($id){
            $a = $user->where("id=%d",$id)->find()['createtime'];
            if($a){
                $da['status'] = 1;
            }
        }
        // $result = $user->where("id=%d",$id)->save($data);
        $res = M('account')->where("createtime='%s'",$a)->save($da);
        if( $res){
            echo "true";
        }else{
            echo "false";
        }    
    }
    //驳回
    public function restbo(){
        $id   = I('get.id');
        $user   = M('order');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 2){
                $data['status'] = 4;
            }
        }
        $tmd = $user->where("id=%d",$id)->find()['createtime'];
        if($tmd){
            $da['status'] = 2;
        }
        $result = $user->where("id=%d",$id)->save($data);
        $res = M('account')->where("createtime ='%s'",$tmd)->save($da);
        if($result && $res){
            echo "true";
        }else{
            echo "false";
        }    
    }
    //同意
    public function weitong(){
        $id   = I('get.id');
        $user   = M('order');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 2){
                $data['status'] = 3;
            }
        }
        $result = $user->where("id=%d",$id)->save($data);
        if($result){
            $order = $user->where('id=%d',$id)->find();
            $userinfo = M('user')->where('id='.$order['uid'])->getField("is_cost");
            if($userinfo == 0){
                qrcode($order['uid']);//生成推广二维码
                M('user')->where('id='.$order['uid'])->save(['is_cost'=>1,'qrcode'=>'http://www.jinxinenjoy.com/Uploads/qrcode/qrcode'.$order['uid'].'.png']);
            }
                rebate($order['order_num']);//插入奖学金表
                grant($order['order_num']);//自动动态收益（助学金）
            echo "true";
        }else{
            echo "false";
        }    
    }
    //驳回
    public function weibo(){
        $id   = I('get.id');
        $user   = M('order');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 2){
                $data['status'] = 4;
            }
        }
        $result = $user->where("id=%d",$id)->save($data);
         //自动动态收益（助学金）
            // grant($order_num);
        if($result){
            echo "true";
        }else{
            echo "false";
        }    
    }

    //同意
    public function zhitong(){
       
        $id   = I('get.id');
        $user   = M('order');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 2){
                $data['status'] = 3;
            }
        }
        $result = $user->where("id=%d",$id)->save($data);
        if($result){
            $order = M('order')->where('id=%d',$id)->find();
            $userinfo = M('user')->where('id='.$order['uid'])->getField("is_cost");
            if($userinfo == 0){
                qrcode($order['uid']);//生成推广二维码
                M('user')->where('id='.$order['uid'])->save(['is_cost'=>1,'qrcode'=>'http://www.jinxinenjoy.com/Uploads/qrcode/qrcode'.$order['uid'].'.png']);
            }
                rebate($order['order_num']);//插入奖学金表
                grant($order['order_num']);//自动动态收益（助学金）
            echo "true";
        }else{
            echo "false";
        }    
    }
    //驳回
    public function zhibo(){
        $id   = I('get.id');
        $user   = M('order');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 2){
                $data['status'] = 4;
            }
        }
        $result = $user->where("id=%d",$id)->save($data);
        if($result){
            echo "true";
        }else{
            echo "false";
        }    
    }

    //同意
    public function banktong(){
        // $users  = $user->where("id=%d",$userid)->find(); 
        // $this->assign("data",json_encode($users));
        // $this->display(); 
        $id   = I('get.id');
        $user   = M('order');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 2){
                $data['status'] = 3;
            }
        }
        $result = $user->where("id=%d",$id)->save($data);
        if($result){
            $order = M('order')->where('id=%d',$id)->find();
            $userinfo = M('user')->where('id='.$order['uid'])->getField("is_cost");
            if($userinfo == 0){
                qrcode($order['uid']);//生成推广二维码
                M('user')->where('id='.$order['uid'])->save(['is_cost'=>1,'qrcode'=>'http://www.jinxinenjoy.com/Uploads/qrcode/qrcode'.$order['uid'].'.png']);
            }
                rebate($order['order_num']);//插入奖学金表
                grant($order['order_num']);//自动动态收益（助学金）
            echo "true";
        }else{
            echo "false";
        }    
    }
    //驳回
    public function bankbo(){
        $id   = I('get.id');
        $user   = M('order');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 2){
                $data['status'] = 4;
            }
        }
        $result = $user->where("id=%d",$id)->save($data);
        if($result){
            echo "true";
        }else{
            echo "false";
        }    
    }

    /**
    * 删除指定id
    */
    public function deleterest(){
        $id   = I('get.id');
        $user = M('order');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }

    /**
    * 删除指定id
    */
    public function deletewei(){
        $id   = I('get.id');
        $user = M('order');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
    /**
    * 删除指定id
    */
    public function deletezhi(){
        $id   = I('get.id');
        $user = M('order');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
    /**
    * 删除指定id
    */
    public function deletebank(){
        $id   = I('get.id');
        $user = M('order');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
}