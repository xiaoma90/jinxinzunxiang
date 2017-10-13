<?php
namespace Admin\Controller;
use Think\Controller;

class DeliverController extends Controller {
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
        $deliver  = M('deliver');
        $user = M('user');
        $order = M('order');
        $users = $user->select();
        $data =$deliver->order('id desc')->select();
        foreach ($data as $key => $value) {
            $data[$key]['phone'] = $user->where('id = '.$value['uid'])->find()['phone'];
            $data[$key]['order_num'] = $order->where('uid = '.$value['uid'])->find()['order_num'];
            $data[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
        }
        $this->assign("data",json_encode($data));
        $this->display();
    }
    /**
    * 模糊查询订单名称
    */
    public function searchDeliver(){
        $ord = M('order');
        $user = M('user');
        $deliver = M('deliver');
        $phone = $_GET['phone'];
        $order = $_GET['order'];
        $name = $_GET['name'];
        $mobile = $_GET['mobile'];
        $where = [];
        // if(!empty(I('start')) && !empty(I('end'))){
        //     $start = strtotime(I('start'));
        //     $end   = strtotime(I('end'));
        //     $where['createtime'] = ['between',"$start,$end"];
        // }
        if(!empty($phone)){
            $users['phone'] = ['like',"%$phone%"];
            $uid = $user->where($users)->select();
        }
        if(!empty($order)){
            $orde['order_num'] = ['like',"%$order%"];
            $or = $ord->where($orde)->select();
        }
        if(!empty($name)){
            $where['receiver'] = ['like',"%$name%"];
        }
        if(!empty($mobile)){
            $where['mobile'] = ['like',"%$mobile%"];
        }
        if(!empty($uid) || !empty($or)){
            foreach ($uid as $k => $v) {
                $where['uid'] = $v['id'];
                $das = $deliver->where($where)->select();
                if(!empty($das)){
                    foreach ($das as $k => $v) {
                        $data[] = $v;
                    }
                }
            }
            foreach ($or as $k => $v) {
                $where['uid'] = $v['uid'];
                $das = $deliver->where($where)->select();
                if(!empty($das)){
                    foreach ($das as $k => $v) {
                        $data[] = $v;
                    }
                }
            }
        }else{
            $data = $deliver->where($where)->select();
        }
        // $data = $deliver->where($where)->select();
        foreach ($data as $k => $v) {
            $data[$k]['phone'] = $user->where('id = '.$v['uid'])->find()['phone'];
            $data[$k]['order_num'] = $ord->where('uid = '.$v['uid'])->find()['order_num'];
        }  
        $this->ajaxReturn($data); 
    }
    /**
    * 删除指定id用户
    */
    public function deleteUser(){
        $id   = I('get.id');
        $user = M('deliver');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
}