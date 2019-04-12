<?php
namespace app\shop\controller;

use think\facade\Config;
use Cache;

/**
 * 图片验证码
 */
class Every extends Common {

	public function initialize() {
		parent::initialize();
	}
	/**
	 * 上传图片
	 * @return [type] [description]
	 */
	public function sctp() {
		try {
			$rel = base64_upload(input('post.img'));
		} catch (\Exception $e) {
			$this->e_msg($e->getMessage());
		}
		if ($rel) {
			$this->s_msg('上传成功', $rel);
		} else {
			$this->e_msg();
		}
	}
	protected function object_to_array($obj) {
		$obj = (array) $obj;
		foreach ($obj as $k => $v) {
			if (gettype($v) == 'resource') {
				return;
			}
		}

		return $obj;
	}


	//注册
    public function reg()
    {
        if (is_post()) {
            $post=input('post.');
            $user=model('User');
            $use=$user->where('us_tel',$post['recommend'])->find();
            $path=$use['us_path'].','.$use['id'];
            if (!$post['recommend']) {
                $this->e_msg('请输入推荐人信息！');
            }
            $tjp=db('user')->where('us_tel',$post['recommend'])->find();
            if (!$tjp) {
            	$this->e_msg('推荐人不存在');
            }
            $code_info = cache($post['us_tel'] . 'code') ?: "";
			if (!$code_info) {
				$this->e_msg('请重新发送验证码');
			} elseif (trim(input('post.code')) != $code_info) {
				$this->e_msg('验证码不正确');
			}
			// dump($code_info);
			unset($post['code']);
            // if ($post['us_pwd']!=$post['us_pwd1']) {
            //     $this->e_msg('输入两次密码不一致！');
            // }
            $post['us_pwd']=encrypt($post['us_pwd']);
            $list = array(
                'us_pid'        => $use['id'],
                'us_tel'        => $post['us_tel'],
                'us_nickname'   => $post['nickname'],
                'us_pwd'        => $post['us_pwd'],
                'us_path'       => $path,
                'us_long'       => $use['us_long']+1,
                'us_add_time'	=> date('y-m-d h:i:s'),
            );
            $pd=$user->insert($list);
            if ($pd) {+
                $this->s_msg('注册成功！');
            }
            else{
                $this->e_msg('请稍后重试！');
            }
        }
    }
    //修改密码 手机号us_tel 验证码code 密码us_pwd,确认密码us_pwd1
    public function forgetpwd()
    {
    	$data=input('post.');
    	$info = model('User')->detail(['us_tel' => $data['us_tel']]);
			if (!$data['us_tel'] || !$info) {
				$this->e_msg('该手机号不存在');
			}
    	$code_info = cache($data['us_tel'] . 'code') ?: "";
		if (!$code_info) {
			$this->e_msg('请重新发送验证码');
		} elseif (trim(input('post.code')) != $code_info) {
			$this->e_msg('验证码不正确');
		}
		unset($data['code']);
		//

		$data['us_pwd']=encrypt($data['us_pwd']);
		$xiugai=model('User')->where('us_tel',$data['us_tel'])->update($data);
		if (!$xiugai) {
			$this->e_msg('修改失败！');
		}
		$this->s_msg('修改成功');
    }


	//获取验证码   获得手机号us_tel
	public function send() {
		$mobile = input('post.us_tel');
		if (cache($mobile . 'code')) {
			//halt(cache($mobile . 'code'));
			$this->e_msg('每次发送间隔120秒');
		}
		$random = mt_rand(100000, 999999);
		// dump($random);
		$rel = $this->note_code($mobile, $random);//手机号，验证码
		if ($rel['returnstatus'] == "Faild") {
			$this->e_msg($rel['message']);
		} else {
			cache($mobile . 'code', $random,120);
			$this->s_msg($random);
		}
	}

	public function register() {
		$data = input('post.');
		$code_info = cache($data['ad_tel'] . 'code') ?: "";
		if (!$code_info) {
			$this->e_msg('请重新发送验证码');
		} elseif (trim(input('post.code')) != $code_info) {
			$this->e_msg('验证码不正确');
		}
		unset($data['code']);
		$info = model('Admin')->detail(['ad_tel' => $data['ad_tel']]);
		$rel = model('Admin')->xiugai($data,['id' => $info['id']]);
		if ($rel) {
			$this->result('1', 1, '修改成功', 'json');
		} else {
			$this->result('1', 2, '修改失败,可能您的密码没有做出修改', 'json');
		}
	}

	public function forget() {
		if (is_post()) {
			$data = input('post.');
			//手机号
			$info = model('Admin')->detail(['ad_tel' => $data['ad_tel']]);
			if (!$data['ad_tel'] || !$info) {
				$this->e_msg('该手机号不存在');
			}
			$code_info = cache($data['ad_tel'] . 'code') ?: "";
			if (!$code_info) {
				$this->e_msg('请重新发送验证码');
			} elseif (trim(input('post.code')) != $code_info) {
				$this->e_msg('验证码不正确');
			}
			unset($data['code']);
			/*$validate = validate('Verify');
			if (!$validate->scene('forgetUser')->check($data)) {
				$this->e_msg($validate->getError());
			}
			$data['us_pwd'] = encrypt($data['us_pwd']);
			$rel = model('User')->xiugai($data, ['id' => $info['id']]);*/
			$rel = model('Admin')->xiugai($data,['id' => $info['id']]);
			if ($rel) {
				$this->result('1', 1, '修改成功', 'json');
			} else {
				$this->result('1', 2, '修改失败,可能您的密码没有做出修改', 'json');
			}
		}
	}
	public function ceshi() {
		$pass = 123456;
		$pass1 = encrypt($pass);
		$pass2 = decrypt($pass1);
		dump($pass1);
		dump($pass2);
	}
	public function token() {
		$pass = 123;
		$url = "user/login";
		$tel = '18739912538';
		$pass1 = HmacMd5($pass, $pass);
		dump($pass1);
		$str = jsEncode($url, $pass1);
		dump($str);
		$this->result($tel . ':' . $str, 1, "获取成功", "json");
	}

	private function note_code($mobile, $content) {
		header('Content-Type:text/html;charset=utf8');
		$sms = Config::get('sms');
		$sms['password'] = ucfirst(md5($sms['password']));
		//$sms['content'] = $sms['content'].$content;
		$sms['content'] = $sms['content'].$content.$sms['contentlast'];
		// $sms['content'] = urlencode($content);
		// $sms['content'] = urlencode($sms['content']);
		$sms['mobile'] = $mobile;
		$query_str = http_build_query($sms);
		$gateway = "http://114.113.154.5/sms.aspx?action=send&" . $query_str;
		// $gateway = "http://114.113.154.5/sms.aspx?action=send&userid={$userid}&account={$account}&password={$password}&mobile={$mobile}&content={$content}&sendTime=";
		// $gateway = "http://114.113.154.5/sms.aspx?action=send&userid={$sms['userid']}&account={$sms['account']}&password={$sms['password']}&mobile={$mobile}&content={$sms['content']}&sendTime=";
		$url = preg_replace("/ /", "%20", $gateway);
		$result = file_get_contents($url);
		$xml = simplexml_load_string($result);
		return $this->object_array($xml);
	}

	public function quanguo()
	{
		upday();
	}
	/*private function note_code($mobile, $content) {
		header('Content-Type:text/html;charset=utf8');
		$sms = Config::get('sms');
		$sms['password'] = ucfirst(md5($sms['password']));
		$sms['content'] .= $content;
		$sms['content'] = urlencode($sms['content']);
		$sms['mobile'] = $mobile;
		$query_str = http_build_query($sms);
		$gateway = "http://114.113.154.5/sms.aspx?action=send&" . $query_str;
		dump($gateway);
		$gateway = "http://114.113.154.5/sms.aspx?action=send&userid={$sms['userid']}&account={$sms['account']}&password={$sms['password']}&mobile={$mobile}&content={$sms['content']}&sendTime=";
		dump($gateway);
		$url = preg_replace("/ /", "%20", $gateway);
		$result = file_get_contents($url);
		$xml = simplexml_load_string($result);
		return $this->object_array($xml);

	}*/
}
