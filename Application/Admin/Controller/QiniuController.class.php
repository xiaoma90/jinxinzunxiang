<?php
namespace Admin\Controller;

use Think\Controller;
use Service\Upload;
class QiniuController extends Controller{
	public function index(){
            // dump($_FILES);die();
        $upload =new \Service\Upload();
        $res = $upload ->upload_one($_FILES['fmvid']);
        $this -> ajaxReturn(['status'=>1,'url'=>$res]);
	}
	

}