<?php
namespace Home\Controller;

use think\Controller;
use Service\Wechat;


/**
* 前台中间件
*/
class AbaseController extends Controller{

	// function __construct(){
	// 	if($_SESSION['userid'] < 1){
	// 		if(is_weixin()){
	// 			Wechat::get_user_info('http://www.jinxinenjoy.com/Home/Login/wechat_login.html');
	// 			//$_SESSION['type'] = 1;
	// 			// $this -> jsonpReturn(['status'=>2000,'url'=>Wechat::get_user_info('http://'.$_SERVER['HTTP_HOST'].'/Home/Wechat/wechat_login.html')]);
	// 		}else{
	// 			header('location:http://wap.jinxinenjoy.com/lf_login.html?pid=0&id=0');exit;
	// 		}
	// 	}else if($_SESSION['user']['phone']==''){
	// 		# 需要绑定手机号
	// 		header('location:http://wap.jinxinenjoy.com/lf_login.html?pid='.$_SESSION['user']['pid'].'&id='.$_SESSION['userid']);exit;#？
	// 	}
	// 	# 调用父类的构造方法
 //    	parent::__construct();
	// }
	
}