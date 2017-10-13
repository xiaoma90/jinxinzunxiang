<?php
namespace Admin\Controller;
use Think\Controller;

class WithdrawalsController extends Controller {
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
        $with  = M('withdrawals');
        $user = M('user');
        $data =$with->order('id desc')->select();
        // var_dump($data);die;
        foreach ($data as $key => $value) {
            $data[$key]['phone'] = $user->where('id = '.$value['uid'])->find()['phone'];
            $a =$value['money'];
            $data[$key]['money'] = -$a;
            $data[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
        }
        $this->assign("data",json_encode($data));
        $this->display();
    }
    /**
    * 模糊查询订单名称
    */
    public function searchWith(){
        $with = M('withdrawals');
        $user = M('user');
        $phone = $_GET['phone'];
        $change = $_GET['change'];
        $star = $_GET['start'];
        $en = $_GET['end'];
        $where = [];
        if(!empty($star) && !empty($en)){
            $start = strtotime($star);
            $end   = strtotime($en);
            $where['createtime'] = ['between',"$start,$end"];
        }
        if(!empty($phone)){
            $users['phone'] = ['like',"%$phone%"];
            $uid = $user->where($users)->select();
        }
        if(!empty($change)){
            $where['payment'] = ['like',"%$change%"];
        }
        if(!empty($uid)){
            foreach ($uid as $k => $v) {
                $where['uid'] = $v['id'];
                $das = $with->where($where)->select();
                if(!empty($das)){
                    foreach ($das as $k => $v) {
                        $data[] = $v;
                    }
                }
            }
            
        }else{
            $data = $with->where($where)->select();
        }
        foreach ($data as $k => $v) {
            $data[$k]['phone'] = $user->where('id = '.$v['uid'])->find()['phone'];
        }  
        $this->ajaxReturn($data); 
    }
    //同意
    public function getInfo(){
        // $users  = $user->where("id=%d",$userid)->find(); 
        // $this->assign("data",json_encode($users));
        // $this->display(); 
        $id   = I('get.id');
        $user   = M('withdrawals');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 0){
                $data['status'] = 3;
            }
        }
        $a = $user->where("id=%d",$id)->find()['createtime'];
        $res = M('account')->where("createtime='%s'",$a)->save($data);
        $result = $user->where("id=%d",$id)->save($data);
        // var_dump($result);die;
        if($result && $res){
            echo "true";
        }else{
            echo "false";
        }    
    }
    //驳回
    public function BoUser(){
        $id   = I('get.id');
        $user   = M('withdrawals');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 0){
                $data['status'] = 2;
            }
        }
        $result = $user->where("id=%d",$id)->save($data);
        $a = $user->where("id=%d",$id)->find()['createtime'];
        $res = M('account')->where("createtime='%s'",$a)->save($data);
        // var_dump($result);die;
        if($result && $res){
            echo "true";
        }else{
            echo "false";
        }    
    }
    /**
    * 删除指定id用户
    */
    public function deleteUser(){
        $id   = I('get.id');
        $user = M('withdrawals');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
}