<?php
namespace app\index\controller;

use think\facade\Config;
use app\admin\logic\UserLogic;
use app\index\controller\Wecpay;
use think\Container;

/**
 * 图片验证码
 */
class Every extends Common {
	protected $wechant_numb;
	public function initialize() {
		parent::initialize();
		$this->wechant_numb = Config::get('wechat_numb');
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
	/**
	 * 86400 / 24 3600/60    120 两分钟
	 * 验证码
	 * @return [type] [description]
	 */
	/*public function send() {
		$mobile = input('post.ad_tel');
		if (cache($mobile . 'code')) {
			//halt(cache($mobile . 'code'));
			$this->e_msg('每次发送间隔120秒');
		}
		$random = mt_rand(100000, 999999);
		$rel = $this->note_code($mobile, $random);
		if ($rel['returnstatus'] == "Faild") {
			$this->e_msg($rel['message']);
		} else {
			cache($mobile . 'code', $random,120);
			$this->s_msg('发送成功');
		}
	}*/
	//vip注册时先支付，支付完成后调用注册接口
	//2018年6月25日 18:40:45
	//fkl
	public function vipPay(){
		$topay = new Wecpay;
        $pay_msg['or_id'] = 0;
        $pay_msg['money'] = 300;
        $pay_msg['openid'] = session('openid');
        $pay_rel = $topay->index($pay_msg);
	}	
	//扫描二维码，通过id获取推荐人手机号
	//2018年6月26日 15:57:26
	//fkl
	public function getTelById(){
		$id = input('post.us_id');
		$ptel = model('User')->where('id',$id)->value('us_tel');
		$this->s_msg(null,$ptel);
	}
	//注册
	public function register() {
		$data = input('post.');
		//halt($data);
		$code_info = cache($data['us_tel'] . 'code') ?: "";
		if (!$code_info) {
			$this->e_msg('请重新发送验证码');
		} elseif (trim(input('post.code')) != $code_info) {
			$this->e_msg('验证码不正确');
		}
		unset($data['code']);
		$data['us_shop_quan'] = 100;
		$userlogic = new UserLogic;
		//vip注册
		if(array_key_exists('us_level', $data)){
			$validate = validate('Verify');
	        $rst = $validate->scene('addTake')->check($data);
	        if (!$rst) {
	        	$this->e_msg($validate->getError());
	        }
	        $data['us_level'] = 0;

	        /*$data['us_shop_quan'] = 600;
	        $data['gave_status'] = 1;
	        $rell['sg'] = '赠送600消费券';*/   

	        $rel = $userlogic->addUser($data);
	        if($rel['code'] == 0){
	        	$this->e_msg($rel['msg']);
	        }
	        //如果是vip注册，支付300元
			$topay = new Wecpay;
			$pay_msg['us_id'] = $rel['us_id'];
	        $pay_msg['or_id'] = 'yxg' . date('YmdHis') . rand(100, 999);
	        $pay_msg['type'] = 2;
	        $pay_msg['money'] = 300;
	        $pay_msg['openid'] = session('openid');
	        $pay_rel = $topay->index($pay_msg);        
		}		
		//添加用户		
		$rel = $userlogic->addUser($data);
		if(!$rel){
			$this->e_msg('注册失败');
		}
		$this->s_msg(null,$rel);
	}
	public function forget() {
		if (is_post()) {
			$data = input('post.');
			//手机号
			$info = model('User')->detail(['us_tel' => $data['us_tel']]);
			if (!$data['us_tel'] || !$info) {
				$this->e_msg('该账号不存在');
			}
			$code_info = cache($data['us_tel'] . 'code') ?: "";
			if (!$code_info) {
				$this->e_msg('请重新发送验证码');
			} elseif (trim(input('post.code')) != $code_info) {
				$this->e_msg('验证码不正确');
			}
			unset($data['code']);
			$validate = validate('Verify');
			if (!$validate->scene('forgetUser')->check($data)) {
				$this->e_msg($validate->getError());
			}
			$data['us_pwd'] = encrypt($data['us_pwd']);
			$rel = model('User')->editInfo($data, ['id' => $info['id']]);
			if ($rel) {
				$this->result('1', 1, '修改成功', 'json');
			} else {
				$this->result('1', 2, '修改失败,可能您的密码没有做出修改', 'json');
			}
		}
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

	/*private function note_code($mobile, $content) {
		header('Content-Type:text/html;charset=utf8');
		$sms = Config::get('sms');
		$contentlast = $sms['contentlast'];
		$sms['password'] = ucfirst(md5($sms['password']));
		$sms['content'] .= $content;		
 		$sms['content'] .= $contentlast;
		//$sms['content'] = urlencode($content);
		$query_str = http_build_query($sms);
		$gateway = "http://114.113.154.5/sms.aspx?action=send&" . $query_str;
		// $gateway = "http://114.113.154.5/sms.aspx?action=send&userid={$userid}&account={$account}&password={$password}&mobile={$mobile}&content={$content}&sendTime=";
		$url = preg_replace("/ /", "%20", $gateway);
		$result = file_get_contents($url);
		$xml = simplexml_load_string($result);
		return $this->object_array($xml);

	}*/
	/*private function note_code($mobile, $content) {
		header('Content-Type:text/html;charset=utf8');
		$sms = Config::get('sms');
		$sms['password'] = ucfirst(md5($sms['password']));
		//【宜享购】您的验证码为："+$content+"。验证码有效期两分钟，请尽快使用。如非本人操作，可不用理会！
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
	}*/

	//code检查
    public function check_code($code) {
        //$code = '081cnrsy0W9FQg19xYty0g2zsy0cnrsx';
        $code = input('post.code');
        $wechat = $this->getOpenID($code);
        $this->s_msg(null, $wechat);
    }
    //获取openid
    protected function getOpenID($code) {

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->wechant_numb['appid'] . "&secret=" . $this->wechant_numb['appsecret'] . "&code=" . $code . "&grant_type=authorization_code";
        $weixin = file_get_contents($url); //通过code换取网页授权access_token
        $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
        $array = get_object_vars($jsondecode); //转换成数组
        //dump($array);
        session('openid', $array['openid']);
        return $array;
        
    }
    public function aaa(){
    	$sms = Config::get('sms');
    	$content = '4561';
    	$sms['content'] = $sms['content'].$content.$sms['contentlast'];
    	halt($sms);
    }
    //上传图片
    public function upload()
    {
        $bb = Container::get('env')->get('ROOT_PATH');
        $file = request()->file('imgFile');
        //halt($file);
        $info = $file->validate(['size' => '4096000'])
            ->move($bb . 'public/uploads/');
        if ($info) {
            $path = '/uploads/' . $info->getsavename();
            $path = str_replace("\\", "/", $path);
            $this->s_msg(null,$path);
        } else {
            $data = array(
                'msg' => $file->getError(),
                'code' => 0,
            );
            $this->e_msg($data['msg']);
        }
    }
    //自动确认收货
    public function zd()
    {
    	// $i=0;
    	$date=date('Y-m-d');
    	$list=db('order')->where('or_type',2)->select();
    	// halt($list);
    	foreach ($list as $key => $value) {
    		$up = date('Y-m-d',strtotime($value['or_creat_time']));
	    	$Date_List_a1=explode('-',$date);
	    	$Date_List_a2=explode('-' ,$up);
	    	$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
	    	$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
	    	$Days=round(($d1-$d2)/3600/24);
	    	if ($Days>=7) {
	    	// halt(123);$
	    		$i++;
	    		$info=db('order')->where('id',$value['id'])->update(['or_type'=>3]);
	    	}
    	}
    }

}
