<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//用户
Route::rule('user/detail', 'index/user/detail', 'get|options'); //用户详情 传入id 则表示某个人的详情
Route::rule('user/direct', 'index/user/direct', 'get|options'); //下级  传入id 则表示该用户的下级

//门店
Route::rule('store/index', 'index/store/index', 'get|options'); //门店列表
Route::rule('store/product', 'index/store/product', 'get|options'); //分类产品列表
Route::rule('store/detail', 'index/store/detail', 'get|options'); //产品详情

//订单
//Route::rule('order/add', 'index/order/add', 'post|options'); //添加订单
//Route::rule('order/index', 'index/order/index', 'get|options'); //订单列表

//门店登录
Route::rule('store/login', 'mall/login/login', 'post|options');


//公共控制器
Route::rule('every/send', 'index/every/send', 'post|options'); //验证码
Route::rule('every/sctp', 'index/every/sctp', 'post|options'); //上传图片
