<?php
namespace app\index\Controller;

use hyperjiang\BankCard;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;
use think\facade\Config;

class Test{
	protected $config = [
        //'appid' => 'wx3d7755dcd2bf370e', // APP APPID
        'app_id' => 'wx2eedda3c6c6a5721', // 公众号 APPID
        //'miniapp_id' => 'wxb3fxxxxxxxxxxx', // 小程序 APPID
        'mch_id' => '1507550611',
        'key' => 'e6063c2e8bdb927e39bee0ef020c202a',
        'notify_url' => 'http://47.92.145.141:8007/index/test/notify',
        // 'cert_client' => './cert/apiclient_cert.pem', // optional，退款等情况时用到
        // 'cert_key' => './cert/apiclient_key.pem',// optional，退款等情况时用到
        // 'log' => [ // optional
        //     'file' => './logs/wechat.log',
        //     'level' => 'debug'
        // ],
        // 'mode' => 'dev', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
    ];
    public function notify()
    {
    	\think\facade\Log::write('111111111111111111111111111111支付回调日志记录');
    }
    public function zc_gw()
    {
        $jiangli=db('jiangli')->where('id',1)->value('zc_gw');
        $jiangli=$jiangli*100;
        $time=floor($jiangli/10);
        $i=0;
        while ($i <= $time) {
            $list[$i]=$i;
            $i++;
        }
        $data = array();
        foreach ($list as $key => $value) {
        	$a = $value/100;
        	$b = $value*10;
        	$c = 100-$value*10;
            $data[$key][]= $a;
            $data[$key][]='购物积分'.$b.'%+创业积分'.$c.'%';
        }
    }
	public function wepay(){
		\think\facade\Log::write('111111111111111111支付');
		$order = [
            'out_trade_no' => time(),
            'total_fee' => '1', // **单位：分**
            'body' => 'test body - 测试',
            //'openid' => 'oh5SgwoyGQlyshch-g6Ob8UqXq4I',
        ];
        $wechat = Pay::wechat($this->config);

        return $wechat->wap($order)->send(); // laravel 框架中请直接 return $wechat->wap($order)

        $pay = Pay::wechat($this->config)->wap($order);
	}
	public function bbb(){
		$a = [4,3];
		$ids = implode(',', $a);
		$map[] = ['id','in',$ids];
        //halt($map);
        $list = model('cart')->getList($map);
		halt($list);
	}
	public function aaa(){
		$array    =   array
		(
		    0 => array
		        (
		            'id' => '1',
		            'pd_num' => '5',
		            'st_id' => '5'
		        ),
		    1 => array
		        (
		            'id' => '2',
		            'pd_num' => '10',
		            'st_id' => '5'
		        ),
		    2 => array
		        (
		            'id' => '3',
		            'pd_num' => '20',
		            'st_id' => '3'
		        )

		); 
		$rel = $this->array_group_by($array,'st_id');
		halt($rel);
	}
	public function array_group_by($arr, $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        return $grouped;
    }
	public function index(){
		//$a = new Log;
		$info = BankCard::info('6225700000000000');
		print_r($info);
	}
	public function trimall($str)//删除空格

	{

	    $oldchar=array("
	 ","　","\t","\n","\r");

	$newchar=array("","","","","");

	    return

	str_replace($oldchar,$newchar,$str);

	}
}
	
