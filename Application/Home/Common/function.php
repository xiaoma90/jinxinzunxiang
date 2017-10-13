<?php
/*

 * 生成订单二维码

 * @param int $id 订单id

 * @param $level 容错等级

 * @param $size 图片大小

 * @return

 */

function qrcode($id,$level=3,$size=4){

   Vendor('phpqrcode.phpqrcode');

   $name   = 'qrcode'.$id;

   $path = "./Uploads/qrcode/$name.png";

   $errorCorrectionLevel =intval($level) ;//容错级别

   $matrixPointSize = intval($size);//生成图片大小

   //生成二维码图片

   $object = new \QRcode();

   $url = 'http://www.jinxinenjoy.com/Home/Wechat/code.html?pid='.$id;

   // $url = 'http://www.baidu.com';

   $img = $object->png($url,$path, $errorCorrectionLevel, $matrixPointSize, 2,false);

   //die('2');

}


/*

 * Jsonp方式返回数据到客户端

 * @param mixed $data 要返回的数据

 * @author jcl

 * @return array

 */

function jsonpReturn($status='',$msg='',$data=array()) {

	$data = array("status"=>$status,"msg"=>$msg,"data"=>$data);

    if(empty($type)) $type  =   'jsonp';

            // 返回JSON数据格式到客户端 包含状态信息

            header('Content-Type:application/json; charset=utf-8');

            $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');

            exit($handler.'('.json_encode($data,$json_option).');');

}





//获取所有的下级

//$id:主id  $level:查询级别

//author:jcl

function getLevelUser($id,$level=3){

	$user = M('user');

	$allid= $user->field("id")->where("pid=%d",$id)->select();

	if (empty($allid) || $allid == false) {

		return false;

	}

	$one = array();

	foreach ($allid as $key => $value) {

		$one[] = $value['id'];

	}

	if ($level ==1) {

		return $one;

	}else if($level ==2){



	    $where['pid'] = array("in",$one);

	    $allid = $user->field("id")->where($where)->select();

	    if (empty($allid) || $allid ==false) {

	    	return $one;

	    }

	    $two = [];

	    foreach ($allid as $k => $v) {

	    	$two[] = $v['id'];

	    }

	    return array_merge($one,$two);

	}else if($level ==3){



	    $where['pid'] = array("in",$one);

	    $allid = $user->field("id")->where($where)->select();

	    if (empty($allid) || $allid ==false) {

	    	return $one;

	    }

	    $three = array();

	    foreach ($allid as $k => $v) {

	    	$three[] = $v['id'];

	    }

		$where1['pid'] = array("in",$three);

		$allid = $user->field("id")->where($where1)->select();

	    if (empty($allid) || $allid ==false) {

	    	$three = array_merge($one,$three);

	    	return $three;

	    }

	    $three1 = [];

	    foreach ($allid as $k => $v) {

	    	$three1[] = $v['id'];

	    }

	    return array_merge($one,$three,$three1);

	}

}

