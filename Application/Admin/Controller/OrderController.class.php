<?php
namespace Admin\Controller;
use Think\Controller;

class OrderController extends Controller {
	/**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
           $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }

    /**
    * 订单管理首页
    */
    public function index(){
        $order  = M('order');
        $orders =$order->where("status ='3'")->order('id desc')->select();
        foreach ($orders as $key => $value) {
            $orders[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
        }
        $this->assign("orders",json_encode($orders));
        $this->display();
    }
    /**
    * 模糊查询订单名称
    */
    public function searchState(){
        $ord = M('order');
        $phone = $_GET['phone'];
        $order = $_GET['order'];
        $name = $_GET['name'];
        $star = $_GET['start'];
        $en = $_GET['end'];
        $where = [];
        if(!empty($star) && !empty($en)){
            $start = strtotime($star);
            $end   = strtotime($en);
            $where['createtime'] = ['between',"$start,$end"];
        }
        if(!empty($phone)){
            $where['getphone'] = ['like',"%$phone%"];
        }
        if(!empty($name)){
            $where['getname'] = ['like',"%$name%"];
        }
        if(!empty($order)){
            $where['order_num'] = ['like',"%$order%"];
        }  
        $orders = $ord->where($where)->order('id desc')->select(); 
        $this->ajaxReturn($orders); 
    }
    /**
    * 获取所有订单id，name
    */
    public function getContact(){
        $contact = M('contact');
        $contacts= $contact->field("id,name")->where("isdelete =0")->select(); 
        return $contacts; 
    }
    /**
    * 在线订单管理
    */
    public function ordering(){
        $order  = M('order');
        $orders =$order->where("type = '1' and status ='3'")->order('id desc')->select();
        foreach ($orders as $key => $value) {
            $orders[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
        }
        $this->assign("orders",json_encode($orders));
        $this->display();
    }
    /**
    * 模糊查询在线订单名称
    */
    public function searchIng(){
        $ord = M('order');
        $phone = $_GET['phone'];
        $order = $_GET['order'];
        $name = $_GET['name'];
        $star = $_GET['start'];
        $en = $_GET['end'];
        $where = [];
        if(!empty($star) && !empty($en)){
            $start = strtotime($star);
            $end   = strtotime($en);
            $where['createtime'] = ['between',"$start,$end"];
        }
        if(!empty($phone)){
            $where['getphone'] = ['like',"%$phone%"];
        }
        if(!empty($name)){
            $where['getname'] = ['like',"%$name%"];
        }
        if(!empty($order)){
            $where['order_num'] = ['like',"%$order%"];
        }  
        $orders = $ord->where("type='1' and status ='3'")->where($where)->order('id desc')->select(); 
        $this->ajaxReturn($orders); 
    }
    /**
    * 出局订单管理
    */
    public function orderout(){
        $order  = M('order');
        $orders =$order->where("type = '2' and status ='3'")->order('id desc')->select();
        foreach ($orders as $key => $value) {
            $orders[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
        }
        $this->assign("orders",json_encode($orders));
        $this->display();
    }
    /**
    * 模糊查询出局订单名称
    */
    public function searchOut(){
        $ord = M('order');
        $phone = $_GET['phone'];
        $order = $_GET['order'];
        $name = $_GET['name'];
        $where = [];
        $star = $_GET['start'];
        $en = $_GET['end'];
        $where = [];
        if(!empty($star) && !empty($en)){
            $start = strtotime($star);
            $end   = strtotime($en);
            $where['createtime'] = ['between',"$start,$end"];
        }
        if(!empty($phone)){
            $where['getphone'] = ['like',"%$phone%"];
        }
        if(!empty($name)){
            $where['getname'] = ['like',"%$name%"];
        }
        if(!empty($order)){
            $where['order_num'] = ['like',"%$order%"];
        }  
        $orders = $ord->where("type='2' and status ='3'")->where($where)->order('id desc')->select(); 
        $this->ajaxReturn($orders); 
    }

    /**
    * 未支付订单管理
    */
    public function wei(){
        $order  = M('order');
        $orders =$order->where("status = '1'")->order('id desc')->select();
        foreach ($orders as $key => $value) {
            $orders[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
        }
        $this->assign("orders",json_encode($orders));
        $this->display();
    }
    /**
    * 模糊查询未支付订单名称
    */
    public function searchwei(){
        $ord = M('order');
        $phone = $_GET['phone'];
        $order = $_GET['order'];
        $name = $_GET['name'];
        $where = [];
        $star = $_GET['start'];
        $en = $_GET['end'];
        $where = [];
        if(!empty($star) && !empty($en)){
            $start = strtotime($star);
            $end   = strtotime($en);
            $where['createtime'] = ['between',"$start,$end"];
        }
        if(!empty($phone)){
            $where['getphone'] = ['like',"%$phone%"];
        }
        if(!empty($name)){
            $where['getname'] = ['like',"%$name%"];
        }
        if(!empty($order)){
            $where['order_num'] = ['like',"%$order%"];
        }  
        $orders = $ord->where("status = '1'")->where($where)->order('id desc')->select(); 
        $this->ajaxReturn($orders); 
    }
    /**
    * 删除指定订单
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
    * 删除指定订单
    */
    public function deleteindex(){
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
    * 删除指定订单
    */
    public function deleteing(){
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
    * 删除指定订单
    */
    public function deleteout(){
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