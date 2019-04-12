<?php
namespace app\index\controller;

use app\common\controller\Api;

/**
 * 需要登录基类
 */
class Basis extends Api {
	public $user;
	public function initialize() {
		parent::initialize();
		/*获取头部信息*/
		$header = $this->request->header();
		$authToken = null;
		if (key_exists('authtoken', $header)) {
			$authToken = $header['authtoken'];
		}
		if ($authToken) {
			if($authToken == 'null'){
				$this->e_msg("请登录");
			}
			// halt($authToken);
			$authToken = explode(':', $authToken);
			$this->user = model('User')
			->where("us_nickname", $authToken[0]) //aututoken[0]为传过来的账号，aututoken[1]为传过来的密码，
			->find();
		}
		else {
			//return $this->user = model('User')->get(72);
			$this->e_msg("请登录");
		}
		if (empty($this->user)) {
			// $this->e_msg(['msg' => '']);
			$this->e_msg("用户名错误");
		}
		if($this->user['us_type'] == 0){
			$this->e_msg("此账号被冻结");
		}
		// if (!cache('setting')['status']) {
		// 	$this->e_msg("网站维护");
		// }
		//密码处理
		$password = $authToken[1];
		// $ps=encrypt(123456);
		// halt($ps);
		// if ($this->user['us_pwd'] !=encrypt($password)) {
		// 	$this->e_msg("密码错误");
		// }
		// if ($password==123456) {
		// 	$this->e_msg("密码错误");
		// }
		// $dataStr = jsDecrypt($authToken[1],);
		// $dataStr = explode(':', $dataStr);
		// $this->s_msg($dataStr);
		$pass = decrypt($this->user['us_pwd']);
		// $pass = decrypt($ps);
		$password = HmacMd5($pass, $pass);

		// //解密
		if (!array_key_exists('1', $authToken)) {
		    $this->e_msg("token不对", 401);
		}
		$dataStr = jsDecrypt($authToken[1], $password);
		if (empty($dataStr)) {
		    $this->e_msg("没有权限", 401);
		}
		// // 解析解密后的字符串
		$dataStr = explode(':', $dataStr);
		// dump( $_SERVER['PATH_INFO']);
		// halt($dataStr[0]);

		if ($dataStr[0] != $_SERVER['PATH_INFO']) {
			// dump($dataStr[0]);
			// dump($_SERVER['PATH_INFO']);
		    $this->e_msg("密码错误", 401); 
		}

	}
}
