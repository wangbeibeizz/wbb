<?php
namespace app\common\validate;

use think\Validate;

/**
 * 添加管理员验证器
 */
class Verify extends Validate
{
    protected $rule = [
        // '__token__' => 'token',
        'ad_tel|手机/账号' => 'require|length:11|regex:/^[1][345678][0-9]{9}$/',
        'ad_name|姓名' => 'require|max:25',
        'ad_pwd|登录密码' => 'require',
        'ro_id|角色' => 'require',

        'ptel|推荐人账号' => 'require|regex:/^[1][34578][0-9]{9}$/',
        'us_nickname|昵称' => 'require',
        'us_tel|手机号' => 'require|regex:/^[1][34578][0-9]{9}$/',
        'us_pwd|密码' => 'require',
        //'us_safe_pwd' => 'require',
        'area_name|区域名' => 'require',

        //提现
        'tx_num|提现金额' => 'require|float|gt:0',

        //会员注册
        'take_tel|收货电话' => 'require|regex:/^[1][34578][0-9]{9}$/',
        'take_name|收货人' => 'require',
        'take_addr|收货地址' => 'require',

        'st_name' => 'require',
        'st_logo' => 'require',
        'st_tel' => 'require|regex:/^[1][34578][0-9]{9}$/',
        'area_id|所属区域' => 'require',
        'province|省份' => 'require',
        'city|市' => 'require',
        'town|县' => 'require',
        'st_addr_detail|详细地址' => 'require',

        'ca_name|分类名称' => 'require',
        'ca_pic|分类图标' => 'require',

        'pd_name|商品名称' => 'require',       
        'pd_price|商品价格' => 'require|float',
        'pd_inventory|商品库存' => 'require|elt:99999',
        'ca_id|商品分类' => 'require',
        'pd_pic|商品主图' => 'require',

        'addr_code|收货地址' => 'require',
        'addr_detail|详细收货地址' => 'require',
        'addr_tel|收货电话' => 'require',
        'addr_receiver|收货人' => 'require',


        'or_express|物流公司' => 'require',
        'or_express_num|物流单号' => 'require',

        // 'tel' => 'require|regex:/^[1][34578][0-9]{9}$/',
        // 'realname' => 'require',
        // 'pass' => 'require|min:6|max:16',
        // 'secpwd' => 'require|min:6|max:16',
        // 'old_pass' => 'require|min:6|max:16',
        // 'pass1' => 'require|confirm:pass',
        // 'old_secpwd' => 'require|min:6|max:16',
        // 'secpwd1' => 'require|different:secpwd',
        // 'sex' => 'require',
        // 'num' => 'require|number|>:0',
        // 'alipay' => 'require',
        // 'wechat' => 'require',
        // 'status' => 'require',
        // 'group' => 'require',
        // 'name' => 'require',
    ];
    protected $field = [
        'ad_account' => '管理员账户',
        'ad_real_name' => '管理员真实姓名',
        'ad_pwd' => '管理员登录密码',
        'ad_tel' => '管理员手机号',
        'ptel' => '父账号手机号',
        'us_tel' => '会员手机号',
        'us_nickname' => '昵称',
        'us_pwd' => '用户登录密码',
        'us_safe_pwd' => '用户安全密码',

        'st_name' => '门店名称',
        'st_logo' => '门店logo',
        'st_tel' => '门店手机号',

        'st_longitude' => '经度',
        'st_latitude' => '纬度',

        'st_id' => '门店ID',
        'ca_name' => '分类名称',

        'pd_name' => '商品名称',
        'pd_pic' => '商品主图',
        'pd_price' => '商品价格',
        'ca_id' => '分类ID',

        'co_name' => '配送员姓名',
        'co_tel' => '配送员手机号',

        // 'jine' => '金额',
        // 'pass' => '登录密码',
        // 'pass1' => '二次登录密码',
        // 'old_pass' => '原登录密码',
        // 'secpwd' => '安全密码',
        // 'old_secpwd' => '原安全密码',
        // 'sex' => '性别',
        // 'num' => '金额',
        // 'wechat' => '微信',
        // 'alipay' => '支付宝',
        // 'status' => '状态',
        // 'group' => '分组',
        // 'name' => '管理员',
    ];
    // protected $message = [
    //     'mobile.regex' => '请填写正确的手机号',
    //     'ad_tel.regex' => '请填写正确的手机号',
    //     'ptel.regex' => '请填写正确的手机号',
    // ];
    protected $scene = [
        // 前台
        'addAdmin' => ['ad_name', 'ad_tel', 'ad_pwd', 'ro_id',], //添加管理员
        'editAdmin' => ['ad_name', 'ad_tel', ], //修改管理员
        'addUser' => ['us_nickname', 'us_tel', 'us_pwd'], //添加用户
        'addTake' => ['take_name', 'take_tel', 'take_addr'], //会员注册
        'addAreastore' => ['us_tel', 'area_name','ad_tel'], //添加区域
        'editUser' => ['us_nickname','us_tel'], //修改用户
        'selfeditUser' => ['us_nickname'], //修改用户
        'forgetUser' => ['us_tel', 'us_pwd'], //修改密码
        'addStore' => ['us_tel', 'ad_tel', 'st_tel', 'st_name', 'area_id', 'st_logo','province','city','town','st_addr_detail' ], //添加门店
        'editStore' => ['st_name', 'st_logo', 'st_pic', 'st_tel', 'st_label'], //修改门店
        'addCate' => ['ca_name','ca_pic'], //添加分类
        'addProduct' => ['pd_name', 'pd_pic', 'pd_price', 'pd_inventory', 'ca_id'], //添加产品
        'editPd' => ['pd_name', 'pd_pic', 'pd_price', 'ca_id'], //修改产品
        'addAddr' => ['addr_code', 'addr_detail', 'addr_tel', 'addr_receiver'], //添加地址
        'tixian' => ['tx_num'],//提现
        'deliver' => ['or_express','or_express_num'],//发货
    ];

}
