<?php
namespace app\index\controller;

use app\common\controller\Api;

/**
 * 无需登录
 */
class Common extends Api
{

    public function __construct()
    {
        parent::__construct();
    }

    //拿出每天增加的5%给所有消费商平分
    public function upday()
    {
        //查找今天消费的总金额
        $sum=db('day')->where('upday_type',0)->sum('us_sum');
        //查找消费商人数
        $xfs=model('User')->where('us_type',1)->count();
        //每个消费商收入金额
        $resum=$sum/$xfs;
        $xfsum=$resum*0.3;
        $xjsum=$resum*0.7;
        $people=model('User')->where('us_type',1)->select();
        foreach ($people as $v) {
            $xf=$v['us_xfjf']+$xfsum;
            $xj=$v['us_xjjf']+$xjsum;
            $list=model('User')->where('us_type',1)->update(['us_xfjf'=>$xf,'us_xjjf'=>$xj]);
            if (!$list) {
                $this->e_msg('发放奖励失败');
            }
        }
    }
    //直推人消费的第一个1980元奖励消费商20%
    public function upzhitui($id,$sum)   //用户id，支付金额sum
    {
        $user=model('User')->where('id',$id)->find();
        if ($user['us_sum']<1980) {
            $sum=$sum*0.2;
            $xfsum=$sum*0.3;
            $xjsum=$sum*0.7;
            $tjr=model('User')->where('id',$user['us_pid'])->setInc('us_xfjf',$xfsum);
            $tjrr=model('User')->where('id',$user['us_pid'])->setInc('us_xjjf',$xjsum);
            if(!$tjr||!$tjrr){
                $this->e_msg('发放奖励失败');
            }
        }
    }
    //每个人消费的5%平分给上面20代推荐人
    public function upjufen($id,$sum)
    {
        //找个用户个人信息
        $user=model('User')->where('id',$id)->find();
        $user['us_path']=explode(',',$user['us_path']);
        $user['us_path']=array_reverse($user['us_path']);
        $a=0;
        $long=count($user['us_path']);
        $long--;
        if ($long>20) {
            $long=20;
            $sum=$sum/$long;
            $xfsum=$sum*0.3;
            $xjsum=$sum*0.7;
        }
        else{
            $sum=$sum/$long;
            $xfsum=$sum*0.3;
            $xjsum=$sum*0.7;
        }
        foreach ($user['us_path'] as $key => $v) {
            $a=$a+1;
            if ($a>20) {
                $this->s_msg(null,1);
            }
            if ($user['us_path'][$key]==0) {
                $this->s_msg(null,1);
            }
            else{
                $c=model('User')->where('id',$user['us_path'][$key])->setInc('us_xfjf',$xfsum);
                $d=model('User')->where('id',$user['us_path'][$key])->setInc('us_xjjf',$xjsum);
            }
        }
    }
    //消费商直推超过30人分成一组，获得全国每单的3%奖励
    public function upquanguo($sum)
    {
        $count=model('User')->where('us_type',3)->count();
        if($count<30){
            $this->s_msg(null,1);
        }
        $sum=$sum/$count;
        $xfsum=$sum*0.3;
        $xjsum=$sum*0.7;
        $data=model('User')->where('us_type',3)->select();
        foreach ($data as $k) {
            $a=model('User')->where('id',$k['id'])->setInc('us_xfjf',$xfsum);
            $b=model('User')->where('id',$k['id'])->setInc('us_xjjf',$xjsum);
        }
    }

}
