<?php
namespace app\shop\controller;

use think\Controller;
use app\admin\logic\OrderLogic;
use app\common\model\Order as OrderModel;
use think\Db;

/**
 * 订单
 */
class Order extends Basis
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 订单列表
     * author fengkl
     * time 2018年5月29日 15:12:01
     * @return mixed
     */
    public function index()
    {    
        $map['st_id'] = $this->shop['id'];
        $rel[0][] = $this->getorder($map,'');
        $rel[0]['id'] = 0;
        $rel[1][] = $this->getorder($map,1);
        $rel[1]['id'] = 1;
        $rel[2][] = $this->getorder($map,2); 
        $rel[2]['id'] = 2;         
        $this->s_msg(null, $rel);
    }

    /**
     * 订单查询
     * author fengkl
     * time 2018年6月6日 11:29:41
     * @return mixed
     */
    public function getorder($map,$code)
    {
        if($code !== ''){
            $map['or_status'] = $code;
        }       
        $Order = new OrderLogic;  
        //halt($map);          
        $list = $Order->getList($map, $this->order, $this->size);      
        foreach ($list as $k => $v) {
                $total = 0;
                $list[$k]['detail'] = model('OrderDetail')->where('or_id',$v['id'])->select();
                foreach ($list[$k]['detail'] as $key => $value) {
                    $total += (float)$value['or_pd_num'] * (float)$value['or_pd_price'];
                    $list[$k]['total_money'] = $total;
                }                              
              }
        if(!$list){
            $this->e_msg('没有查询到订单');
        }      
        return $list;
    }


    /**
     * 订单详情
     * author fengkl
     * time 2018年6月4日 18:00:16
     * @return mixed
     */
    public function detail()
    {
        if (!input('get.or_id')){
            $this->e_msg('无法获取订单ID', 401);
        }
        $Order = new OrderLogic;
        $addr = $Order->getOne(input('get.or_id'));
        $this->s_msg(null, $addr);
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
        $addr = model('User_Addr')->get($orderdetail['addr_id']);
        $addr['addr_add'] = $addr['addr_addr'].$addr['addr_detail'];
        $this->s_msg(null, $addr);
    }

    /**
     * 发货
     * author fengkl
     * time 2018年5月29日 15:46:40
     * @return mixed
     */
    public function deliver()
    {
        if (is_post()) {
            $data = input('post.');
            $where['id'] = $data['or_id'];
            unset($data['or_id']);
            $data['or_status'] = 2;
            $rst = model('Order')->editInfo($data, $where);
            $or_detail = model('OrderDetail')->detail($where);
            foreach ($or_detail as $k => $v) {
                $pd_where['id'] = $v['pd_id'];
                $rel1 = model('Product')->where($pd_where)->setInc('pd_sales',$v['or_pd_num']);
                $rel2 = model('Product')->where($pd_where)->setDec('pd_inventory',$v['or_pd_num']);
                if(!$rel1 || !$rel2){
                    $this->e_msg('库存或销量更新失败');
                }
            }
            if ($rst) {
                $this->s_msg(null, $rst);
            }
            $this->e_msg('发货失败', 401);
        }
    }
    


}
