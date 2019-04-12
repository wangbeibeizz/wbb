<?php
namespace app\shop\Controller;

/**
 * 用户控制器
 */
//fkl
class Shop extends Basis
{

    public function __construct()
    {
        parent::__construct();
    }
    //2018年5月29日 09:26:57
    //登录
    public function index()
    {
        $data = $this->shop;
        $area_m = model('area');
        $province = $area_m->where('area_id',$data['province'])->value('area_name');
        $city = $area_m->where('area_id',$data['city'])->value('area_name');
        $town = $area_m->where('area_id',$data['town'])->value('area_name');
        $data['st_all_addr'] = $province.$city.$town.$data['st_addr_detail'];
        $this->s_msg(null, $data);
    }

    //2018年6月5日 09:19:54
    //fkl
    //自营店个人信息
    public function mydetail()
    {
        $us_id = $this->shop['us_id'];
        $data = model('User')->getInfo($us_id);
        if($data){
            $this->s_msg(null, $data);
        }
        $this->e_msg('查询不到个人信息');
    }

    //2018年6月5日 09:27:43
    //fkl
    //自营店店铺信息
    public function shopdetail()
    {
        $st_id = $this->shop['id'];
        $data = model('Store')->getInfo($st_id);
        if($data){
            $this->s_msg(null, $data);
        }
        $this->e_msg('查询不到店铺信息');
    }
    //下级
    public function direct()
    {
        if (input('get.id')) {
            $list = model("User")->where('us_pid', input("get.id"))->select();
        } else {
            $list = model("User")->where('us_pid', $this->user['id'])->select();
        }
        $this->s_msg(null, $list);
    }
    //修改
    public function edit()
    {
        if (input('post.us_tel') != "") {
            $data['us_tel'] = input('post.us_tel');
        }
        if (input('post.uspwd') != "") {
            $data['us_pwd'] = encrypt(input('post.us_pwd'));
        }
        $rel = $this->xiugai($data, ['id' => $this->user['id']]);
        $this->msg($rel);
    }
    // 添加提现
    public function tixian()
    {
        $data = input('post.');
        if ($data['tx_num'] >= $this->user['us_msc']) {
            $this->e_msg('剩余佣金不足');
        }
        if ($data['tx_type' == 0]) {
            $data['tx_account'] = $this->user['us_bank_number'];
            $data['tx_addr'] = $this->user['us_bank_addr'];
            $data['tx_name'] = $this->user['us_bank_person'];
        } elseif ($data['tx_type'] == 1) {
            $data['tx_account'] = $this->user['alipay'];
            $data['tx_name'] = $this->user['us_real_name'];
        } else {
            $data['tx_account'] = $this->user['wechat'];
            $data['tx_name'] = $this->user['us_real_name'];
        }
        $data['us_id'] = $this->user['id'];
        $rel = model("Tixian")->tianjia($data);
        if ($rel) {
            model('Wallet')->tianjia($data['us_id'], $data['tx_num'], 1);
            $this->s_msg('添加成功');
        } else {
            $this->e_msg('添加失败');
        }
    }
    //提现记录
    public function tx_record()
    {
        $this->map[] = array('us_id', '=', $this->user['id']);
        $tx_record = model("tixian")->chaxun($this->map, $this->order, $this->size);
        $this->s_msg(null, $tx_record);
    }

    //佣金记录
    public function msc_record()
    {
        $this->map[] = array('us_id', '=', $this->user['id']);
        $this->map[] = array('type', 'in', array(1));
        $msc_record = model("Wallet")->chaxun($this->map, $this->order, $this->size);
        $this->s_msg(null, $msc_record);
    }
    //购物币记录
    public function wallet_record()
    {
        $this->map[] = array('us_id', '=', $this->user['id']);
        $this->map[] = array('type', 'in', array(2));
        $wallet_record = model("Wallet")->chaxun($this->map, $this->order, $this->size);
        $this->s_msg(null, $wallet_record);
    }

}
