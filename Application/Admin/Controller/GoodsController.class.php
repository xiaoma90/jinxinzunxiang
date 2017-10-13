<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends Controller {
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
    public function index(){
        $good = M('goods');
        $goods = $good->where("isdel = 0 ")->select();
        foreach ($goods as $key => $value) {
            $goods[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
        }
        $this->assign("goods",json_encode($goods));
        $this->display();
    }

    /**
    * 查询文章
    */
    public function searchState(){
        $good = M('goods');
        $name = $_GET['name'];
        $author = $_GET['author'];
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
        if(!empty($author)){
            $where['author'] = ['like',"%$author%"];
        }
        $goods = $good->where($where)->select();
        $this->ajaxReturn($goods);
    }
    /**
    * 保存(新添 | 修改)的文章信息
    */
    public function saveUser(){
        $userid     = (int)I('userid');
        $good       = M('goods');
        $data['title']   = I('title');
        $data['price']   = I('price');
        $data['author']       = I('author');
        $data['content']       = I('content');
        $data['description']       = I('description');
        $data['photo']= I('photo');
        if ($userid) {
            // $data['isenable']   = I('isenable');
            $res = $good->where("id=%d",$userid)->save($data);
            if ($res) {
                $this->success("更新成功!",U('Admin/Goods/index'));
            }else{
                $this->error("更新失败!");
            }
        }else{
            $name = $good->getField("title",true);
            if (!in_array($data['title'],$name)) {
              $data['createtime'] = time();
              $res = $good->add($data);
              if ($res) {
                $this->success("添加成功!",U('Admin/Goods/index'));
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
        $user = M('goods');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
    public function getInfo(){
        $userid = I('id');
        $good   = M('goods');
        $goods  = $good->where("id=%d",$userid)->find();   
        $this->assign("data",$goods);
        $this->display('add');     
    }
    //文本编辑器
    public function ueditor(){
        $data = new \Org\Util\Ueditor();
        echo $data->output();
    }
}