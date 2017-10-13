<?php
namespace Admin\Controller;
use Think\Controller;

class ArticleController extends Controller {
	/**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
           $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }

    /**
    * 文章管理
    */
    public function article(){
        $user = M('info');
        $info = $user->where("isdel = 0 ")->order('id desc')->select();
        foreach ($info as $key => $value) {
            $info[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
        }
        // var_dump($users);
        $this->assign("info",json_encode($info));
        $this->display();
    }

    /**
    * 根据id获取单个文章
    */
    public function getOneUser(){
        $userid = I('id');
        $user   = M('info');
        // var_dump($user);
        $info  = $user->where("id=%d",$userid)->find();
        // var_dump($users);die;
        $this->ajaxReturn($info); 
    }

    /**
    * 查询文章
    */
    public function searchState(){
        $state = M('info');
        $name = $_GET['name'];
        $star = $_GET['start'];
        $en = $_GET['end'];
        $where = [];
        if(!empty($star) && !empty($en)){
            $start = strtotime($star);
            $end   = strtotime($en);
            $where['createtime'] = ['between',"$start,$end"];
        }
        if(!empty($name)){
            $where['title'] = ['like',"%$name%"];
        }
        $info = $state->where($where)->select();
        $this->ajaxReturn($info);
    }
    /**
    * 保存(新添 | 修改)的文章信息
    */
    public function saveUser(){
        $userid     = (int)I('userid');
        $user       = M('info');
        $data['title']   = I('title');
        $data['classid']   = I('classid');
        $data['author']       = I('author');
        $data['content']       = I('content');
        $data['description']       = I('description');
        $data['thumbnail']= I('thumbnail');
        if ($userid) {
            // $data['isenable']   = I('isenable');
            $res = $user->where("id=%d",$userid)->save($data);
            if ($res) {
                $this->success("更新成功!",U('Admin/Article/article'));
            }else{
                $this->error("更新失败!");
            }
        }else{
            $name = $user->getField("title",true);
            if (!in_array($data['title'],$name)) {
              $data['createtime'] = time();
              $res = $user->add($data);
              if ($res) {
                $this->success("添加成功!",U('Admin/Article/article'));
              }else{
                $this->error("添加失败!");
              }                            
            }else{
                $this->error("标题已存在!");
            }
        }
    }
    /**
    * 删除指定id文章
    */
    public function deleteUser(){
        $id   = I('get.id');
        $user = M('info');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
    public function getInfo(){
        $userid = I('id');
        $user   = M('info');
        $users  = $user->where("id=%d",$userid)->find(); 
        // $this->ajaxReturn($users);  
        $this->assign("data",$users);
        $this->display('addaticle');     
    }
    //文本编辑器
    public function ueditor(){
        $data = new \Org\Util\Ueditor();
        echo $data->output();
    }
}