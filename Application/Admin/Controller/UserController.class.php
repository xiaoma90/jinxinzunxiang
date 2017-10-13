<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends Controller {
    /**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
           $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }
    /**
    * 用户管理
    */
    public function user(){
        $user = M('user');
        $order = M('order');
        $with = M('withdrawals');
        $account = M('account');
        $data = $user->where('isdel= 0')->order('id desc')->select();
        $num =count($data);
        foreach ($data as $k => $v) {
            $data[$k]['tphone'] = $user->where('id = '.$v['pid'])->find()['phone'];
            $data[$k]['tname'] = $user->where('id = '.$v['pid'])->find()['nickname'];
            $data[$k]['money'] = $account->where('uid = '.$v['id'])->sum('money');
            $data[$k]['bnm'] = $with->where('uid = '.$v['id'].' and status = "1"')->sum('money');
            
            $a= $user->where('pid = '.$v['id'])->select();
            $data[$k]['person'] =count($a);

            $b= $order->where('uid = '.$v['id'])->select();
            $data[$k]['ordernum'] =count($b);
            $c= $order->where('uid = '.$v['id'].' and type = "1"')->select();
            $data[$k]['orderchu'] =count($c);
            $d= $order->where('uid = '.$v['id'].' and type = "2"')->select();
            $data[$k]['orderzai'] =count($d);
            $data[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
        }
        // var_dump($t);
       
        

        $this->assign('data',json_encode($data));
        $this->assign("num",$num);
        $this->display();       
    }

    /**
    * 根据id获取单个用户
    */
    public function getOneUser(){
        $id = I('id');
        $user   = M('user');
        $data = $user->where("id=%d",$id)->select();
        foreach ($data as $key => $value) {
            $data = $user->where('id = '.$value['pid'])->find();
            // var_dump($data);die;

        }
        $data['id'] = $id;
        $this->ajaxReturn($data); 
    }  
    /**
    * 模糊查询会员
    */
    public function searchUser(){
        $user = M('user');
        $order = M('order');
        $with = M('withdrawals');
        $account = M('account');
        $tphone = $_GET['tphone'];
        $tname = $_GET['tname'];
        $name = $_GET['name'];
        $where = [];
        if((I('start')) != "" && (I('end')) != ""){
            $start = strtotime(I('start'));
            $end   = strtotime(I('end'));
            $where['createtime'] = ['between',"$start,$end"];
        }
        if(!empty($name)){
            $where['nickname'] = ['like',"%$name%"]; 
        }  
         
        if(!empty($tname)){
            $where['nickname'] = ['like',"%$tname%"];
        }
        if(!empty($tphone)){
            $where['phone'] = ['like','%'.$tphone.'%'];
        }
        
        $data = $user->where($where)->select();
        // var_dump($data);
        foreach ($data as $k => $v) {
            $data[$k]['tphone'] = $user->where('id = '.$v['pid'])->find()['phone'];
            $data[$k]['tname'] = $user->where('id = '.$v['pid'])->find()['nickname'];
            $data[$k]['money'] = $account->where('uid = '.$v['id'])->sum('money');
            $data[$k]['bnm'] = $with->where('uid = '.$v['id'].' and status = "1"')->sum('money');
            $a= $user->where('pid = '.$v['id'])->select();
            $data[$k]['person'] =count($a);
            $b= $order->where('uid = '.$v['id'])->select();
            $data[$k]['ordernum'] =count($b);
            $c= $order->where('uid = '.$v['id'].' and type = "1"')->select();
            $data[$k]['orderchu'] =count($c);
            $d= $order->where('uid = '.$v['id'].' and type = "2"')->select();
            $data[$k]['orderzai'] =count($d);
        }
        $this->ajaxReturn($data);
    }

    /**
    * 保存(新添 | 修改)的用户信息
    */

    //根据手机号查找修改pid
    public function saveUser(){
        $userid     = (int)I('userid');
        $user       = M('user');
        $phone      = I('tel');
        // var_dump($userid);
        // var_dump($phone);die;
        if(!empty($phone)){
            $where['phone'] = ['like','%'.$phone.'%'];
        }
        $dasa = $user->where($where)->find()['id'];
        // var_dump($dasa);die;
        if ($dasa) {
            $data['pid']   = $dasa;           
            $res = $user->where("id=%d",$userid)->save($data);
            if ($res) {
                $this->success("更新成功!",U('Admin/User/user'));
            }else{
                $this->error("更新失败!");
            }

        }
    }
    /**
    * 冻结指定id用户
    */
    public function dongUser(){
        $id   = I('get.id');
        $user = M('user');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 0){
                $data['status'] = 1;
            }else{
                $data['status'] = 0;
            }
        }
        $result = $user->where("id=%d",$id)->save($data);
        // var_dump($result);die;
        if($result){
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
        $user = M('user');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
}