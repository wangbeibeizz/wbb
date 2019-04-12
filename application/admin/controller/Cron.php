<?php
namespace app\admin\controller;

use think\Controller;

/**
 * 乱七八糟控制器
 */
class Cron extends Controller
{

    public function test()
    {
        $pwd = 123456;
        $pass = encrypt($pwd);
        halt($pass);
    }
    public function ceshi()
    {
        $pass = "mNHJlXgomjqLlUzpU/s8JQ==";
        $pwd = decrypt($pass);
        halt($pwd);
    }
}
