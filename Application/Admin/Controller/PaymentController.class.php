<?php
namespace Admin\Controller;
use Think\Controller;
/**
* 轮播图管理
*/

class PaymentController extends Controller {
	# 轮播图列表
	public function index(){
		$payment = M('payment');
        $pay = $payment->where("isdel = 0 ")->order('id desc')->select();
        $this->assign("pay",json_encode($pay));
        $this->display();
	}
    /**
    * 保存(新添 | 修改)的信息
    */
    public function saveUser(){
        $userid     = (int)I('userid');
        $payment       = M('payment');
        $data['author']       = I('author');
        // $data['remaerk']       = I('remaerk');
        $data['imgqrcode']= I('img');
        $data['number']= I('number');
        $data['type']= I('type');
        if ($userid) {
            $res = $payment->where("id=%d",$userid)->save($data);
            if ($res) {
                $this->success("更新成功!",U('Admin/Payment/index'));
            }else{
                $this->error("更新失败!");
            }
        }else{
            $name = $payment->getField("author",true);
            if (!in_array($data['author'],$name)) {
              $data['createtime'] = time();
              $res = $payment->add($data);
              if ($res) {
                $this->success("添加成功!",U('Admin/Payment/index'));
              }else{
                $this->error("添加失败!");
              }                            
            }else{
                $this->error("已存在!");
            }
        }
    }
    /**
    * 删除指定id
    */
    public function delete(){
        $id   = I('get.id');
        $user = M('payment');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
    public function getinfo(){
        $userid = I('id');
        $user   = M('payment');
        $users  = $user->where("id=%d",$userid)->find(); 
        $this->assign("data",$users);
        $this->display('addpay');     
    }
    //文本编辑器
    public function ueditor(){
        $data = new \Org\Util\Ueditor();
        echo $data->output();
    }
    // Recharge
    //余额充值管理
    public function recharge(){

        $account = M('account');
        $user = M('user');
        $data = $account->where("message ='3' ")->order('id desc')->select();
        // var_dump($data); 
        foreach ($data as $k => $v) {
            $data[$k]['phone'] = $user->where('id = '.$v['uid'])->find()['phone'];
            $data[$k]['rname'] = $user->where('id = '.$v['uid'])->find()['rname'];
            $data[$k]['nickname'] = $user->where('id = '.$v['uid'])->find()['nickname'];
        }
        // var_dump($data);die;
        $this->assign('data',json_encode($data));
        $this->display();
    }
    //余额充值查询人
    public function payinfo(){
        $id = I('id');
        $account   = M('account');
        $accounts  = $account->where("id=%d",$id)->find(); 
        $user = M('user')->where("id=%d",$accounts['uid'])->find()['phone'];
        $accounts['phone'] = $user;
        $this->assign("data",$accounts);
        $this->display('addrecharge');     
    }
    //余额充值
    public function savepay(){
        $userid     = (int)I('userid');
        $account       = M('account');
        $data['pname']       = I('pname');
        // $data['remaerk']       = I('remaerk');
        $phone = I('phone');
        if($phone != null){
            $tmd = M('user')->where("phone='%s'",$phone)->find();
            // var_dump($tmd);die;
            if($tmd){
                $data['source'] = $tmd['id'];
            }
        }
        $data['money']= I('money');
        $data['status']= 1;
        // $data['uid']=$userid;
        if($userid){
            $data['agreetime'] = time();
            $res = $account->where("id=%d",$userid)->save($data);
            if($res){
                $this->success("提交成功!",U('Admin/Payment/recharge'));
            }else{
                $this->error("提交失败!");
            }                            
        }
    }
    //驳回
    public function paybo(){
        $id   = I('get.id');
        $user   = M('account');
        if($id){
            $pwd = $user->where("id=%d",$id)->getField("status");
            if($pwd == 0){
                $data['status'] = 2;
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
    /**
    * 删除指定id用户
    */
    public function deleteRe(){
        $id   = I('get.id');
        $user = M('account');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
}
