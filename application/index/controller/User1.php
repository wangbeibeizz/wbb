<?php
namespace app\index\Controller;

use app\admin\logic\UserLogic;
use hyperjiang\BankCard;
use app\index\controller\Wecpay;
/**
 * 用户控制器
 */
class User extends Basis
{

    public function __construct()
    {
        parent::__construct();
    }
    //详情 登录
    public function detail()
    {
        //$this->e_msg('调用接口');
        $data = $this->user;
        $this->s_msg(null, $data);
    }
    //下级
    public function directOld()
    {
        $list = model("User")->where('us_pid', $this->user['id'])->select();
        foreach ($list as $k => $v) {
            $list[$k]['direct_num'] = model('User')->where('us_pid',$v['id'])->count();
        }
        $this->s_msg(null, $list);
    }
    public function direct()
    {
        $u_model = model('User');
        //直推
        //$map1[] = ['gave_status','>',1];
        $map1[] = ['us_pid','=',$this->user['id']];
        $data['direct_normal_num'] = $u_model->where($map1)->where('us_level',0)->count();
        $data['direct_vip_num'] = $u_model->where($map1)->where('us_level',1)->count();
        //所有的
        //$map2[] = ['gave_status','>',1];
        $map2[] = ['us_path' , 'like' , '%' . $this->user['us_path'] . ',' . $this->user['id'] . '%'];
        $data['all_normal_num'] = $u_model->where($map2)->where('us_level',0)->count();
        $data['all_vip_num'] = $u_model->where($map2)->where('us_level',1)->count();
        $data['list'] = $u_model->where($map1)->select();
        $data['us_nickname'] = $this->user['us_nickname'];
        $this->s_msg(null, $data);
    }

    /**
     * 修改用户
     * author fengkl
     * time 2018年5月16日 18:31:58
     * @return mixed
     */
    public function edit()
    {       
        if (is_post()) {
            $data = input('post.');
            $data['id'] = $this->user['id'];
            $userlogic = new UserLogic;
            $rel = $userlogic->selfedit($data);            
            $this->msg($rel);
        }
    }
    //支付300元成为vip
    //2018年6月25日 19:00:36
    //fkl
    public function beVip(){       
        $userlogic = new UserLogic;
        //把提交的收货人信息保存到数据库
        $data = input('post.');
        $map['id'] = $this->user['id'];
        //把信息更新到数据库
        $bevip_status = model('User')->updateInfo($map,$data);
        //发起微信支付
        $topay = new Wecpay;
        $pay_msg['or_id'] = 'yxg' . date('YmdHis') . rand(100, 999);
        $pay_msg['type'] = 1;
        $pay_msg['money'] = 300;
        $pay_msg['openid'] = $this->user['us_openid'];
        $pay_msg['us_id'] = $this->user['id'];
        //halt($pay_msg);
        $pay_rel = $topay->index($pay_msg);
    }
    //成为vip选择套餐
    //fkl
    //2018年7月13日 16:42:32
    public function plan(){
        $plan = array(
            ['name' => 'plan1','value' => cache('setting')['plan1']],
            ['name' => 'plan2','value' => cache('setting')['plan2']],
            ['name' => 'plan3','value' => cache('setting')['plan3']],
        );
        $this->s_msg(null,$plan);
    }
    
    //详情 登录
    public function tixian()
    {
        //$this->e_msg('调用接口');
        $data = $this->user['us_shop_bi'];
        $this->s_msg(null, $data);
    }
    // 添加提现
    public function dotixian()
    {
        $data = input('post.');
        $validate = validate('Verify');
        $rel = array();
        $rst = $validate->scene('tixian')->check($data);
        //var_dump($rst);exit;
        if (!$rst) {
            $this->e_msg($validate->getError());
        }
        if($data['get_type'] == 1){
            $need_bi = $data['tx_num'] * 1.01;
        }else{
            $need_bi = $data['tx_num'] * 1.03;
        }        
        if ($need_bi > $this->user['us_shop_bi']) {
            $this->e_msg('剩余消费币不足');
        }
        /*if ($data['tx_type' == 0]) {
            $data['tx_account'] = $this->user['us_bank_number'];
            $data['tx_addr'] = $this->user['us_bank_addr'];
            $data['tx_name'] = $this->user['us_bank_person'];
        } elseif ($data['tx_type'] == 1) {
            $data['tx_account'] = $this->user['alipay'];
            $data['tx_name'] = $this->user['us_real_name'];
        } else {
            $data['tx_account'] = $this->user['wechat'];
            $data['tx_name'] = $this->user['us_real_name'];
        }*/
        if($data['tx_type'] == 0){
            $data['tx_account'] = preg_replace('/[ ]/', '', $data['tx_account']);
            $bank_info = BankCard::info($data['tx_account']);
            //halt($data);
            if(empty($bank_info)){
                $this->e_msg('银行卡号非法');
            }
        }
        $data['us_id'] = $this->user['id'];
        $data['tx_apply_time'] = date('Y-m-d H:i:s');
        $rel = model("Tixian")->insertInfo($data);
        if ($rel){
            //model('Wallet')->tianjia($data['us_id'], $data['tx_num'], 1);
            $rell = model('User')->where('id',$this->user['id'])->setDec('us_shop_bi',$need_bi);
            if($rell){
                $this->s_msg('申请提现成功');
            }   
            $this->e_msg('申请提现失败');      
        } 
        $this->e_msg('申请提现失败');
        
    }
    //提现记录
    public function tx_record()
    {
        $this->map[] = array('us_id', '=', $this->user['id']);
        $tx_record = model("tixian")->chaxun($this->map, $this->order, $this->size);
        $this->s_msg(null, $tx_record);
    }
    //收益记录
    public function shop_record()
    {
        $this->map['us_id'] = $this->user['id'];
        //$this->map['wa_type'] = 1;
        $record = model("Wallet")->getList($this->map, $this->order, $this->size);
        $this->s_msg(null, $record);
    }

    //消费币记录
    public function bi_record()
    {
        $this->map['us_id'] = $this->user['id'];
        $this->map['wa_type'] = 1;
        $bi_record = model("Wallet")->getList($this->map, $this->order, $this->size);
        $this->s_msg(null, $bi_record);
    }
    //消费券记录
    public function quan_record()
    {
        $this->map['us_id'] = $this->user['id'];
        $this->map['wa_type'] = 2;
        $quan_record = model("Wallet")->getList($this->map, $this->order, $this->size);
        $this->s_msg(null, $quan_record);
    }

}
