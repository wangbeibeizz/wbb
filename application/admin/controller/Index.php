<?php
namespace app\admin\controller;
use app\common\controller\Base;
/**
 * @todo 首页操作
 */
class Index extends Base
{
    // ------------------------------------------------------------------------
    public function index()
    {
        return $this->fetch();
    }

    // ------------------------------------------------------------------------
    public function welcome()
    {
        // 获取平台账户详情
        // $us['all']= 1;
        // $us['l0'] = 1;
        // $us['l1'] = 1;
        // $us['l2'] = 1;
        // $us['l3'] = 1;
        // $us['l4'] = 1;
        // $us['l5'] = 1;
        // $us['l6'] = 1;
        // $us['l7'] = 1;

        // $this->assign('us', $us);

        // $acc['wallet_cash'] = 1;
        // $acc['wallet_integrity'] = 1;
        // $this->assign('acc', $acc);
        // $list['user']=db('user')->count();
        // $list['ptuser']=db('user')->where('us_sum','<',1980)->count();
        // $list['xfuser']=db('user')->where('us_sum','>=',1980)->count();
        // $list['xjjf']=db('user')->sum('us_xjjf');
        // $list['xfjf']=db('user')->sum('us_xfjf');
        // halt($list);
        // $this->assign('list',$list);
        $user=model('user')->count('id');
        // halt($user);
        $us_cash=model('user')->sum('us_cash');
        $us_reg=model('user')->sum('us_reg');
        $list['user']=$user;
        $list['us_cash']=$us_cash;
        $list['us_reg']=$us_reg;
        $list['us_chongzhi']=model('tixian')->where('type',3)->where('tx_review',1)->sum('tx_sum');
        $this->assign('list',$list);
        return $this->fetch();
    }
    // ------------------------------------------------------------------------

}
