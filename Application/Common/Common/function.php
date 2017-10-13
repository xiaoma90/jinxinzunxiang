<?php
function userp($id){
    $pid = M('user')->where('id=%d',$id)->getField('pid');
    if($pid==0){
        return false;
    }
    return $pid;
}
//助学金返现
function grant($order_num){
    $order  = M('order')->where('order_num=%d and status=3',$order_num)->find();
    if(empty($order)){return false;}
    $one = userp($order['uid']);
    $arr = [];
    if($one){
        $arr[] = $one;
        $two = userp($one);
        if($two){
            $arr[] = $two;
            $three = userp($two);
            if($three){
                $arr[] = $three;
                $four = userp($three);
                if($four){
                    $arr[] = $four;
                    $five = userp($four);
                    if($five){
                        $arr[] = $five;
                    }
                }
            }      
        }
    }
    if(empty($arr)){return false;} 
    $num = count($arr);
    //动态收益
    $config = M('config')->where('id=1')->find();
    $got = [$config['one']/100,$config['two']/100,$config['three']/100,$config['four']/100,$config['five']/100];
    $result = [];
    for ($i=0; $i <$num ; $i++) { 
        $acc['uid'] = $arr[$i];
        $acc['money'] = $order['money']*$got[$i];
        $acc['source'] = $order['order_num'];
        $acc['message'] = 5;
        $acc['createtime'] = time(); 
        $result[] = M('account')->add($acc);
     }
     if(count($result) == $num && !in_array('0',$result)){
        return true;
     }else{
        return false;
     }
}
// 判断是否是微信内部浏览器
function is_weixin(){ 
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }  
            return false;
} 
/*
*@param $uid  每人每天限投100单
* return 
*/
function limitFor(){
    $id = $_SESSION['userid'];
    $order   = M('order');
    //每天开始
    $start = strtotime(date('Y-m-d 0:0:0',time()));
    //每天结束
    $end = strtotime(date('Y-m-d 23:59:59',time()));
    //每天单数
    $count = $order->where('uid=%d and createtime>%d and createtime<%d',$id,(int)$start,(int)$end)->sum('num');
    $sum = 100-$count;
    if($sum<0){
        return $sum=0;
    }else{
        return $sum;
    }
}
/*
*@param $pay_order_num  支付成功订单支付pay_order_num 订单号
* return 
*/
function rebate($pay_order_num){
    $order   = M('order');
    $rebate  = M('rebate');
    $data = $order->where('order_num='.$pay_order_num)->find();
    if($data['status']!=3){
        return false;
    }
    //插入返现表
     $arr = [];
    //前5天
    for ($i=0; $i < 5 ; $i++) { 
        $arr[$i]['uid'] = $_SESSION['userid'];
        $arr[$i]['rebate'] = $data['money']*0.1;
        $arr[$i]['order_num'] = $pay_order_num;
        $arr[$i]['type']=$i+1;
        $arr[$i]['createtime']=time();
        $arr[$i]['status'] = 0;      
    }
    //后5天
    for ($i=5; $i <= 9 ; $i++) { 
        $arr[$i]['uid'] = $_SESSION['userid'];
        $arr[$i]['rebate'] = $data['money']*0.2;
        $arr[$i]['order_num'] = $pay_order_num;
        $arr[$i]['type']=$i+1;
        $arr[$i]['createtime']=time();
        $arr[$i]['status'] = 0;      
    }
    $result = $rebate->addAll($arr);
    if($result>0){
        return true;
    }else{
        return false;
    }
}
//判断自动出局
function outorder(){
    $id = $_SESSION['userid'];
    $order   = M('order');
    $rebate  = M('rebate');
    $data = $order->where('type=1 and uid='.$id)->select();
    foreach ($data as $k => $v) {
        $sum = $rebate->where('order_num='.$v['order_num'])->sum('status');
        if($sum == 10){
            if($v['type']!=2){
               $order->where('order_num ='.$v['order_num'])->save(['type'=>2]); 
            }
        }
    }
}
//自动返现
function getmoney(){
    $id = $_SESSION['userid'];
    $order   = M('order');
    $rebate  = M('rebate');
    $account = M('account');
    //获取用户所有订单
    $data = $order->where('type=1 and status=3 and uid='.$id)->select();
    $time = time();
    foreach ($data as $k => $v) {
        //循环判断10天 
        for ($i=1; $i <11 ; $i++){ 
            $data1 = $rebate->where('order_num=%d and type=%d',$v['order_num'],$i)->find(); 
            if($data1){
                $day1 = strtotime(date('Y-m-d H:i:s',strtotime("+$i day",$data1['createtime'])));//第一天领取时间
                // $day1 = (int)$data1['createtime']+60*$i;
                if($data1['status']==0){
                    if(((int)$time) > $day1){
                        $acc1['uid']        = $id;
                        $acc1['money']      = $data1['rebate'];
                        $acc1['source']     = $data1['order_num'];
                        $acc1['message']    = "4";
                        $acc1['createtime'] = $day1;
                        $result1 = $account->add($acc1);
                        if($result1>0){
                            $res1 = $rebate->where('order_num=%d and type=%d',$v['order_num'],$i)->save(['status'=>1]);
                        } 
                    }
                }
            }       
            /*$time = time();//当前时间
            $day1 = strtotime(date('Y-m-d H:i:s',strtotime("+$i day",$data1['createtime'])));//第一天领取时间
            if(((int)$time+100)>$day1 && $data1['status']==0){
                $acc1['uid']        = $id;
                $acc1['money']      = $data1['rebate'];
                $acc1['source']     = $data1['order_num'];
                $acc1['message']    = "5";
                $acc1['createtime'] = time();
                $result1 = $account->add($acc1);
                if($result1>0){
                    $res1 = $rebate->where('order_num=%d and type=1',$v['order_num'])->save(['status'=>1]);
                }     
            }    */
        } 
    }
}
/* //判断第一天
*       $data1 = $rebate->where('order_num=%d and type=1',$v['order_num'])->find();
*      $time = time();//当前时间
*     $day1 = strtotime(date('Y-m-d H:i:s',strtotime('+1 day',$data1['createtime'])));//第一天领取时间
*    if($time>$day1 && $data1['status']==0){
*       $acc1['uid']        = $id;
*      $acc1['money']      = $data1['money'];
*     $acc1['source']     = $data1['order_num']
*    $acc1['message']    = "奖学金";
*            $acc1['createtime'] = time();
*            $result1 = $account->add($acc1);
*            if($result1>0){
*                $res1 = $rebate->where('order_num=%d and type=1',$v['order_num'])->save(['status'=>1]);
*            }     
*        }
*        //判断第2天
*        $data2 = $rebate->where('order_num=%d and type=2',$v['order_num'])->find();
*        $time = time();//当前时间
*        $day2 = strtotime(date('Y-m-d H:i:s',strtotime('+2 day',$data1['createtime'])));//第一天领取时间
*        if($time>$day2 && $data1['status']==0){
*            $acc2['uid']        = $id;
*            $acc2['money']      = $data1['money'];
*            $acc2['source']     = $data1['order_num']
*            $acc2['message']    = "奖学金";
*            $acc2['createtime'] = time();
*            $result2 = $account->add($acc2);
*            if($result2>0){
*                $res2 = $rebate->where('order_num=%d and type=2',$v['order_num'])->save(['status'=>1]);
*            }     
*       }
        //判断第3天*/
/*
*@param $pay_order_num  支付成功订单支付pay_order_num 订单号
* return 
*/
function fxReturnMoney($pay_order_num){
    $order   = M('order');
    $dd['status'] = 2;
    $order->where("pay_order_num='%s'",$pay_order_num)->save($dd);  //修改订单状态
    $info   = $order->field("uid,money,type")->where("pay_order_num='%s'",$pay_order_num)->find();
    $userid = $info['uid']; //用户id
    $money  = $info['money'];   //金额
    $type   = $info['type'];    //购买类型(2:学霸,3:讲师,4:合伙人,,5:购买权限)
    $use['is_cost'] = 1;    //首次消费
    $use['id']      = $userid;
    if ($type != 5) {
        $use['grade'] = $type;
    }else if($type ==2){
          $use['xbtime'] = time();
    }else{
       //购买权限
        $dd1['user_id'] = $userid;
        $dd1['add_time'] = time();
        $results= M('uservip')->add($dd1); //购买权限卡写入表中        
    }
    $rr = M('user')->save($use);    //购买用户等级修改状态
    //分销
    //获取分销规则
    $config = M('config')->field("student,scholar,lecturer,partner,name")->select();            
    // ----获取用户的上三级
    $one = M('user')->where('id=%d',$userid)->getField("pid");
    $two = M('user')->where('id=%d',$one)->getField("pid");
    $three = M('user')->where('id=%d',$two)->getField("pid");
    $user[] = $one;
    $user[] = $two;
    $user[] = $three;

    foreach($user as $k=>$v){
        if (!$v) {
            $v = -1; 
        }
        $grade = M('user')->where('id=%d',$v)->getField("grade");
        switch ($grade){
            case 1 :
            $level = 'student';
            break;
            case 2 :
            $level = 'scholar';
            break;
            case 3 :
            $level = 'lecturer';
            break;
            case 4 :
            $level = 'partner';
            break;
        }
        $return[$k]['id'] =$v['id']; 
        $return[$k]['level'] = $config[$k][$level];
    }
    foreach($return as $v){
        if ($v['id'] == null || $v['id'] == '' || $v['id']==0 ) {
            break;
        }
        if(is_numeric($v['level'])){    //判断是否为学童,返积分
            $da['uid'] = $v['id'];
            $da['source'] = $userid;
            $da['score']  = $v['level'];
            $da['message']   = '分佣积分';
            $da['createtime']= time();
            M('score')->add($da);
        }else{  
            $daa['money'] = $money * ((int)$v['level'] / 100);
            $daa['uid']   = $v['id'];
            $daa['source']= $userid;
            $daa['message']= '分佣金额';
            $daa['createtime']= time();
            M('account')->add($daa);
        }
    }
    echo success;
}
function ObjectToArray($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = ObjectToArray($value);  
             }  
     }  
     return $array;  
}

/*
 * 获取指定级别后的所有用户
 * @param $uid char 要查询下级的用户id
 * @param $num int   要查的级别后
 * @return 查询指定级别后的用户下级
 */

function getThreeEnd($uid,$n = ''){
    static $user = [];
    if($n){
        $threeChilden = '';
        for($i = 1;$i <= $n;$i++){
            $threeChilden .= getChilden($uid,$i).',';
        }
        $threeChilden = explode(',',trim($threeChilden,','));
    }
    if(!in_array($uid, $user)) {
        array_push($user, $uid);
    }
    $where['pid'] = ['in',"$uid"];
    $userChilden = M('user')->field('id')->where($where)->select();
    $userChilden = array_column($userChilden, 'id');
    $user = array_unique(array_merge($user, $userChilden));
            // dump($user);exit;
    foreach($userChilden as $user_id) {
        getThreeEnd($user_id);
    }
    $threeChildenEnd = array_diff($user,$threeChilden);
    array_shift($threeChildenEnd);
    return $threeChildenEnd;
}

/*
 * 获取指定级别下级
 * @param $uid char 要查询下级的用户集合id；如'1,2,3'
 * @param $num int   要查询的级别
 * @return 查询级别的用户下级
 */

function getChilden($uid,$num = 1){
    $where['pid'] = ['IN',"$uid"];
    $user1 = M('user')->where($where)->select();
    $users_id = '';
    foreach($user1 as $k=>$v){
        $users_id .= $v['id'].',';
    }
    $users_id = trim($users_id,',');    //一级下级
    for($i = 1;$i < $num;$i++){
        $users_id = getChilden($users_id,$num-1);
        return $users_id;
    }
    return $users_id;

}

// 公用函数库
/* 
 * 模拟提交参数，支持https提交 可用于各类api请求 
 * @param string $url ： 提交的地址 
 * @param array $data :POST数组 
 * @param string $method : POST/GET，默认GET方式 
 * @return mixed 
 */

function http($url, $data='', $method='GET'){   
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');  
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);  
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
    $result = curl_exec($ch);  
    if (curl_errno($ch)) {  
        exit(curl_error($ch));  
    }  
    curl_close($ch);
    // 返回结果集
    return $result;
}
/*发送短信验证码
auth:mpc
$mobile:手机号
$code :验证码
*/

function NewSms($Mobile){
      $str = "1234567890123456789012345678901234567890";
      $str = str_shuffle($str);
      $code= substr($str,3,6);
    $data = "username=%s&password=%s&mobile=%s&content=%s";
    $url="http://120.55.248.18/smsSend.do?";
    $name = "JXZX";
    $pwd  = md5("aX5uB7eA");
    $pass =md5($name.$pwd);
    $to   =  $Mobile;
    $content = "您的验证码是：".$code."，切勿将验证码泄露于他人。【金鑫尊享】";
    $content = urlencode($content);
    $rdata = sprintf($data, $name, $pass, $to, $content);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$rdata);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $result = curl_exec($ch);
    curl_close($ch);
    return ['code' => $code, 'data' => $result, 'msg' => ''];
}



/*

* 随机生成订单号

*/

function orderNum(){
    $num = date('Y').date('m').time().rand(1,100);
    $res = M('order')->field("count(1) as num")->where("order_num='%d'",$num)->select();
    if ($res[0]['num']) {
        orderNum();
    }else{
        return $num;
    }
}