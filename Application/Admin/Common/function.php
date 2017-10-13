<?php



/**

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