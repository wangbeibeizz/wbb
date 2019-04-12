<?php
namespace app\index\controller;
use app\common\controller\Api;
// use think\Controller;
use app\index\controller\User;

/**
 * 微信支付
 */
//class Wecpay extends Basis {
class Wecpay extends Api{
	function __construct() {
		parent::__construct();
		// $path = env('APP_PATH') . "public\\wecpay\\";
		require_once "wecpay/lib/WxPay.Api.php";
		require_once "wecpay/lib/WxPay.Notify.php";
		require_once "wecpay/example/WxPay.JsApiPay.php";
		require_once "wecpay/example/WxPay.NativePay.php";
		require_once 'wecpay/example/log.php';
	}
	//网页
	public function index($data) {
		//①、获取用户openid
		$tools = new \JsApiPay();
		//$openId = $tools->GetOpenid();
		//$openId = 'oZLRBw1QFN8y_3zeOMjZSU3OspPA';
		//$openId = $this->user['us_openid'];
		$openId = $data['openid'];
		//$jine = input('jine');
		$jine = $data['money'];
		$or_id = $data['or_id'];
		$type = $data['type'];		
		//添加微信支付记录
		//halt($data);
		$add_pay = model("PayWechat")->tianjia($data['us_id'], $jine, $type, $or_id);
		// jine 金额
		// order_id
		//model("PayWechat")->tianjia($this->user['id'], $jine, $order_id, $wechat_number);
		$num = $jine * 100;
		$input = new \WxPayUnifiedOrder();
		$input->SetBody("order");
		$input->SetAttach($type);
		$input->SetOut_trade_no($or_id);
		$input->SetTotal_fee('1');
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://czh.jugekeji.com/index/wenotify/notify");
		// $input->SetNotify_url("http://czh.jugekeji.com/index/wechat/notify/number/" . $wechat_number);
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = \WxPayApi::unifiedOrder($input);
		// halt($order);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		//halt($jsApiParameters);
		$this->s_msg(null, $jsApiParameters);
		// $this->assign(array(
		// 	// 'order' => $order,
		// 	'jsApiParameters' => $jsApiParameters,
		// ));
		// return $this->fetch();
	}
	// 扫码
	public function native() {
		$notify = new \NativePay();
		$url1 = $notify->GetPrePayUrl("123456789");
		//模式二
		/**
		 * 流程：
		 * 1、调用统一下单，取得code_url，生成二维码
		 * 2、用户扫描二维码，进行支付
		 * 3、支付完成之后，微信服务器会通知支付成功
		 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
		 */

		$jine = input('jine');
		$order_id = input('order_id') ?: 0;
		$wechat_number = 'ywx' . date('YmdHis') . rand(100, 999);
		if ($order_id == 0) {
			if ($jine < 100 || $jine % 100 != 0) {
				$this->e_msg('金额必须大于100且为100的倍数');
			}
		}
		model("PayWechat")->tianjia($this->user['id'], $jine, $order_id, $wechat_number);
		$num = $jine * 100;
		$input = new \WxPayUnifiedOrder();
		$input->SetBody("test");
		$input->SetAttach("test");
		$input->SetOut_trade_no(\WxPayConfig::MCHID . date("YmdHis"));
		$input->SetTotal_fee('1');
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://ywx.jugekeji.com/index/wechat/notify/number/" . $wechat_number);
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id("123456789");
		$result = $notify->GetPayUrl($input);
		$url2 = $result["code_url"];
		$this->s_msg(null, $url2);
		// $this->assign(array(
		// 	'url1' => $url1,
		// 	'url2' => $url2,
		// ));
		// return $this->fetch();
	}
}