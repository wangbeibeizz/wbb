<?php
namespace app\index\controller;

use think\Controller;
use app\admin\logic\OrderLogic;
use app\admin\logic\ProductLogic;
use app\admin\logic\OrderDetailLogic;
use app\index\controller\Wecpay;

/**
 * 订单
 */
class Order extends Basis
//class Order extends Common
{

    public function __construct()
    {
        parent::__construct();
    }
    public function test(){
        $pay = new Wecpay;
        $aaa = $pay->index();
        halt($aaa);
    }
    /**
     * 用户订单列表
     * author fengkl
     * time 2018年6月12日 17:07:07
     * @return mixed
     */
    public function index()
    {    
        /*$map['a.us_id'] = $this->user['id'];
        $rel[0][] = $this->getorder($map,'');
        $rel[0]['id'] = 0;
        $rel[1][] = $this->getorder($map,0);
        $rel[1]['id'] = 1;
        $rel[2][] = $this->getorder($map,1); 
        $rel[2]['id'] = 2; 
        $rel[3][] = $this->getorder($map,2); 
        $rel[3]['id'] = 3;*/
        $map[] = ['a.us_id','=',$this->user['id']]; 
        if(input('post.or_status') !== ''){
            $map[] = ['or_status', '=', input('post.or_status')];
        }
        //halt($map);
        $rel = $this->getorder($map);       
        $this->s_msg(null, $rel);        
    }
    /**
     * 订单查询
     * author fengkl
     * time 2018年6月12日 17:06:58
     * @return mixed
     */
    public function getorder($map)
    {      
        $Order = new OrderLogic;            
        $list = $Order->getList($map, $this->order, $this->size);       
        foreach ($list as $k => $v) {
                $total = 0;
                $list[$k]['detail'] = model('OrderDetail')->where('or_id',$v['id'])->select();
                foreach ($list[$k]['detail'] as $key => $value) {
                    $total += (float)$value['or_pd_num'] * (float)$value['or_pd_price'];
                    $list[$k]['total_money'] = $total;
                    $pics = explode(",", $value['or_pd_pic']);
                    if(!$pics[0]){
                        array_shift($pics);
                    }
                    $list[$k]['detail'][$key]['or_pd_pic'] = $pics;
                }                              
              }
        if(!$list){
            $this->e_msg('没有查询到订单');
        }      
        return $list;
    }
    /*public function index()
    {
        $Order = new OrderLogic;      
        $us_id = $this->user['id'];
        $this->map['us_id'] = $us_id;
        if (is_numeric(input('get.or_status'))) {
            $this->map[] = ['or_status', '=', input('get.or_status')];
        }
        $list = $Order->getList($this->map, $this->order, $this->size); 
        foreach ($list as $k => $v) {
                $list[$k]['detail'] = model('OrderDetail')->where('or_id',$v['id'])->select();
              }
        if(!$list){
            $this->e_msg('没有查询到订单');
        }      
        $this->s_msg(null, $list);
    }*/

    /**
     * 订单详情
     * author fengkl
     * time 2018年6月5日 11:11:17
     * @return mixed
     */
    public function detail()
    {
        /*$Order = new OrderLogic;
        $list = $Order->getOneDetail(22);
        $this->s_msg(null, $list);*/
        if (!input('post.or_shop_id')){
            $this->e_msg('无法获取详情ID', 401);
        }
        $Order = new OrderLogic;
        $list = $Order->getOneDetail(input('post.or_shop_id'));

        $where['or_id'] = input('post.or_id');
        $or_detail = model('OrderDetail')->getInfo($where);
        if(!$or_detail){
            $this->e_msg('查询不到订单详情');
        }
        $total = 0;
        foreach ($or_detail as $kk => $vv) {          
            $total += (float)$vv['or_pd_num'] * (float)$vv['or_pd_price'];
        }
        foreach ($list as $k => $v) {
            $pics = explode(",", $v['or_pd_pic']);
            if(!$pics[0]){
                array_shift($pics);
            }
            $list[$k]['or_pd_pic'] = $pics;
            if(!$v['st_name']){
                $v['st_name'] = '平台店铺';
            }
            $list[$k]['all_money'] = $total;
            $list[$k]['area_id'] = model('Store')->where('id',$v['st_id'])->value('area_id');
        }
        $this->s_msg(null, $list);
    }
    /**
     * 订单详情
     * author fengkl
     * time 2018年6月5日 11:11:17
     * @return mixed
     */
    public function orderDetail()
    {
        if (!input('post.or_id')){
            $this->e_msg('无法获取订单ID', 401);
        }
        $Order = new OrderLogic;
        $list = $Order->getOne(input('post.or_id'));
        $total = 0;

        foreach ($list as $k => $v) {
            $total += (float)$v['or_pd_num'] * (float)$v['or_pd_price'];
            $pics = explode(",", $v['or_pd_pic']);
            if(!$pics[0]){
                array_shift($pics);
            }
            $list[$k]['or_pd_pic'] = $pics;
            if(!$v['st_name']){
                $v['st_name'] = '平台店铺';
            }
            $list[$k]['area_id'] = model('Store')->where('id',$v['st_id'])->value('area_id');           
        }
        $list[0]['all_money'] = $total;
        $this->s_msg(null, $list);
    }

    /**
     * 没有收获地址的订单添加收货地址
     * author fengkl
     * time 2018年6月19日 10:39:54
     * @return mixed
     */
    public function orderAddAddr(){
        if (!input('post.data.or_id')){
            $this->e_msg('无法获取订单ID', 401);
        }
        if (!input('post.data.addr_id')){
            $this->e_msg('无法获取收货地址', 401);
        }
        $datas['addr_id'] = input('post.data.addr_id');
        $rel = model('order')->where('id',input('post.data.or_id'))->update($datas);
        $this->s_msg(null,$rel);
        if($rel){
            $this->s_msg(null,$rel);
        }
        $this->e_msg('添加收货地址失败');
    }

    /**
     * 收货信息
     * author fengkl
     * time 2018年5月29日 15:46:01
     * @return mixed
     */
    public function getAddr()
    {
        if (!input('post.or_id')){
            $this->e_msg('无法获取订单ID', 401);
        }
        $orderdetail = model('Order')->getInfo(input('post.or_id'));
        $addr = model('UserAddr')->get($orderdetail['addr_id']);
        $addr['addr_add'] = $addr['addr_addr'].$addr['addr_detail'];
        $this->s_msg(null, $addr);
    }
    //点击购买补充订单详情页面
    //2018年6月5日 11:15:16
    //fkl
    public function buy(){
        if(!input('post.pd_id')){
            $this->e_msg('无法获取商品信息');
        }
        $pd_logic = new ProductLogic;       
        $info = $pd_logic->getOne(input('post.pd_id'));       
        $this->s_msg(null, $info);
    }
    /**
     * 商品详情
     * author fengkl
     * time 2018年5月31日 15:25:52
     * @return mixed
     */
    public function pddetail()
    {
        $id = input('post.pd_id');
        if(!$id){
            $this->e_msg('无法获取商品ID', 401);
        }       
        $info = model('product')
            ->alias('a')
            ->field('a.*, b.ca_name')
            ->join('cate b', 'a.ca_id = b.id','LEFT')
            ->where('a.id',$id)
            ->find();
        $pics = explode(",", $info['pd_pic']);
        if(!$pics[0]){
            array_shift($pics);
        }
        $info['pd_pic'] = $pics;
        
        $this->s_msg(null, $info);
        //var_dump($info['pd_pic']);exit();
    }
    //购物车结算生成订单
    //fkl
    //2018年6月13日 18:38:02
    public function cart_to_add($sub_info){
        $pd_logic = new ProductLogic;
        
            $order['us_id'] = $this->user['id'];
            $order['or_status'] = 0;
            $order['st_id'] = $sub_info[0]['st_id'];
            $order['or_add_time'] = date('Y-m-d H:i:s');
            $order['or_num'] = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            //$order['addr_id'] = input('post.addr_id');
            $or_id = model('order')->addInfo($order);
            if(!$or_id){
                $this->e_msg('订单添加失败');
            }
            $order_d['or_id'] = $or_id;
            foreach ($sub_info as $key => $value) {
                $info = $pd_logic->getOne($value['pd_id']);
                $order_d['st_id'] = $sub_info[0]['st_id'];
                $order_d['ca_id'] = $info['ca_id'];
                $order_d['pd_id'] = $info['id'];
                $order_d['or_pd_name'] = $info['pd_name'];
                $order_d['or_pd_pic'] = $info['pd_pic'];
                $order_d['or_pd_price'] = $info['pd_price'];
                $order_d['or_pd_num'] = $value['pd_num'];
                $order_d['or_pd_type'] = $info['pd_type'];
                $order_d['or_pd_spec'] = $info['pd_spec'];
                $order_d['or_pd_content'] = $info['pd_content'];

                $rel = model('OrderDetail')->addInfo($order_d);
                if(!$rel){
                    $this->e_msg('详情添加失败');
                }
            }
            //返回生成的订单
            return $or_id;
        
    }
    //商品直接购买时，生成订单，返回订单号
    //2018年6月5日 16:11:21
    //fkl
    public function add(){
        if(!input('post.data.pd_id')){
            $this->e_msg('无法获取商品信息');
        }
        if(!input('post.data.pd_num')){
            $this->e_msg('无法获取商品数量');
        }
        if(!input('post.data.addr_id')){
            $this->e_msg('无法获取收货地址');
        }
        $pd_logic = new ProductLogic;       
        $info = $pd_logic->getOne(input('post.data.pd_id'));
        $order = array();
        $order_d = array();
        $order['us_id'] = $this->user['id'];
        $order['or_status'] = 0;
        $order['or_add_time'] = date('Y-m-d H:i:s');
        if(input('post.data.st_id')){
            $order['st_id'] = input('post.data.st_id');
        }else{
            $order['st_id'] = $info['st_id'];
        }       
        $order['or_num'] = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $order['addr_id'] = input('post.data.addr_id');
        $or_id = model('order')->addInfo($order);
        if(!$or_id){
            $this->e_msg('订单添加失败');
        }
        $order_d['or_id'] = $or_id;       
        $order_d['st_id'] = $order['st_id'];
        $order_d['ca_id'] = $info['ca_id'];
        $order_d['pd_id'] = $info['id'];
        $order_d['or_pd_name'] = $info['pd_name'];
        $order_d['or_pd_pic'] = implode(',', $info['pd_pic']);
        $order_d['or_pd_price'] = $info['pd_price'];
        $order_d['or_pd_num'] = input('post.data.pd_num');
        $order_d['or_pd_type'] = $info['pd_type'];
        $order_d['or_pd_spec'] = $info['pd_spec'];
        $order_d['or_pd_content'] = $info['pd_content'];
        $rel = model('OrderDetail')->addInfo($order_d);
        if(!$rel){
            $this->e_msg('详情添加失败');
        }
        $this->s_msg(null,$or_id);

    }
    //根据订单id，查询折扣，付款信息等等
    //2018年6月25日 15:31:03
    //fkl
    public function getOrderPay($or_id){
        $where['or_id'] = $or_id;
        $or_detail = model('OrderDetail')->getInfo($where);
        if(!$or_detail){
            $this->e_msg('查询不到订单详情');
        }
        $total = 0;
        foreach ($or_detail as $k => $v) {          
            $total += (float)$v['or_pd_num'] * (float)$v['or_pd_price'];
        }
        $quan = $this->user['us_shop_quan'];
        $bi = $this->user['us_shop_bi'];
        $or_data['total'] = $total;
        $or_data['discount'] = 0.1 * $total;
        $map['id'] = $this->user['id'];
        //价格10%用消费券抵扣，判断是否有足够消费券
        if($or_data['discount'] < $quan){
            $or_data['rel_pay'] = 0.9 * $total;           
            $or_data['qdata']['us_shop_quan'] = $quan - $or_data['discount'];
            $or_data['wallet1']['wa_num'] = '-'.$or_data['discount'];
        }else{
            $or_data['rel_pay'] = $total - $quan;           
            $or_data['qdata']['us_shop_quan'] = 0;
            $or_data['wallet1']['wa_num'] = '-'.$quan;            
        }
        return $or_data;
    }
    //消费币购买
    //2018年6月25日 15:31:21
    //fkl
    public function payByBi(){
        if(!input('post.or_id')){
            $this->e_msg('无法获取订单ID');
        }
        $or_id = input('post.or_id');
        $or_status = model('order')->where('id',$or_id)->value('or_status');
        if($or_status > 0){
            $this->e_msg('请勿重复支付');
        }
        $or_data = $this->getOrderPay($or_id);
        $rel_pay = $or_data['rel_pay'];
        $bi = $this->user['us_shop_bi'];
        $map['id'] = $this->user['id'];
        //判断消费币是否足够支付
        if($rel_pay > $bi){
            $this->e_msg('消费币不足，请选择微信支付');
        }
        $data['us_shop_bi'] = $bi - $rel_pay;
        $rell = model('User')->updateInfo($map,$data);
        if(!$rell){
            $this->e_msg('消费币购买失败');
        }
        $wallet2['us_id'] = $this->user['id'];
        $wallet2['wa_num'] = $rel_pay;
        $wallet2['wa_type'] = 2;
        $wallet2['wa_note'] = '消费币购买商品';
        $wallet2['add_time'] = date('Y-m-d H:i:s');
        $wa2_rel = model('Wallet')->addInfo($wallet2);
        if(!$wa2_rel){
            $this->e_msg('消费币购买商品记录添加失败');
        }
        //$this->s_msg(null,'消费币支付成功');
        //购买成功，发放奖励
        $this->payed($or_id);
    }

    //订单微信支付
    public function pay()
    {
        //根据订单id计算价格
        if(!input('post.or_id')){
            $this->e_msg('无法获取订单ID');
        }
        $or_id = input('post.or_id');
        $or_status = model('order')->where('id',$or_id)->value('or_status');
        if($or_status > 0){
            $this->e_msg('请勿重复支付');
        }
        $or_data = $this->getOrderPay($or_id);
        $rel_pay = $or_data['rel_pay'];
        $bi = $this->user['us_shop_bi'];
        //halt($rel_pay);
        //支付$rel_pay
        //微信支付
        $topay = new Wecpay;
        $pay_msg['or_id'] = $or_id;
        $pay_msg['type'] = 3;
        $pay_msg['money'] = $rel_pay;
        $pay_msg['openid'] = $this->user['us_openid'];
        $pay_msg['us_id'] = $this->user['id'];
        //halt($pay_msg);
        $pay_rel = $topay->index($pay_msg);
        //halt($pay_rel);
    }

    //支付后是否成功
    //成功1，失败2
    //2018年6月25日 13:00:08
    //fkl
    public function payed($or_id){
        /*if(!input('post.or_id')){
            $this->e_msg('无法获取订单ID');
        }       
        if(!input('post.code')){
            $this->e_msg('支付成功code错误');
        }
        $or_id = input('post.or_id');*/
        $or_status['or_status'] = 1;
        $or_status_rel = model('order')->where('id',$or_id)->update($or_status);
        //dump($or_status_rel);
        if(!$or_status_rel){
            $this->e_msg('订单处理失败，请联系商家');
        }
        $map['id'] = $this->user['id'];
        $or_data = $this->getOrderPay($or_id);
        $qdata = $or_data['qdata'];
        $rel = model('User')->updateInfo($map,$qdata);
        $quan = $this->user['us_shop_quan'];
        if($quan != 0 && !$rel){
            $this->e_msg('消费券抵用失败');
        }
        $wallet1['wa_num'] = $or_data['wallet1']['wa_num'];
        $wallet1['us_id'] = $this->user['id'];
        $wallet1['wa_type'] = 4;
        $wallet1['wa_note'] = '购买商品消费券抵用';
        $wallet1['add_time'] = date('Y-m-d H:i:s');
        $wa1_rel = model('Wallet')->addInfo($wallet1);
        if(!$wa1_rel){
            $this->e_msg('购买商品消费券抵用记录添加失败');
        }
        if(input('post.code') == 1){
            $wechat_number = session('wechat_number');
            session('wechat_number',null);
            //添加微信支付记录
            model("PayWechat")->tianjia($this->user['id'], $or_data['rel_pay'], $or_id, $wechat_number);
        }      
        //如果购买成功，三代以内推荐人获得消费币奖励
        $total = $or_data['total'];
        $this->gaveaward($map['id'],$total);
        //购买成功，门店和区域商获得消费币
        $this->gavefee($or_id);
        $this->result($data, 1, '购买成功', 'json');
        $this->s_msg(null,'购买成功');

    }

    //根据购买者id和消费金额，发放推荐奖励,发放至第三代
    //2018年6月6日 15:30:25
    //fkl
    public function gaveaward($us_id,$total){
        $map['id'] = $us_id;
        $p_id = model('User')->where($map)->value('us_pid');      
        if($p_id){
            $first_where['id'] = $p_id;
            $vip1 = model('User')->where($first_where)->find();
            if($vip1['us_level'] = 1){
                $first_rel = model('User')->where($first_where)->setInc('us_shop_bi',$total * 0.1);
                if(!$first_rel){
                    $this->e_msg('直推奖发放失败');
                }
                $wallet1['us_id'] = $p_id;
                $wallet1['wa_num'] = $total * 0.1;
                $wallet1['wa_type'] = 1;
                $wallet1['wa_note'] = '直推奖发放';
                $wallet1['add_time'] = date('Y-m-d H:i:s');
                $wa1_rel = model('Wallet')->addInfo($wallet1);
                if(!$wa1_rel){
                    $this->e_msg('直推奖发放记录添加失败');
                }
            }          
            //halt($first_rel);
            if($vip1['us_pid']){
                $second_where['id'] = $vip1['us_pid'];
                $vip2 = model('User')->where($second_where)->find();
                if($vip2['us_level'] = 1){
                    $second_rel = model('User')->where($second_where)->setInc('us_shop_bi',$total * 0.04);
                    if(!$second_rel){
                        $this->e_msg('二推奖发放失败');
                    }
                    $wallet2['us_id'] = $vip1['us_pid'];
                    $wallet2['wa_num'] = $total * 0.04;
                    $wallet2['wa_type'] = 1;
                    $wallet2['wa_note'] = '二推奖发放';
                    $wallet2['add_time'] = date('Y-m-d H:i:s');
                    $wa2_rel = model('Wallet')->addInfo($wallet2);
                    if(!$wa2_rel){
                        $this->e_msg('二推奖发放记录添加失败');
                    }
                }               
                if($vip2['us_pid']){
                    $third_where['id'] = $vip2['us_pid'];
                    $vip3 = model('User')->where($third_where)->find();
                    if($vip3['us_level'] = 1){
                        $third_rel = model('User')->where($third_where)->setInc('us_shop_bi',$total * 0.02);
                        if(!$third_rel){
                            $this->e_msg('三推奖发放失败');
                        }
                        $wallet3['us_id'] = $vip2['us_pid'];
                        $wallet3['wa_num'] = $total * 0.02;
                        $wallet3['wa_type'] = 1;
                        $wallet3['wa_note'] = '三推奖发放';
                        $wallet3['add_time'] = date('Y-m-d H:i:s');
                        $wa3_rel = model('Wallet')->addInfo($wallet3);
                        if(!$wa3_rel){
                            $this->e_msg('三推奖发放记录添加失败');
                        }
                    }                   
                }
            }

        }

    }
    //根据已支付订单id，查询订单中商品所在店铺和区域，发放提成
    //2018年6月7日 16:39:59
    //fkl
    public function gavefee($or_id){
        $map['or_id'] = $or_id;
        //自营店提成比例award
        $award = cache('setting')['award'];
        $or_detail = model('OrderDetail')->where($map)->select();
        foreach ($or_detail as $k => $v) {
            $total = $v['or_pd_price'] * $v['or_pd_num'];
            $shop = model('Store')->getInfo($v['st_id']);
            //halt($shop);
            if($shop['area_id'] == 1){
                $rel = model('User')->where('id',$shop['us_id'])->setInc('us_shop_bi',$total*$award);
                $rel_store = model('Store')->where('id',$v['st_id'])->setInc('st_money',$total*$award);
                if(!$rel){
                    $this->e_msg('自营店铺提成发放失败');
                }
                if(!$rel_store){
                    $this->e_msg('自营店销售额添加失败');
                }
                $wallet1['us_id'] = $shop['us_id'];
                $wallet1['wa_num'] = $total*$award;
                $wallet1['wa_type'] = 5;
                $wallet1['wa_note'] = '自营店铺销售额发放';
                $wallet1['add_time'] = date('Y-m-d H:i:s');
                $wa1_rel = model('Wallet')->addInfo($wallet1);
                if(!$wa1_rel){
                    $this->e_msg('自营店铺销售额发放记录添加失败');
                }
            }else{
                $rel = model('User')->where('id',$shop['us_id'])->setInc('us_shop_bi',$total*0.1);
                if(!$rel){
                    $this->e_msg('店铺提成发放失败');
                }
                $wallet2['us_id'] = $shop['us_id'];
                $wallet2['wa_num'] = $total * 0.1;
                $wallet2['wa_type'] = 1;
                $wallet2['wa_note'] = '店铺提成发放';
                $wallet2['add_time'] = date('Y-m-d H:i:s');
                $wa2_rel = model('Wallet')->addInfo($wallet2);
                if(!$wa2_rel){
                    $this->e_msg('店铺提成发放记录添加失败');
                }
                $areashop = model('AreaStore')->getInfo($shop['area_id']);
                $rel2 = model('User')->where('id',$areashop['us_id'])->setInc('us_shop_bi',$total*0.08);
                if(!$rel){
                    $this->e_msg('区域商提成发放失败');
                }
                $wallet3['us_id'] = $areashop['us_id'];
                $wallet3['wa_num'] = $total * 0.08;
                $wallet3['wa_type'] = 1;
                $wallet3['wa_note'] = '区域商提成发放';
                $wallet3['add_time'] = date('Y-m-d H:i:s');
                $wa3_rel = model('Wallet')->addInfo($wallet3);
                if(!$wa3_rel){
                    $this->e_msg('区域商提成发放记录添加失败');
                }

            }           
        }
    }

    //订单确认收货
    //2018年6月13日 11:32:28
    //fkl
    public function receive(){
        if(!input('post.or_id')){
            $this->e_msg('无法获取订单ID');
        }
        $map['id'] = input('post.or_id');
        $data['or_status'] = 3;
        $rel = model('Order')->where($map)->update($data);
        if($rel){
            $this->s_msg(null,'确认收货成功');
        }else{
            $this->e_msg('确认收货失败');
        }

    }

}
