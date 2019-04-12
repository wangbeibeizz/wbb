<?php
namespace app\mall\controller;

use app\common\controller\Api;

/**
 * 需要登录基类
 */
class Basis extends Api
{
    public $user;
    public function initialize()
    {
        parent::initialize();
        /*获取头部信息*/
        $header = $this->request->header();
        $authToken = null;
        if (key_exists('authtoken', $header)) {
            $authToken = $header['authtoken'];
        }
        if ($authToken) {
            $authToken = explode(':', $authToken);
            $this->user = model('Store')->where("st_tel", $authToken[0])->find();
        } else {
            $this->user = model('Store')->get(1);
            // $this->e_msg("token不存在", 401);
        }
        if (empty($this->user)) {
            $this->e_msg("账号不存在", 401);
        }
        if (!cache('setting')['status']) {
            $this->e_msg("网站维护", 203);
        }
        // 密码处理
        // $pass = decrypt($this->user['us_pwd']);
        // $password = HmacMd5($pass, $pass);

        // //解密
        // if (!array_key_exists('1', $authToken)) {
        //     $this->e_msg("token不对", 401);
        // }
        // $dataStr = jsDecrypt($authToken[1], $password);
        // if (empty($dataStr)) {
        //     $this->e_msg("没有权限", 401);
        // }
        // // 解析解密后的字符串
        // $dataStr = explode(':', $dataStr);
        // if ($dataStr[0] != $_SERVER["PHP_SELF"] . $_SERVER['PATH_INFO']) {
        //     $this->e_msg("密码错误", 401);
        // }
    }
}
