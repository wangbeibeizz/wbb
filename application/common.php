<?php
function is_options()
{
    return request()->isOptions();
}
function is_post()
{
    return request()->isPost();
}
function is_get()
{
    return request()->isGet();
}
// ------------------------------------------------------------------------
/**
 * 加密函数
 * @param string 加密后字符串
 */
function encrypt($data, $key = 'fes45dsk')
{
    $prep_code = serialize($data);
    $block = mcrypt_get_block_size('des', 'ecb');
    $pad = $block - (strlen($prep_code) % $block);
    if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
        $prep_code .= str_repeat(chr($pad), $pad);
    }
    $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
    return base64_encode($encrypt);
}

/**
 *  解密函数
 * @param array 解密后数组
 */
function decrypt($str, $key = 'fes45dsk')
{
    $str = base64_decode($str);
    $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
    $block = mcrypt_get_block_size('des', 'ecb');
    $pad = ord($str[($len = strlen($str)) - 1]);
    if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
        $str = substr($str, 0, strlen($str) - $pad);
    }
    return unserialize($str);
}

/**
 * hmacMd5
 */
function HmacMd5($data, $key)
{
    //RFC 2104 HMAC implementation for php
    //Creates an md5 HMAC.
    //Eliminates the need to install mhash to compute a HMAC
    //Hacked by Lance Rushing(NOTE:Hacked means written)
    //需要配置环境支持iconv,否则中文参数不能正常处理
    $b = 64;
    if (strlen($key) > $b) {
        $key = pack("H*", md5($key));
    }
    $key = str_pad($key, $b, chr(0x00));
    $ipad = str_pad('', $b, chr(0x36));
    $opad = str_pad('', $b, chr(0x5c));
    $k_ipad = $key ^ $ipad;
    $k_opad = $key ^ $opad;
    return md5($k_opad . pack("H*", md5($k_ipad . $data)));
}

//解密
function jsDecrypt($encryptedData, $privateKey, $iv = "O2%=!ExPCuY6SKX(")
{
    $encryptedData = base64_decode($encryptedData);
    $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $privateKey, $encryptedData, MCRYPT_MODE_CBC, $iv);
    $decrypted = rtrim($decrypted, "\0");
    return $decrypted;
}

//加密
function jsEncode($encodeData, $privateKey, $iv = "O2%=!ExPCuY6SKX(")
{
    $encode = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $privateKey, $encodeData, MCRYPT_MODE_CBC, $iv));
    $encode = rtrim($encode, "\0");
    return $encode;
}

// ------------------------------------------------------------------------
/**
 * 生成一段随机字符串
 * @param int $len 几位数
 */
function GetRandStr($len)
{
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9",
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

//上传图片
function base64_upload($base64)
{
    $base64_image = str_replace(' ', '+', $base64);
    //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)) {
        $image_name = rand(100, 999) . time() . '.png';
        $path = "/uploads/" . date("Ymd") . '/' . $image_name;

        $image_file = env('ROOT_PATH') . 'public/' . $path;
        $rel = check_path(dirname($image_file));
        //服务器文件存储路径
        if (file_put_contents($image_file, base64_decode(str_replace($result[1], '', $base64_image)))) {
            return $path;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function check_path($path)
{
    if (is_dir($path)) {
        return true;
    }
    if (mkdir($path, 0755, true)) {
        return true;
    }
    return false;
}
function getChild($id)
{
    $map['p_id'] = $id;
    $list = model('Cate')->getlist($map);
    return $list;
}
function generate_password( $length = 8 ) {
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ( $i = 0; $i < $length; $i++ )
    {
    // 这里提供两种字符获取方式
    // 第一种是使用 substr 截取$chars中的任意一位字符；
    // 第二种是取字符数组 $chars 的任意元素
    // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
    $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $password;
}
/**
     * 验证身份证号
     * @param  [type] $idcard [身份证号码]
     */
 function checkIdCard($idcard){
        if(empty($idcard)){
            return false;
        }
        $City = array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
        $iSum = 0;
        $idCardLength = strlen($idcard);
        //长度验证
        if(!preg_match('/^\d{17}(\d|x)$/i',$idcard) and!preg_match('/^\d{15}$/i',$idcard))
        {
            return false;
        }
        //地区验证
        if(!array_key_exists(intval(substr($idcard,0,2)),$City))
        {
           return false;
        }
        // 15位身份证验证生日，转换为18位
        if ($idCardLength == 15)
        {
            $sBirthday = '19'.substr($idcard,6,2).'-'.substr($idcard,8,2).'-'.substr($idcard,10,2);
            echo $sBirthday;die;
            $d = new \DateTime($sBirthday);
            $dd = $d->format('Y-m-d');
            if($sBirthday != $dd){
                return false;
            }
            $idcard = substr($idcard,0,6)."19".substr($idcard,6,9);//15to18
            $Bit18 = getVerifyBit($idcard);//算出第18位校验码
            $idcard = $idcard.$Bit18;
        }
        // 判断是否大于2078年，小于1900年
        $year = substr($idcard,6,4);
        if ($year<1900 || $year>2078 )
        {
            return false;
        }
 
        //18位身份证处理
        $sBirthday = substr($idcard,6,4).'-'.substr($idcard,10,2).'-'.substr($idcard,12,2);
        $d = new \DateTime($sBirthday);
        $dd = $d->format('Y-m-d');
        if($sBirthday != $dd){
            return false;
        }
        //身份证编码规范验证
        $idcard_base = substr($idcard,0,17);
        if(strtoupper(substr($idcard,17,1)) != $this->getVerifyBit($idcard_base))
        {
           return false;
        }
        return true;
    }
    // 计算身份证校验码，根据国家标准GB 11643-1999
    function getVerifyBit($idcard_base){
        if(strlen($idcard_base) != 17)
        {
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++)
        {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }
    //直推奖励
    function zhitui($id)
    {
        $jiangli = db('jiangli')->find();
        $order = db('order')->where('id',$id)->find();
        if ($order['gold_sum']==0) {
            return;
        }
        $user = db('user')->where('id',$order['us_id'])->find();
        if ($user['us_zhitui']==0) {
            return;
        }
        $zt_user = db('user')->where('id',$user['us_zhitui'])->find();
        $list = [
            'us_id'   => $zt_user['id'],
            'rd_id'   => $user['id'],
            'rd_mony' => $order['gold_sum']*(1-$jiangli['kouchu']),
            're_time' => date('Y-m-d H:i:s'),
            're_type' => 3,
        ];
        $info = db('reward')->insert($list);
        db('user')->where('id',$zt_user['id'])->setInc('us_cash',$list['rd_mony']);
        return;
    }

