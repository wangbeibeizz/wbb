<?php
namespace app\index\controller;

use app\admin\logic\ProductLogic;
/**
 * 购物车控制器
 */
class Cart extends Basis {

	function __construct() {
		parent::__construct();
	}

	//2018年6月4日 11:42:06
    //购物车首页
    public function index()
    {       
        $where['us_id'] = $this->user['id'];
        $data = model("Cart")->getList($where);
        foreach ($data as $k => $v) {
            $pics = explode(",", $v['pd_pic']);
            if(!$pics[0]){
                array_shift($pics);
            }
            $data[$k]['area_id'] = model('Store')->where('id',$v['st_id'])->value('area_id');
            $data[$k]['pd_pic'] = $pics;
            $data[$k]['max_num'] = model('Product')->where('id',$v['pd_id'])->value('pd_inventory');
            }
        $this->s_msg(null, $data);
    }
    /*//删除商品
    //2018年6月13日 15:02:31
    //fkl
    public function delete(){
        if(!input('post.pd_id')){
            $this->e_msg('无法获取商品ID');
        }
        $where['us_id'] = $this->user['id'];
    }*/

    //2018年6月4日 11:54:51
    //商品信息显示
    public function detail()
    {
        if (input('post.cart_id')) {
            $where['id'] = input('post.cart_id');
            $data = model("Cart")->getOne($where);
            $this->s_msg(null, $data);
        }
        $this->e_msg('无法获取购物车ID');
    }

    //加入购物车
    public function add()
    {
        if (input('post.pd_id')) {
            $pd_id = input('post.pd_id');
            //halt(input('post.'));
            $us_id = $this->user['id'];           
            $map['pd_id'] = $pd_id;
            $map['us_id'] = $us_id;
            //halt($map);
            $rst = model('Cart')->getOne($map);
            if($rst){
                $result = model('Cart')->where('id',$rst['id'])->setInc('pd_num');
                if($result){
                    $this->s_msg('购物车商品数量+1',$result);
                }else{
                    $this->e_msg('添加失败'); 
                }
            }
            //halt($rst);    
            $goods = model('Product')->where('id',$pd_id)->find();
            $carts['us_id'] = $us_id;
            $carts['pd_id'] = $pd_id;
            $carts['pd_name'] = $goods['pd_name'];
            $carts['pd_price'] = $goods['pd_price'];
            $carts['pd_pic'] = $goods['pd_pic'];
            if(input('post.st_id')){
                $carts['st_id'] = input('post.st_id');
            }else{
                $carts['st_id'] = $goods['st_id'];
            }
            $carts['st_name'] = model('Store')->where('id',$carts['st_id'])->value('st_name');                     
            $carts['pd_content'] = $goods['pd_content'];
            $carts['pd_type'] = $goods['pd_type']?:'无';
            //halt($carts['pd_type']);
            $carts['pd_spec'] = $goods['pd_spec']?:'无';
            $rel = model("Cart")->addInfo($carts);
            //halt($rel);
            if($rel){
                $this->s_msg('添加成功', $rel);
            }
            $this->e_msg('添加失败');
        }
        $this->e_msg('无法获取商品ID');
    }
    /*//购物车结算界面
    //2018年6月14日 11:58:48
    public function closelist(){
        $data = input('post.');
        $ids = implode(',', $data['arr']);
        $map[] = ['id','in',$ids];
        $list = model('cart')->getList($map);
        $st_id = $list[0]['st_id'];
        foreach ($list as $k => $v) {
            if($st_id !== $v['st_id']){
                $this->e_msg('不同店铺商品请分批购买');
            }
        }
        if($list){
            $this->s_msg(null,$list);
        }
    }*/
    //购物车结算生成订单
    //2018年6月14日 11:58:48
    public function closelist(){
        $data = input('post.');
        $ids = implode(',', $data['arr']);
        $map[] = ['id','in',$ids];
        $list = model('cart')->getList($map);
        $st_id = $list[0]['st_id'];
        foreach ($list as $k => $v) {
            if($st_id !== $v['st_id']){
                $this->e_msg('不同店铺商品请分批购买');
            }
        }
        if($list){
            //$rel = action('order/cart_to_add',$list);
            $rel = $this->cart_to_add($list);
            if(!$rel){
                $this->e_msg('生成订单失败');
            }
            foreach ($data['arr'] as $key => $value) {
                //halt($data['arr']);
                $cart_info = model('cart')->get($value);
                if(!$cart_info){
                    $this->e_msg('数据不存在');
                }
                $rell = model('cart')->destroy($value);
                if(!$rell){
                    $this->e_msg('请联系网站管理员');
                }
            }
            $this->s_msg(null,$rel);
        }else{
            $this->e_msg('查询不到购物车信息');
        }
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
            $order_d['or_pd_pic'] = implode(',', $info['pd_pic']);
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

    //2018年6月4日 14:21:07
    //商品数量修改
    public function edit()
    {
        if (input('post.cart_id')) {
            $where['id'] = input('post.cart_id');
            $data['pd_num'] = input('post.pd_num');
            $rel = model("Cart")->updateInfo($data,$where);
            if($rel){
                $this->s_msg(null, $rel);
            }else{
                $this->e_msg('修改失败');
            }            
        }
        $this->e_msg('无法获取商品ID');
    }
    //2018年6月14日 15:33:59
    //购物车支付
    //fkl
    public function closecart1111(){
        $data = input('post.');
        foreach ($id_list as $k => $v) {
            $cart_info = model('cart')->where('id',$v['id'])->find();
            $id_list[$k]['st_id'] = $cart_info['st_id'];
            $id_list[$k]['pd_id'] = $cart_info['pd_id'];

            //订单生成后，删除购物车信息
            $info = model('cart')->get($v['id']);
            if(!$info){
                $this->e_msg('数据不存在');
            }
            $rel = model($key)->destroy($id);
            if(!$rel){
                $this->e_msg('请联系网站管理员');
            }            
        }
    }
    //2018年6月13日 17:00:22
    //结算
    //fkl
    //addr_id收货人地址id
    //id_list 数组：商品id和数量
    public function closecart111(){
        if(!input('post.id_list')){
            $this->e_msg('无法获取购物车ID');
        }
        foreach ($id_list as $k => $v) {
            $cart_info = model('cart')->where('id',$v['id'])->find();
            $id_list[$k]['st_id'] = $cart_info['st_id'];
            $id_list[$k]['pd_id'] = $cart_info['pd_id'];

            //订单生成后，删除购物车信息
            $info = model('cart')->get($v['id']);
            if(!$info){
                $this->e_msg('数据不存在');
            }
            $rel = model($key)->destroy($id);
            if(!$rel){
                $this->e_msg('请联系网站管理员');
            }            
        }
        action('order/cart_to_add',$id_list);
    }
    //根据店铺id对购物车分组
    public function array_group_by($arr, $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        return $grouped;
    }
	
}