<?php
namespace Service;
use Payment\Common\PayException;
use Payment\Client\Charge;
/**
* 支付宝
*/
class Alipay{
	# 创建订单
	public static function create($data,$channel='ali_wap'){
		# 默认配置
		$default = [
			'amount'=>0.01,
			'timeout_express'=>(time()+1800),
			'body'=>'测试商品',
			'subject'=>'订单名称',
			'return_param'=>'buy'
		];

		# 合并配置
		$data = array_merge($default,$data);
		# 读取支付宝配置
		$config = C('Alipay');
		# 默认手机站支付
		$channel = 'ali_wap';
		# 支付的数据
		$payData = [
			# 商品信息
		    'body' => $data['body'],
		    # 订单名称
		    'subject' => $data['subject'],
		    # 商家支付订单号
		    'order_no' => $data['order_no'],
		    # 订单过期时间
		    'timeout_express' => $data['timeout_express'],
		    # 订单总额
		    'amount' => $data['amount'],
		    # 支付成功返回页面
		    'return_param' => $data['return_param'],
		    # 商品类型1=商品0=虚拟货币
		    'goods_type' => 1,
		    'store_id' => '',// 没有就不设置
		];
		try {
			# 下单
		    $payUrl = Charge::run($channel, $config, $payData);
		} catch (PayException $e) {
		    // 打印错误
		    echo($e -> errorMessage());
		    exit();
		}
		# 返回下单结果的支付url
		return $payUrl;
	}
}