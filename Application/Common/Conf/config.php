<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE'   =>  'Admin',    // 默认模快
    'DEFAULT_CONTROLLER'   =>   'Login',
    'DEFAULT_ACTION'       =>   'login',
	/* 数据库设置 */
    'DB_TYPE'          =>  'mysql',      // 数据库类型
    'DB_HOST'          =>  'localhost',  // 服务器地址
    'DB_NAME'          =>  'jinxin',      // 数据库名
    'DB_USER'          =>  'jinxin',       // 用户名
    'DB_PWD'           =>  'jinxin2017',       // 密码
    'DB_PORT'          =>  '3306',       // 端口
    'DB_PREFIX'        =>  'jxzx_',        // 数据库表前缀
    'SHOW_PAGE_TRACE'  =>  false,        //开启ThinkPHP页面调试
  	'URL_MODEL'        =>  2, 
    'VIPPRICE'         =>  100, 
     //微信支付配置
    "Wechat"           =>   [
        # 微信的appid
                                'appid'=>'wx30f4a69764a017c2',#
                                # 公众号的secret
                                'secret'=>'3d87b78487deaaf099a0e092b069c499',#
                              /*  # 登录操作函数回调链接
                                'callback'=>'http://www.jinxinenjoy.com/Home/User/wechat_login.html',
                                # 授权成功的回调链接
                                'login_success_callback'=>'http://www.jinxinenjoy.com',
                                # 微信支付key
                                'pay_key'=>'t9fc9qT18ebe5edOF1WDzZk7al7BaDKVKoj3PQfTgA2',
                                # 商户id
                                'mchid' => '1458211702',#
                                #通知回调地址
                                'notify_url'=>'http://www.jinxinenjoy.com/Admin/Wechat/notify.html',*/
                                #token定义
                                'TOKEN'=>"jinxin",#
    ],
   
);
