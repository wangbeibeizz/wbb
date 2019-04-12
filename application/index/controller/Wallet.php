<?php
namespace app\index\controller;
use think\facade\Config;
use app\index\controller\Every;
use app\common\controller\Api;
use think\Container;

/**
 * 登陆控制器
 */

// class Wallet extends Common
// {
//     public function __construct()
//     {
//         parent::__construct();
//     }
class Wallet extends Basis {
	function __construct()
    {
		parent::__construct();
	}

    //提现比例
    public function tixianbili()
    {
        $list=db('jiangli')->value('tixian');
        $this->s_msg(null,$list);
    }

    //登录首页的小卡片信息
    public function my_info()
    {
        $data=$this->user;
        //昵称
        $list['us_nickname']=$data['us_nickname'];
        $list['us_name']=$data['us_name'];
        $list['us_id']=$data['us_id'];
        //我的直推人信息
        $list['us_zhitui_name']=model('User')->where('id',$data['us_zhitui'])->value('us_nickname');
        $list['us_zhitui_us_id']=model('User')->where('id',$data['us_zhitui'])->value('us_id');
        //现金积分
        $list['us_cash']=$data['us_cash'];
        $list['us_reg']=$data['us_reg'];
        $list['us_node_number']=$data['us_node_number'];
        $list['gonggao']=db('gonggao')->select();
        $list['us_head_image']=$data['us_head_image'];
        $list['zhitui_number']=model('User')->where('us_zhitui',$data['id'])->count();
        $this->s_msg(null,$list);
    }
    public function my_infoss()
    {
        $data=$this->user;
        $this->s_msg(null,$data['us_zfpwd']);
    }
    //修改登录密码
    public function xg_denglu()
    {
        $data=$this->user;
        $post=input('post.');
        if (empty($post['us_pwd'])||empty($post['us_pwd1'])||empty($post['us_pwd2'])) {
            $this->e_msg('输入完整信息');
        }
        if (encrypt($post['us_pwd2'])!=$data['us_pwd']) {
            $this->e_msg('输入旧密码不正确');
        }
        if ($post['us_pwd']!=$post['us_pwd1']) {
            $this->e_msg('两次密码不一致');
        }
        if ($post['us_pwd']==$post['us_pwd2']) {
            $this->e_msg('输入新密码和旧密码一致');
        }
        $us_pwd=encrypt($post['us_pwd']);
        // halt($us_pwd);
        $list=model('user')->where('id',$data['id'])->update(['us_pwd'=>$us_pwd]);
        if ($list) {
            $this->s_msg('修改成功');
        }
        else
        {
            $this->e_msg('请稍后重试');
        }
    }
    //修改支付密码
    public function xg_zhifu()
    {
        $data=$this->user;
        $post=input('post.');
        if (empty($post['us_zfpwd'])||empty($post['us_zfpwd1'])||empty($post['us_zfpwd2'])) {
            $this->e_msg('输入完整信息');
        }
        if (encrypt($post['us_zfpwd2'])!=$data['us_zfpwd']) {
            $this->e_msg('输入旧密码不正确');
        }
        if ($post['us_zfpwd']!=$post['us_zfpwd1']) {
            $this->e_msg('两次密码不一致');
        }
        if ($post['us_zfpwd']==$post['us_zfpwd2']) {
            $this->e_msg('输入新密码和旧密码一致');
        }
        $us_zfpwd=encrypt($post['us_zfpwd']);
        $list=model('user')->where('id',$data['id'])->update(['us_zfpwd'=>$us_zfpwd]);
        if ($list) {
            $this->s_msg('修改成功');
        }
        else
        {
            $this->e_msg('请稍后重试');
        }
    }
    //添加支付密码
    public function tj_zhifu()
    {
        $data=$this->user;
        $post=input('post.');
        if ($post['us_zfpwd']!=$post['us_zfpwd1']) {
            $this->e_msg('两次支付密码不一致');
        }
        $us_zfpwd=encrypt($post['us_zfpwd']);
        $list=model('user')->where('id',$data['id'])->update(['us_zfpwd'=>$us_zfpwd]);
        if ($list) {
            $this->s_msg('添加成功');
        }
        else
        {
            $this->e_msg('请稍后重试');
        }
    }

    //公告详情  发送过来公告的id
    public function new()
    {
        $list=db('gonggao')->where('id',input('id'))->find();
        $this->s_msg(null,$list);
    }


    //查看我的公排
    public function my_node()
    {
        $data=$this->user;
        $user=model('User');
        $head=$user->where('id',$data['id'])->find();
        if ($head['us_node']!=0) {
            $head=$user->where('id',$head['us_node'])->find();
        }
        if ($head['us_node']!=0) {
            $head=$user->where('id',$head['us_node'])->find();
        }
        // halt($head);
        $list[0]=$user->where('id',$head['id'])->find();
        // halt($list[0]['id']);
        $ct=$user->where('us_node',$list[0]['id'])->count();
        // halt($ct);
        if ($ct==0) {
            foreach ($list as $key => $value) {
                if (empty($value)) {
                    break;
                }
                $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
            }
            $this->s_msg('null',$list);
        }
        if ($ct==1) {
            $list[1]=$user->where('us_node',$list[0]['id'])->find();
            foreach ($list as $key => $value) {
                if (empty($value)) {
                    break;
                }
                $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
            }
            $this->s_msg('null',$list);
        }
        if ($ct==2) {
            $i=1;
            $li=$user->where('us_node',$list[0]['id'])->order('us_area')->select();
            // halt($li);
            foreach ($li as $key) {
                $list[$i]=$user->where('id',$key['id'])->find();
                $i++;
                // halt($list);
            }
            // halt($list);
            $ct=$user->where('us_node',$list[1]['id'])->count();
            if ($ct==0) {
                foreach ($list as $key => $value) {
                if (empty($value)) {
                    break;
                }
                $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
            }
                $this->s_msg(null,$list);
            }
            if ($ct==1) {
                $list[3]=$user->where('us_node',$list[1]['id'])->find();
                foreach ($list as $key => $value) {
                if (empty($value)) {
                    break;
                }
                $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
            }
                $this->s_msg(null,$list);
            }
            if ($ct==2) {
                $i=3;
                $ii=$user->where('us_node',$list[1]['id'])->order('us_area')->select();
                foreach ($ii as $key) {
                    $list[$i]=$user->where('id',$key['id'])->find();
                    $i++;
                }
                $ct=$user->where('us_node',$list[2]['id'])->count();
                if ($ct==0) {
                    foreach ($list as $key => $value) {
                if (empty($value)) {
                    break;
                }
                $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
            }
                    $this->s_msg(null,$list);
                }
                if ($ct==1) {
                    $list[5]=$user->where('us_node',$list[2]['id'])->find();
                    foreach ($list as $key => $value) {
                        if (empty($value)) {
                            break;
                        }
                        $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
                    }
                    $this->s_msg(null,$list);
                }
            }
        }
    }

    //查看直推人的公排的公排
    public function zt_node()
    {
        $data=input('id');
        $user=model('User');
        $head=$user->where('id',$data)->find();
        if ($head['us_node']!=0) {
            $head=$user->where('id',$head['us_node'])->find();
        }
        if ($head['us_node']!=0) {
            $head=$user->where('id',$head['us_node'])->find();
        }
        // halt($head);
        $list[0]=$user->where('id',$head['id'])->find();
        // halt($list[0]['id']);
        $ct=$user->where('us_node',$list[0]['id'])->count();
        // halt($ct);
        if ($ct==0) {
            foreach ($list as $key => $value) {
                if (empty($value)) {
                    break;
                }
                $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
            }
            $this->s_msg('null',$list);
        }
        if ($ct==1) {
            $list[1]=$user->where('us_node',$list[0]['id'])->find();
            foreach ($list as $key => $value) {
                if (empty($value)) {
                    break;
                }
                $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
            }
            $this->s_msg('null',$list);
        }
        if ($ct==2) {
            $i=1;
            $li=$user->where('us_node',$list[0]['id'])->order('us_area')->select();
            // halt($li);
            foreach ($li as $key) {
                $list[$i]=$user->where('id',$key['id'])->find();
                $i++;
                // halt($list);
            }
            // halt($list);
            $ct=$user->where('us_node',$list[1]['id'])->count();
            if ($ct==0) {
                foreach ($list as $key => $value) {
                if (empty($value)) {
                    break;
                }
                $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
            }
                $this->s_msg(null,$list);
            }
            if ($ct==1) {
                $list[3]=$user->where('us_node',$list[1]['id'])->find();
                foreach ($list as $key => $value) {
                if (empty($value)) {
                    break;
                }
                $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
            }
                $this->s_msg(null,$list);
            }
            if ($ct==2) {
                $i=3;
                $ii=$user->where('us_node',$list[1]['id'])->order('us_area')->select();
                foreach ($ii as $key) {
                    $list[$i]=$user->where('id',$key['id'])->find();
                    $i++;
                }
                $ct=$user->where('us_node',$list[2]['id'])->count();
                if ($ct==0) {
                    foreach ($list as $key => $value) {
                if (empty($value)) {
                    break;
                }
                $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
            }
                    $this->s_msg(null,$list);
                }
                if ($ct==1) {
                    $list[5]=$user->where('us_node',$list[2]['id'])->find();
                    foreach ($list as $key => $value) {
                        if (empty($value)) {
                            break;
                        }
                        $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
                    }
                    $this->s_msg(null,$list);
                }
            }
        }
    }

    //我的个人信息
    public function my_infos()
    {
        $data=$this->user;
        if (is_post()) {
            $post=input('post.');
            if ($post['alipay_name']=='') {
                $post['alipay_name']=$data['alipay_name'];
            }
            if ($post['alipay']=='') {
                $post['alipay']=$data['alipay'];
            }
            if ($post['bank_loction']=='') {
                $post['bank_loction']=$data['bank_loction'];
            }
            if ($post['bank_user']=='') {
                $post['bank_user']=$data['bank_user'];
            }
            if ($post['bank_number']=='') {
                $post['bank_number']=$data['bank_number'];
            }
            $post['us_dizhi'] = $post['Jtdizhi'];
            $list=model('User')->where('id',$data['id'])->update($post);
            if ($list) {
                $this->s_msg('修改成功');
            }
            else
            {
                $this->e_msg('请稍后重试');
            }
        }
        $data['Jtdizhi'] = $data['us_dizhi'];
        $this->s_msg(null,$data);
    }

    //地址列表
    public function dizhi()
    {
        $data=$this->user;
        $dizhi=db('dizhi')->where('us_id',$data['id'])->order('id desc')->select();
        foreach ($dizhi as $key => $value) {
            //将字符串转换成数组
            $dizhi[$key]['addr_code']=explode(',',$value['addr_code']);
        }
        $this->s_msg(null,$dizhi);
    }
    //添加地址
    //姓名：us_name，电话：us_tel,区域us_area，详细地址us_detailed，默认地址type=1，其他type=0
    public function tj_dizhi()
    {
        $data=$this->user;
        if (is_post()) {
            $post=input('post.');
            // halt($post);
            //将数组装换成字符串
            $post['addr_code']=implode(',',$post['addr_code']);
            $post['us_id']=$data['id'];
            $info=db('dizhi')->insertGetId($post);
            if ($info) {
                $this->s_msg('添加地址成功');
            }
            else{
                $this->e_msg('请稍后重试');
            }
        }
    }

    //地址详细信息 地址：id
    public function xg_dizhi_view()
    {
        $id=input('id');
        $list=db('dizhi')->where('id',$id)->find();
        //将字符串转换成数组
        $list['addr_code']=explode(',',$list['addr_code']);
        if ($list) {
            $this->s_msg(null,$list);
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }

    //删除地址  地址：id
    public function sc_dizhi()
    {
        $id=input('id');
        $list=db('dizhi')->delete($id);
        if ($list) {
            $this->s_msg('删除成功');
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }

    //修改地址
    public function xg_dizhi()
    {
        $post=input('post.');
        // halt($post);
        //将数组装换成字符串
        $post['addr_code']=implode(',',$post['addr_code']);
        $list=db('dizhi')->where('id',$post['id'])->update($post);
        if ($list) {
            $this->s_msg('修改成功');
        }
        else{
            $this->e_msg('未进行任何修改');
        }
    }

    //奖励记录  奖励类型：直推奖励re_type=0，自身出局奖励：re_type=1，首节点人出局奖励：re_type=2
    //ID:rd_id,昵称：rd_nickname，姓名：rd_name，电话：rd_tel，金额：rd_mony，时间：re_time
    public function jiangli()
    {
        $data=$this->user;
        $list=db('reward')->where('us_id',$data['id'])->order('re_time desc')->select();
        foreach ($list as $key => $v) {
            $info=model('User')->where('id',$v['rd_id'])->find();
            $list[$key]['rd_id']=$info['us_id'];
            $list[$key]['rd_nickname']=$info['us_nickname'];
            $list[$key]['rd_name']=$info['us_name'];
            $list[$key]['rd_tel']=$info['us_tel'];
        }
        $this->s_msg(null,$list);
    }

    //我推荐的人
    //ID:us_id,姓名us_name，推荐人ID：tj_id,直推时间：us_creattime，当前公排数量：us_node_number
    public function tuijian()
    {
        $data=$this->user;
        $list = model('User')->where('us_zhitui',$data['id'])->order('us_creattime desc')->select();

        $count = model('User')->where('us_ztstring','like',$data['us_ztstring'].','.$data['id'].'%')->count();
        // halt($list);
        $number = $count;
        foreach ($list as $key => $v) {
            $list[$key]['tj_id'] = $data['us_id'];
            $list[$key]['number_tuijian'] = model('user')->where('us_zhitui',$v['id'])->count();
            // $number = $number+$list[$key]['number_tuijian'];
            if ($v['us_node']==0) {
                $list[$key]['us_node_number'] = $v['us_node_number'];
            }else{
                $user = model('user')->where('id',$v['us_node'])->find();
                if ($user['us_node']==0) {
                    $list[$key]['us_node_number'] = $user['us_node_number'];
                }else{
                    $us = model('user')->where('id',$user['us_node'])->find();
                    $list[$key]['us_node_number'] = $us['us_node_number'];
                }
            }
        }
        $info['number'] = $number;
        $info['list'] = $list;
        $this->s_msg(null,$info);
    }

    // 提现  提现金额sum 提现方式type:0支付宝，1微信，2银行卡 登录密码us_pwd
    public function tixian()
    {
        $jiangli = db('jiangli')->find();
        $data    = $this->user;
        if (is_post()) {
            $post=input('post.');
            if ($post['type']==0) {
                if ($data['alipay']==null) {
                    $this->e_msg('未绑定支付宝');
                }
                else{
                    $pay=$data['alipay'];
                }
            }
            if ($post['type']==1) {
                if ($data['wechat']==null) {
                    $this->e_msg('未绑定微信');
                }
                else{
                    $pay=$data['wechat'];
                }
            }
            if ($post['type']==2) {
                if ($data['bank_number']==null) {
                    $this->e_msg('未绑定银行卡');
                }
                else{
                    $pay=$data['bank_number'];
                }
            }
            if ($data['us_zfpwd']=='') {
                $this->e_msg('请前往添加支付密码');
            }
            if (encrypt($post['us_pwd'])!=$data['us_zfpwd']) {
                $this->e_msg('密码错误！');
            }
            if ($post['sum']>$data['us_cash']) {
                $this->e_msg('可提现金额不足');
            }
            if ($post['sum']<$jiangli['tx_mix']) {
                $this->e_msg('提现金额小于'.$jiangli['tx_mix']);
            }
            $list = array(
                'us_id' => $data['id'],
                'tx_sum'=>$post['sum'],
                'tx_time'=>date('y-m-d H:i:s'),
                'type'=>0,
                'tx_fangshi'=>$post['type'],
                'tx_number'=>$pay,
            );
            $a=db('user')->where('id',$data['id'])->setDec('us_cash',$post['sum']);
            $b=db('tixian')->insert($list);
            if ($a&&$b) {
                $this->s_msg('申请已提交');
            }
            else{
                $this->e_msg('请稍后重试');
            }
        }
        //可提现金额
        $this->s_msg(null,$data['us_xjjf']);
    }

    //提现记录  支付宝tx_fangshi=0，微信tx_fangshi=1，银行卡tx_fangshi=2
    //tx_review=0:未审核，tx_review=1：审核通过，tx_review=3已驳回
    //时间；tx_time，金额：tx_sum
    public function tixianjilu()
    {
        $data=$this->user;
        $list=db('tixian')->where('us_id',$data['id'])->where('type',['=',0],['=',3],'or')->order('tx_time desc')->select();
        $this->s_msg(null,$list);
    }
    //好友转账购物积分 sum转账金额，us_nickname好友账号，us_pwd登录密码
    public function zhuanzhang()
    {
        $jiangli = db('jiangli')->find();
        $post    = input('post.');
        $use     = model('User');
        $user    = $use->where('us_nickname',$post['us_nickname'])->find();
        if (!$user) {
            $this->e_msg('账号不存在');
        }
        $data=$this->user;


        $td = model('User')->where('us_ztstring','like',$data['us_ztstring'].','.$data['id'].'%')->column('id');
        // if (!in_array($user['id'],$td)) {
        //     $this->e_msg('请转账下级用户');
        // }
        if ($post['sum']<0) {
            $this->e_msg('请输入正确金额');
        }
        if ($post['sum']>$data['us_cash']) {
            $this->e_msg('账户余额不足！');
        }
        if ($post['sum']<$jiangli['zz_gouwu']) {
            $this->e_msg('低于最小转账金额'.$jiangli['zz_gouwu']);
        }
        if ($data['us_zfpwd']=='') {
            $this->e_msg('请前往添加支付密码');
        }
        if ($data['us_zfpwd']!=encrypt($post['us_pwd'])) {
            $this->e_msg('密码错误');
        }
        //转账记录
        $list = array(
            'us_id'     =>$data['id'],
            'tx_id'     =>$user['id'],
            'tx_name'   =>$user['us_name'],
            'tx_tel'    =>$user['us_tel'],
            'tx_sum'    =>$post['sum'],
            'tx_time'   =>date('y-m-d H:i:s'),
            'type'      =>1,
        );
        $c=db('tixian')->insert($list);
        // halt($c);
        $a=$use->where('us_nickname',$post['us_nickname'])->setInc('us_cash',$post['sum']);
        // halt($a);
        $b=$use->where('id',$data['id'])->setDec('us_cash',$post['sum']);
        // halt($b);
        if ($a&&$b&&$c) {
            $this->s_msg('转账成功');
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }

    //好友转账创业积分 sum转账金额，us_nickname好友账号，us_pwd登录密码
    public function zhuanzhang_cy()
    {
        $jiangli = db('jiangli')->find();
        $post    = input('post.');
        $use     = model('User');
        $user    = $use->where('us_nickname',$post['us_nickname'])->find();
        if (!$user) {
            $this->e_msg('账号不存在');
        }
        $data=$this->user;
        $td = model('User')->where('us_ztstring','like',$data['us_ztstring'].','.$data['id'].'%')->column('id');
        if (!in_array($user['id'],$td)) {
            $this->e_msg('请转账下级用户');
        }
        // if ($user['us_zhitui']!=$data['id']) {
        //     $this->e_msg('请转账下级用户');
        // }
        if ($post['sum']<0) {
            $this->e_msg('请输入正确金额');
        }
        if ($post['sum']>$data['us_reg']) {
            $this->e_msg('账户余额不足！');
        }
        if ($post['sum']<$jiangli['zz_chuangye']) {
            $this->e_msg('低于最小转账金额'.$jiangli['zz_chuangye']);
        }
        if ($data['us_zfpwd']=='') {
            $this->e_msg('请前往添加支付密码');
        }
        if ($data['us_zfpwd']!=encrypt($post['us_pwd'])) {
            $this->e_msg('密码错误');
        }
        //转账记录
        $list = array(
            'us_id'     => $data['id'],
            'tx_id'     => $user['id'],
            'tx_name'   => $user['us_name'],
            'tx_tel'    => $user['us_tel'],
            'tx_sum'    => $post['sum'],
            'tx_time'   => date('y-m-d H:i:s'),
            'type'      => 1,
            'zz_type'   => 1,
        );
        $c = db('tixian')->insert($list);
        $a = $use->where('us_nickname',$post['us_nickname'])->setInc('us_reg',$post['sum']);
        $b = $use->where('id',$data['id'])->setDec('us_reg',$post['sum']);
        if ($a&&$b&&$c) {
            $this->s_msg('转账成功');
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }
    //转账记录 收款人姓名tx_name，收款人电话tx_tel 金额tx_sum  时间tx_time
    public function zhuanzhangjl()
    {
        $data=$this->user;
        $list=db('tixian')->where('type',1)->where('us_id|tx_id',$data['id'])->order('tx_time desc')->select();
        foreach ($list as $key => $value) {
            if ($value['zz_type']==0) {
                $list[$key]['zz_type'] = '购物积分';
            }
            if ($value['zz_type']==1) {
                $list[$key]['zz_type'] = '创业积分';
            }
        }
        $this->s_msg(null,$list);
    }

    //对方资料   返回tx_id;
    public function duifangzhiliao()
    {
        $post=input('tx_id');
        $list=model('User')->where('id',$post)->find();
        $this->s_msg(null,$list);
    }

    //充值  充值金额tx_sum 密码us_pwd 支付方式tx_fangshi=0;支付宝，1：微信，2：银行卡
    public function chongzhi()
    {
        $data=$this->user;
        $post=input('post.');
        $list['us_id']=$data['id'];
        $list['tx_sum']=$post['tx_sum'];
        $list['tx_time']=date('y-m-d H:i:s');
        $list['type']=3;
        $list['tx_review']=4;
        $list['tx_fangshi']=$post['tx_fangshi'];
        if ($data['us_zfpwd']=='') {
                $this->e_msg('请前往添加支付密码');
            }
        if (encrypt($post['us_pwd'])!=$data['us_zfpwd']) {
            $this->e_msg('密码错误');
        }
        if ($post['tx_sum']<0) {
            $this->e_msg('请输入正确充值金额');
        }
        $info=db('tixian')->insertGetId($list);
        if ($info) {
            $this->s_msg(null,$info);
        }
    }


    public function congzhizf()
    {
        // halt(input('get.'));
        $admin=db('admin')->where('id',1)->find();
        // halt($list);
        $list['ad_bank']=$admin['ad_bank'];
        $list['ad_bank_name']=$admin['ad_bank_name'];
        $list['ad_bank_number']=$admin['ad_bank_number'];
        $list['ad_wechat_name']=$admin['ad_wechat_name'];
        $list['ad_wechat']=$admin['ad_wechat'];
        $list['ad_alipay_name']=$admin['ad_alipay_name'];
        $list['ad_alipay']=$admin['ad_alipay'];
        $list['wechat_image']=$admin['wechat_image'];
        $list['sum']=db('tixian')->where('id',input('id'))->value('tx_sum');
        $this->s_msg($list);
    }

    public function congzhizf_type()
    {
        $admin = db('admin')->where('id',1)->find();
        $post = input('post.');
        // halt($post);
        if ($post['type']==2) {
            $list['ad_bank']        = $admin['ad_bank'];
            $list['ad_bank_name']   = $admin['ad_bank_name'];
            $list['ad_bank_number'] = $admin['ad_bank_number'];
        }
        if ($post['type']==1) {
            $list['ad_wechat_name'] = $admin['ad_wechat_name'];
            $list['ad_wechat']      = $admin['ad_wechat'];
            $list['wechat_image']   = $admin['wechat_image'];
        }
        if ($post['type']==0) {
            $list['ad_alipay_name'] = $admin['ad_alipay_name'];
            $list['ad_alipay']      = $admin['ad_alipay'];
        }
        $list['sum'] = db('tixian')->where('id',$post['id'])->value('tx_sum');
        $this->s_msg($list);
    }

    public function congzhizfs()
    {
        $admin=db('admin')->where('id',1)->find();
        // halt($list);
        $list['ad_bank']=$admin['ad_bank'];
        $list['ad_bank_name']=$admin['ad_bank_name'];
        $list['ad_bank_number']=$admin['ad_bank_number'];
        $list['ad_wechat_name']=$admin['ad_wechat_name'];
        $list['ad_wechat']=$admin['ad_wechat'];
        $list['ad_alipay_name']=$admin['ad_alipay_name'];
        $list['wechat_image']=$admin['wechat_image'];
        $list['ad_alipay']=$admin['ad_alipay'];
        $list['sum']=db('order')->where('id',input('id'))->value('or_sum');
        $this->s_msg($list);
    }





     //充值功能，改变充值支付状态
    public function congzhizt()
    {
        // halt(111);
        $post=input('id');
        $image=db('tixian')->where('id',$post)->value('tx_image');
        if ($image=="") {
            $this->e_msg('请上传支付凭证');
        }
        $list=db('tixian')->where('id',$post)->update(['tx_review'=>0]);
        $this->s_msg('已提交');
    }

    //支付功能，改变支付状态
    public function zhifuzt()
    {
        $post=input('id');
        $image=db('order')->where('id',$post)->value('or_zf_image');
        if ($image=='') {
            $this->e_msg("请上传支付凭证");
        }
        $list=db('order')->where('id',$post)->update(['or_type'=>1]);
        $this->s_msg('已提交');
    }

    //获取管理员信息
    public function admessage()
    {
        $admin=db('admin')->where('id',1)->find();
        $map['bank_name']=$admin['bank_name'];
        $map['bank_num']=$admin['bank_num'];
        $map['wechat']=$admin['wechat'];
        $map['alipay']=$admin['alipay'];
        $map['sum']=db('dingdanzh')->where('id',input('id'))->value('dd_sum');
        $map['ca_id']=db('dingdanzh')->where('id',input('id'))->value('ca_id');
        $this->s_msg(null,$map);
    }




    //购买商品
    //商品pd_id,规格gg_id,地址dd_id,购买数量pd_number
    public function goumai()
    {
        $data = $this->user;
        $post = input('post.');
        $product = db('product')->where('id',$post['pd_id'])->find();
        $product_type = db('product_type')->where('id',$post['gg_id'])->find();
        $dizhi = db('dizhi')->where('id',$post['dd_id'])->find();
        $order['us_id']         = $data['id'];
        $order['or_sum']        = $product_type['type_sum']*$post['pd_number'];
        $order['gold_sum']      = $product_type['gold_yd']*$post['pd_number'];
        $order['or_address_id'] = $dizhi['id'];
        $order['or_creat_time'] = date('y-m-d H:i:s');
        $order['or_type']       = 0;
        //插入订单
        $order_id = db('order')->insertGetId($order);
        //对应订单的商品
        $order_detailed['pd_id']      = $product['id'];
        $order_detailed['pd_name']    = $product['pd_name'];
        $order_detailed['pd_price']   = $product_type['type_sum'];
        $order_detailed['pd_sum']     = $product_type['type_sum']*$post['pd_number'];
        $order_detailed['gold']       = $product_type['gold_yd'];
        $order_detailed['gold_sum']   = $product_type['gold_yd']*$post['pd_number'];
        $order_detailed['gw_number']  = $post['pd_number'];
        $order_detailed['pd_image']   = $product['pd_image'];
        $order_detailed['pd_type']    = $product_type['type'];
        $order_detailed['pd_type_id'] = $product_type['id'];
        $order_detailed['od_id']      = $order_id;
        $order_detailed = db('order_detailed')->insertGetId($order_detailed);
        if ($order_id&&$order_detailed) {
            $this->s_msg(null,$order_id);
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }

    //点击结算，添加订单
    public function jiesuan()
    {
        $data=$this->user;
        $post=input('post.');//购物车id
        $post = $post['arr'];
        $in = array(
            'us_id'           => $data['id'],
            'or_creat_time'   => date('y-m-d H:i:s'),
            'or_type'         => 0,
        );
        $order    = db('order')->insertGetId($in);
        $sum      = 0;
        $gold_sum = 0;
        foreach ($post as $v) {
            $gwc      = db('gouwuche')->where('id',$v)->find();
            $sum      = $gwc['pd_number']*$gwc['ca_price']+$sum;
            $gold_sum = $gwc['pd_number']*$gwc['gold_yd']+$gold_sum;
            $list = array(
                'pd_id'     => $gwc['pd_id'],
                'pd_name'   => $gwc['pd_name'],
                'pd_price'  => $gwc['ca_price'],
                'pd_sum'    => $gwc['ca_price']*$gwc['pd_number'],
                'gold'      => $gwc['gold_yd'],
                'gold_sum'  => $gwc['gold_yd']*$gwc['pd_number'],
                'gw_number' => $gwc['pd_number'],
                'pd_image'  => $gwc['pd_pic'],//购买数量
                'pd_type'   => $gwc['ca_type'],//规格
                'od_type'   => 0,
                'od_id'     => $order,
                 );
            db('order_detailed')->insertGetId($list);
            db('gouwuche')->where('id',$v)->delete();
        }
        db('order')->where('id',$order)->update(['or_sum'=>$sum,'gold_sum'=>$gold_sum]);
        $info=db('order')->where('id',$order)->find();
        if ($info) {
            $this->s_msg(null,$info);
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }

    //选择地址  地址dz_id  还有订单的dd_id
    public function xzdizhi()
    {
        $post=input('post.');
        $list=db('order')->where('id',$post['dd_id'])->update(['or_address_id'=>$post['dz_id']]);
        $dizhi=db('dizhi')->where('id',$post['dz_id'])->find();
        $this->s_msg(null,$dizhi);
    }
    //线上支付
    public function xszhifu()
    {
        $post=input('post.');
        $data=$this->user;
        if (empty($data['us_zfpwd'])) {
            $this->e_msg('请前往添加支付密码');
        }
        if (encrypt($post['us_pwd'])!=$data['us_zfpwd']) {
            $this->e_msg('支付密码错误');
        }
        $list=db('order')->where('id',$post['id'])->find();
        if ($list['or_sum']>$data['us_cash']) {
            $this->e_msg('现金积分不足');
        }
        $a=db('user')->where('id',$data['id'])->setDec('us_cash',$list['or_sum']);
        $order=db('order')->where('id',$post['id'])->update([
            'or_type'=>1,
            'or_zf_type'=>1,
        ]);
        if ($order) {
            $this->s_msg('支付成功');
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }

    //修改购物车商品数量 ,传过来购物车id,修改的数量number
    public function xg_gouwuche()
    {
        $post=input('post.');
        $list=db('gouwuche')->where('id',$post['id'])->update(['pd_number'=>$post['number']]);
        if ($list) {
            $this->s_msg('修改成功');
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }

    //删除购物车商品 ,传过来购物车id
    public function sc_gouwuche()
    {
        $post=input('post.');
        $list=db('gouwuche')->where('id',$post['id'])->delete();
        if ($list) {
            $this->s_msg('已删除');
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }


    //购物车列表
    public function gouwuche()
    {
        $data=$this->user;
        // $data=db('user')->where('id',74)->find();
        $gwc=db('gouwuche')->where('us_id',$data['id'])->order('pd_add_time desc')->select();
        // halt($gwc);
        $this->s_msg(null,$gwc);
    }

    //加入购物车  商品id:pd_id  类型的id：ca_id  购买数量pd_number
    public function jrgouwuche()
    {
        $data=$this->user;
        $post=input('post.');
        $pd=db('product')->where('id',$post['pd_id'])->find();
        $sm=db('product_type')->where('id',$post['ca_id'])->find();
        $data = array(
            'us_id'       => $data['id'],
            'pd_id'       => $post['pd_id'],
            //商品详情
            'pd_content'  => $pd['pd_details'],
            'pd_name'     => $pd['pd_name'],
            //商品单价
            'ca_price'    => $sm['type_sum'],
            'pd_number'   => $post['pd_number'],
            'pd_pic'      => $pd['pd_image'],
            'pd_add_time' => date('y-m-d H:i:s'),
            'ca_type'     => $sm['type'],
            'ca_id'       => $post['ca_id'],
            'gold_yd'     => $sm['gold_yd'],
        );
        $in=db('gouwuche')->insert($data);
        if (!$in) {
            $this->e_msg('添加购物车失败');
        }
        else{$this->s_msg('成功添加购物车');}
    }

    //获取对应订单的商品
    public function dz_product()
    {
        $post=input('id');
        $order=db('order')->where('id',$post)->find();
        $order_detailed=db('order_detailed')->where('od_id',$post)->select();
        foreach ($order_detailed as $key => $value) {
            $order_detailed[$key]['pd_image']=explode(',',$value['pd_image']);
            $order_detailed[$key]['pd_image']=$order_detailed[$key]['pd_image'][0];
        }
        $dizhi=db('dizhi')->where('id',$order['or_address_id'])->find();
        $map['order']=$order;
        $map['dizhi']=$dizhi;
        $map['order_detailed']=$order_detailed;
        $this->s_msg(null,$map);
    }

    //商品详情


    //订单 全部订单type=0,待支付1，待审核2，已发货3，已收货4
    public function dingdanxq()
    {
        $data=$this->user;
        if (input('type')==0) {
            $list=db('order')->where('us_id',$data['id'])->select();
            foreach ($list as $k => $v) {
                $list[$k]['product']=db('order_detailed')->where('od_id',$v['id'])->select();
            }
        }
        if (input('type')==1) {
            $list=db('order')->where('us_id',$data['id'])->where('or_type',0)->select();
            foreach ($list as $k => $v) {
                $list[$k]['product']=db('order_detailed')->where('od_id',$v['id'])->select();
            }
        }
        if (input('type')==2) {
            $list=db('order')->where('us_id',$data['id'])->where('or_type',1)->select();
            foreach ($list as $k => $v) {
                $list[$k]['product']=db('order_detailed')->where('od_id',$v['id'])->select();
            }
        }
        if (input('type')==3) {
            $list=db('order')->where('us_id',$data['id'])->where('or_type',2)->select();
            foreach ($list as $k => $v) {
                $list[$k]['product']=db('order_detailed')->where('od_id',$v['id'])->select();
            }
        }
        if (input('type')==4) {
            $list=db('order')->where('us_id',$data['id'])->where('or_type',3)->select();
            foreach ($list as $k => $v) {
                $list[$k]['product']=db('order_detailed')->where('od_id',$v['id'])->select();
            }
        }
        $this->s_msg(null,$list);
    }

    //确认收货
    public function querensh()
    {
        $post=input('id');
        $up=db('order')->where('id',$post)->update(['or_type'=>3]);
        if ($up) {
            $this->s_msg('确认收货成功');
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }
}



