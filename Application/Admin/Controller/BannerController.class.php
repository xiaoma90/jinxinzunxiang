<?php
namespace Admin\Controller;
use Think\Controller;
/**
* 轮播图管理
*/

class BannerController extends Controller {
	# 轮播图列表
	public function index(){
		$banner = M('lunbo');
        $lunbo = $banner->where("isdel = 0 ")->order('id desc')->select();
        foreach ($lunbo as $key => $value) {
            $lunbo[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
        }
        $this->assign("lunbo",json_encode($lunbo));
        $this->display();
	}
    /**
    * 保存(新添 | 修改)的文章信息
    */
    public function saveUser(){
        $userid     = (int)I('userid');
        $user       = M('lunbo');
        $data['author']       = I('author');
        // $data['content']       = I('content');
        $data['description']       = I('description');
        $data['img']= I('img');
        $data['sort']= I('sort');
        if ($userid) {
            $res = $user->where("id=%d",$userid)->save($data);
            if ($res) {
                $this->success("更新成功!",U('Admin/Banner/index'));
            }else{
                $this->error("更新失败!");
            }
        }else{
            $name = $user->getField("author",true);
            if (!in_array($data['author'],$name)) {
              $data['createtime'] = time();
              $res = $user->add($data);
              if ($res) {
                $this->success("添加成功!",U('Admin/Banner/index'));
              }else{
                $this->error("添加失败!");
              }                            
            }else{
                $this->error("已存在!");
            }
        }
    }
    /**
    * 删除指定id文章
    */
    public function delete(){
        $id   = I('get.id');
        $user = M('lunbo');
        $result = $user->where("id=%d",$id)->delete();
        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }
    public function getinfo(){
        $userid = I('id');
        $user   = M('lunbo');
        $users  = $user->where("id=%d",$userid)->find(); 
        $this->assign("data",$users);
        $this->display('add');     
    }
    //文本编辑器
    public function ueditor(){
        $data = new \Org\Util\Ueditor();
        echo $data->output();
    }

}
