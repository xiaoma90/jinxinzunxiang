<?php
namespace Admin\Controller;
use Think\Controller;

class DataController extends Controller {
	/**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
           $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }
    /**
    * 
    */
    public function index(){
        $t = time();
        $starttime = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $endtime = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        // 累计会员总量
        $user = M('user');
        $user = $user->where("isdel = 0 ")->select();
        $num = count($user);

        // 今日新增会员量
        $time['createtime'] = array('between',array($starttime,$endtime));
        $arr = M('user')->where("isdel = 0 ")->where($time)->select();                       
        $numtoday = count($arr);
        // 历史累计收入总额
        $account = M('account');
        $money = $account->sum('money');
        // 已发放提现总额
        $rest = $account->where("message = '1'")->sum('money');
        // 会员账户余额总计
        // $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
        // $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y')); 
        // $monthtime['createtime'] = array('between',array($beginThismonth,$endThismonth));
        $restmoney = $money - $rest;
        // 今日提现申请总计
        $with = M('withdrawals');
        $withd = $with->where($time)->select();
        $todaywith = count($withd);
        // echo (time());
        // 今日提现手续费总计
        $today = $with->where("status = '1'")->where($time)->sum('fee');
        $todayfee = -($today *5/100);
        
        // 今日提现总计
        $b = $with->where($time)->where('status = "1"')->sum('money');

        // 已出局订单统计
        $order = M('order');
        $e = $order->where('type = "2"')->select();
        $f = count($e);
        // 在线订单统计
        $c = $order->select();
        $d = count($c);

        $this->assign("num",$num);
        $this->assign("money",$money);
        $this->assign("rest",$rest);
        $this->assign("restmoney",$restmoney);
        $this->assign("todaywith",$todaywith);
        $this->assign("todayfee",$todayfee);
        $this->assign("b",$b);
        $this->assign("d",$d);
        $this->assign("f",$f);
        
        $this->display();
        
    }
    
}