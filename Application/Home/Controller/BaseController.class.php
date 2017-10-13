<?php
namespace Home\Controller;
use think\Controller;


class BaseController extends Controller{
    //推广二维码
    public function userQrcode(){
        //$id = $_SESSION['userid'];
        $id = I('id');
        $userinfo = M('user')->field("nickname,headimg,qrcode")->where('id=%d',$id)->find(); 
        jsonpReturn('1','查询成功',$userinfo);
    }
	
	public function test(){
     $id = 109;
     dump(userp($id));

	}
	#我的小伙伴
   public function partner(){
   		$id = $_SESSION['userid'];
         $img = M('user')->where('id=%d',$id)->getField('headimg');
   		#扫码会员
         list($data1,$data2,$data3,$data4,$data5) =array([],[],[],[],[],[]);
         #消费会员
         list($data11,$data22,$data33,$data44,$data55) =array([],[],[],[],[],[]);
   		$one 	= getChilden($id,1);
   		$user1  = explode(',', $one);
   		foreach ($user1 as $k => $v) {
   			$data = M('user')->field('id,headimg,phone,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i") as createtime,nickname,is_cost')->where('id=%d',$v)->find();
   			if($data['is_cost'] == 1){
   				$data11[]	= $data;
   			}else if($data['is_cost'] == 0){
               if($data){
                  $data1[] = $data;
               }
   			}
   		}
   		$two 	= getChilden($id,2);
   		$user2  = explode(',', $two);
   		foreach ($user2 as $k => $v) {
   			$data = M('user')->field('id,headimg,phone,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i") as createtime,nickname,is_cost')->where('id=%d',$v)->find();
   			if($data['is_cost'] == 1){
   				$data22[]	= $data;
   			}else if($data['is_cost'] == 0){
   				if($data){
                  $data2[] = $data;
               }
   			}
   		}
   		$three	= getChilden($id,3);
   		$user3  = explode(',', $three);
   		foreach ($user3 as $k => $v) {
   			$data = M('user')->field('id,headimg,phone,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i") as createtime,nickname,is_cost')->where('id=%d',$v)->find();
   			if($data['is_cost'] == 1){
   				$data33[]	= $data;
   			}else if($data['is_cost'] == 0){
   				if($data){
                  $data3[] = $data;
               }
   			}
   		}
   		$four 	= getChilden($id,4);
   		$user4  = explode(',', $four);
   		foreach ($user4 as $k => $v) {
   			$data = M('user')->field('id,headimg,phone,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i") as createtime,nickname,is_cost')->where('id=%d',$v)->find();
   			if($data['is_cost'] == 1){
   				$data33[]	= $data;
   			}else if($data['is_cost'] == 0){
   				if($data){
                  $data3[] = $data;
               }
   			}
   		}
   		$five 	= getChilden($id,5);
   		$user5  = explode(',', $five);
   		foreach ($user5 as $k => $v) {
   			$data = M('user')->field('id,headimg,phone,FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i") as createtime,nickname,is_cost')->where('id=%d',$v)->find();
   			if($data['is_cost'] == 1){
   				$data33[]	= $data;
   			}else if($data['is_cost'] == 0){
   				if($data){
                  $data3[] = $data;
               }
   			}
   		}
         //$sum = count($user1)+count($user2)+count($user3)+count($user4)+count($user5);
         $saoma = count($data1)+count($data2)+count($data3);
         $xiaofei = count($data11)+count($data22)+count($data33);
         $sum = $saoma+$xiaofei;
   		jsonpReturn('1','查询成功',['img'=>$img,'num'=>[$sum,$saoma,$xiaofei],'saoma'=>['one'=>$data1,'two'=>$data2,'three'=>$data3],'xiaofei'=>['one'=>$data11,'two'=>$data22,'three'=>$data33]]);
   }
   public function erweima(){
      $id = $_SESSION['userid'];
      $data = M('user')->where('id=%d',$id)->field('headimg,nickname,qrcode')->find();
      if(empty($data['qrcode'])){
         jsonpReturn('0','成功下单后才能有自己的推广二维码哦！','');
      }else{
         jsonpReturn('1','查询成功',$data);
      }  
   }

}