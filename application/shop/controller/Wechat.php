<?php
namespace app\index\Controller;

use EasyWeChat\Factory;
use think\facade\Config;

/**
 *微信公众号
 */
class Wechat {
	protected $app;
	public function __construct() {
		$bb = env('ROOT_PATH') . "public\\tmp\\wechat\\" . date('Ymd') . ".log";
		check_path(dirname($bb));
		$wechant_numb = Config::get('wechat_numb');
		$options = [
			'app_id' => $wechant_numb['appid'],
			'secret' => $wechant_numb['appsecret'],
			'token' => $wechant_numb['token'],
			'aes_key' => $wechant_numb['encodingaeskey'],
			'response_type' => 'array',
			'log' => [
				'level' => 'debug',
				'permission' => 0777,
				'file' => $bb,
			],
			'oauth' => [
				'scopes' => ['snsapi_userinfo'],
				'callback' => url('index/wechat/showshow'),
			],
			// ...
		];
		if (!$this->app) {
			$this->app = Factory::officialAccount($options);
		}
	}
	public function index() {
		// $this->app->server->push(function ($message) {
		// 	return "您好！谢谢关注";
		// });
		// $response = $this->app->server->serve();
		$this->app->server->push(function ($message) {

			switch ($message['MsgType']) {
			case 'event':
				return '谢谢您的👍哦！我们会做的更好的';
				break;
			case 'text':
				if ($message['Content'] == 1) {
					return '<a href="http://www.baidu.com" style="color:red;">百度一下</a>';
				}
				return '客户将根据实际意义做出回复';
				break;
			case 'image':
				return '我们会看到您给的图片的';
				break;
			case 'voice':
				return '我们会听一下您的声音';
				break;
			case 'video':
				return '这是个好视频 谢谢';
				break;
			case 'location':
				return '这是个好地方';
				break;
			case 'link':
				return '这个链接可是好东西,谢谢分享';
				break;
			case 'file':
				return '不敢打开看';
			// ... 其它消息
			default:
				return '没明白您发的是什么';
				break;
			}

			// ...
		});
		$response = $this->app->server->serve();
		// 将响应输出
		$response->send();

	}
	public function news() {
		$aa = $this->app->broadcasting->sendText("欢迎关注", ['oYCdr1mO0tFK2e-xN85V4ugbnnbU', 'oYCdr1uSCg90SQjMWOpdFJIHb0b0']);
		halt($aa);
	}
	//菜单
	public function menu() {
		$buttons = [
			[
				"type" => "view",
				"name" => "商城首页",
				"url" => "http://www.baidu.com/",
			],
			[
				"type" => "view",
				"name" => "门店列表",
				"url" => "http://www.baidu.com/",
			],
			[
				"name" => "我的",
				"sub_button" => [
					[
						"type" => "view",
						"name" => "订单列表",
						"url" => "http://www.baidu.com/",
					],
					[
						"type" => "view",
						"name" => "财务记录",
						"url" => "http://www.baidu.com/",
					],
					[
						"type" => "view",
						"name" => "分享链接",
						"url" => "http://v.qq.com/",
					],
					[
						"type" => "click",
						"name" => "赞一下我们",
						"key" => "V1001_GOOD",
					],
				],
			],
		];
		$this->app->menu->create($buttons);
	}

	// public function getopenid() {
	// $this->app = Factory::officialAccount($options);
	// $users = $this->app->user->list();
	// halt($users);
	// }

	//拉取用户授权
	public function showquan() {
		$response = $this->app->oauth->redirect()->send();
		return $response;
	}
	public function showshow() {
		$user = $this->app->oauth->user();
		$openid = $user->token->openid;
		halt($openid);
		$aa = encrypt($openid);
		$info = model("User")->where('us_openid', $aa)->find();
		if (isset($info)) {
			$pass = decrypt($info['us_pwd']);
			$aa = HmacMd5($pass, $pass);
			$info['dou'] = $aa;
			return [
				'code' => 1,
				'data' => $info,
			];
		}
		return [
			'code' => 0,
			'data' => $aa,
		];

	}
}
