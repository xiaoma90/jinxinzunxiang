<?php
namespace Admin\Controller;
use Think\Controller;

class ConfigController extends Controller {
	/**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
           $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }

    /**
    * 系统设置管理
    */
    public function index(){
        $config = M('config');
        $data = $config->select();
        foreach ($data as $k => $v) { 
            $b[] = $v['one'];
            $c[] = $v['two'];
            $d[] = $v['three'];
            $e[] = $v['four'];
            $f[] = $v['five'];
            $t[] = $v['cash_commission'];
            $n[] = $v['lower_cash'];
            $m[] = $v['cycle'];
            $p[] = $v['unit_price'];
            $l[] = $v['person_day'];
        }
        // var_dump($a);
        $this->assign("a",$a);
        $this->assign("b",$b);
        $this->assign("c",$c);
        $this->assign("d",$d);
        $this->assign("e",$e);
        $this->assign("f",$f);

        $this->assign("t",$t);
        $this->assign("n",$n);
        $this->assign("m",$m);
        $this->assign("p",$p);
        $this->assign("l",$l);
    	$this->display();
    }

    /**
    * 会员各级别分佣比例设置
    */
    public function save(){
        $id = 1;
        $data['one']    = I('one');
        $data['two']    = I('two');
        $data['three']    = I('three');
        $data['four']    = I('four');
        $data['five']    = I('five');
        $config      = M('config');
        
        $res = $config->where('id = '.$id)->save($data);
        if ($res) {
            $this->success("1","更新成功!","");
        }else{
            $this->error("更新失败!");
        }          
    }

    /**
    * 提现设置
    */
    public function Withdrawals(){
        $config = M('config');
        $id = 1;
        $data['cash_commission']    = I('cash');
        $data['lower_cash']    = I('lower');
        $data['cycle']    = I('cycle');
        
        $config      = M('config');
        $res = $config->where('id = '.$id)->save($data);
        if ($res) {
            $this->success("1","更新成功!","");
        }else{
            $this->error("更新失败!");
        }          
    }

    /**
    * 理财产品设置
    */
    public function price(){
        $config = M('config');
        $id = 1;
        $data['unit_price']    = I('unit_price');
        $data['person_day']    = I('person_day');
        
        $config      = M('config');
        $res = $config->where('id = '.$id)->save($data);
        if ($res) {
            $this->success("1","更新成功!","");
        }else{
            $this->error("更新失败!");
        }          
    }
}