<?php
namespace app\index\controller;
use EasyWeChat\Factory;
use think\Controller;
use think\facade\Config;
use think\Log;

/**
 *
 */
class Wecpay extends Controller {
	protected $config;

	function __construct() {
		parent::__construct();
		$path = env('ROOT_PATH') . "public\\wecpay\\cert\\";
		$wechant_numb = Config::get('wechat_numb');
		$this->config = [
			// 必要配置
			'app_id' => 'wxfaf018dc16b98e1a',
			'mch_id' => '1494664132',
			'key' => 'voCrtk37h63SBW1dLs0pPmqbr138fadq', // API 密钥

			// 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
			// 'cert_path' => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
			// 'key_path' => 'path/to/your/key', // XXX: 绝对路径！！！！
			'cert_path' => $path . 'apiclient_cert.pem',
			'key_path' => $path . 'apiclient_key.pem',
			'notify_url' => 'http://ywx.jugekeji.com/index/wecpay/notify', // 你也可以在下单时单独设置来想覆盖它
		];

	}
	public function index() {
		$ap = Factory::payment($this->config);

		$result = $ap->order->unify([
			'body' => '订单',
			'out_trade_no' => date('YmdHis') . rand(0000, 9999),
			'total_fee' => 1,
			'trade_type' => 'JSAPI',
			'openid' => 'oYCdr1mO0tFK2e-xN85V4ugbnnbU',
		]);
		halt($result);
	}

	public function notify() {
		Log::write('订单支付失败');
	}
}