<?php
namespace Admin\Controller;
use Think\Controller;

class StateController extends Controller {
	/**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
            $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }
    public function index(){
		$state  = M('account');
		$user = M('user');
		$data = $state->select();
		foreach ($data as $k => $v) {
			$ud = $user->where('id = '.$v['uid'])->find();
			$data[$k]['phone'] = $ud['phone'];
			$data[$k]['nikname'] = $ud['name'];
		}
		$this->assign('data',json_encode($data));
		$this->display();		
	}
    
    public function searchState(){
    	$state = M('account');
    	$user = M('user');
    	$phone = $_GET['phone'];
    	$reason = $_GET['reason'];
    	$paytype = $_GET['type'];
    	$start = strtotime(I('start'));
    	$end   = strtotime(I('end'));
    	if((I('start')) == ""){
    		$start = 0;
    	}
    	if((I('end')) ==""){
    		$end = time();
    	}
    	if((I('start')) != "" && (I('end')) !=""){
    		$start = min($start,$end);
    		$end   = max($start,$end);
    	}	
    	$where = [];
    	if(!empty($reason)){
    		$where['message'] = ['like',"%$reason%"];
    	}
    	if(!empty($paytype)){
    		$where['paymenttype'] = ['like',"%$paytype%"];
    	}
    	$where['createtime'] = ['between',"$start,$end"];
    	if(!empty($phone)){
            $users['phone'] = ['like','%'.$phone.'%'];
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
			$data[$k]['nikname'] = $ud['name'];
		}
    	$this->ajaxReturn($data);
    }
    
	
}
