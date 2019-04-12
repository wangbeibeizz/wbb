<?php
namespace app\shop\controller;

use app\common\controller\Api;

/**
 * 需要登录基类
 */
class Basis extends Api {
	public $shop;
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
				$this->e_msg("请登录", 203);
			}
			$authToken = explode(':', $authToken);
			$this->shop = model('Store')
			->alias('a')
			->join('admin b', 'a.ad_id = b.id', 'LEFT')
			->join('user c', 'a.us_id = c.id', 'LEFT')
			->field('a.*, b.ad_pwd, b.status,c.us_pwd')
			->where("b.ad_tel", $authToken[0])
			->find();
		} else {
			//return $this->shop = model('Store')->get(1);
			$this->e_msg("请登录", 401);
		}
		if (empty($this->shop)) {
			// $this->e_msg(['msg' => '']);
			$this->e_msg("用户不存在", 401);
		}
		if($this->shop['area_id'] !== 1){
			$this->e_msg("登录入口错误",203);
		}
		if($this->shop['status'] == 0){
			$this->e_msg("此账号被禁用",203);
		}
		if (!cache('setting')['status']) {
			$this->e_msg("网站维护", 203);
		}
		//密码处理
		$pass = decrypt($this->shop['ad_pwd']);
		$password = HmacMd5($pass, $pass);

		//解密
		if (!array_key_exists('1', $authToken)) {
		    $this->e_msg("token不对", 401);
		}
		$dataStr = jsDecrypt($authToken[1], $password);
		if (empty($dataStr)) {
		    $this->e_msg("没有权限", 401);
		}
		// 解析解密后的字符串
		$dataStr = explode(':', $dataStr);
		//dump($dataStr[0]);
		//halt($_SERVER);
		if ($dataStr[0] != $_SERVER['PATH_INFO']) {

		    $this->e_msg("密码错误", 401);
		}

	}
}
