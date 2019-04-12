<?php
namespace app\index\controller;

use wechat\TPWechat;
use think\facade\Config;
use app\admin\logic\UserLogic;
// use think\Log;   
/**
 * 微信公众号
 */
class Wenotify
{
    public $user;
    function __construct() {
        // $path = env('APP_PATH') . "public\\wecpay\\";
        require_once "wecpay/lib/WxPay.Api.php";
        require_once "wecpay/lib/WxPay.Notify.php";
        require_once "wecpay/lib/WxPay.Data.php";
        // require_once "wecpay/example/WxPay.JsApiPay.php";
        // require_once "wecpay/example/WxPay.NativePay.php";
        // require_once 'wecpay/example/log.php';
    }
    public function notify() {
        $notify = new \WxPayNotify();
        //验签
        $notify->Handle(false);
        //获取回调参数
        $xml = file_get_contents('php://input');
        //处理xml参数
        $data = $notify->FromXml($xml);
        //\think\facade\Log::write('支付回调日志记录'.json_encode($data));
        $succeed = ($notify->GetReturn_code() == 'SUCCESS') ? true : false;
        if (!$succeed){
            \think\facade\Log::write('订单' . $data['out_trade_no'] . '支付失败');
        }
        $pay_model = model("PayWechat");
        $status['status'] = 1;
        $where['or_id'] = $data['out_trade_no'];
        //$pay_model->where($where)->update($status);
        $pay_model->where($where)->setInc('status');
        $pay_record = $pay_model->where($where)->find();
        //如果是vip升级支付
        if($data['attach'] == 1 || $data['attach'] == 2){
            $this->vipUpdateStaus($pay_record['us_id']);
        }elseif($data['attach'] == 3){
            //订单支付
            $this->payed($data['out_trade_no'],$pay_record['us_id']);
        }

    }
    //支付成功后，更新vip的状态，发放奖励
    public function vipUpdateStaus($us_id){
        $userlogic = new UserLogic;
        $us_model = model('User');
        $us_msg = $us_model->where('id',$us_id)->find();
        $data['us_shop_quan'] = $us_msg['us_shop_quan'] + 500;
        $data['gave_status'] = 1;
        $data['us_level'] = 1;
        $map['id'] = $us_id;
        //把信息更新到数据库
        $bevip_status = $us_model->updateInfo($map,$data);
        $us_pid = $us_msg['us_pid'];
        $award_rel = $userlogic->gaveReferAward($us_pid);
    }
    //支付后是否成功
    //成功1，失败2
    //2018年6月25日 13:00:08
    //fkl
    public function payed($or_id,$us_id){       
        $or_status['or_status'] = 1;
        $or_status_rel = model('order')->where('id',$or_id)->update($or_status);
        //dump($or_status_rel);
        if(!$or_status_rel){
            return '订单处理失败，请联系商家';
        }
        $map['id'] = $us_id;
        $or_data = $this->getOrderPay($or_id,$us_id);
        $qdata = $or_data['qdata'];
        $rel = model('User')->updateInfo($map,$qdata);
        $wallet1['wa_num'] = $or_data['wallet1']['wa_num'];
        $wallet1['us_id'] = $us_id;
        $wallet1['wa_type'] = 2;
        $wallet1['wa_note'] = '购买商品消费券抵用';
        $wallet1['add_time'] = date('Y-m-d H:i:s');
        $wa1_rel = model('Wallet')->addInfo($wallet1);
        if(!$wa1_rel){
            return '购买商品消费券抵用记录添加失败';
        }      
        //如果购买成功，三代以内推荐人获得消费币奖励
        $total = $or_data['total'];
        $this->gaveaward($map['id'],$total);
        //购买成功，门店和区域商获得消费币
        $this->gavefee($or_id);
        return '购买成功';

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
                    return '直推奖发放失败';
                }
                $wallet1['us_id'] = $p_id;
                $wallet1['wa_num'] = $total * 0.1;
                $wallet1['wa_type'] = 1;
                $wallet1['wa_note'] = '直推奖发放';
                $wallet1['add_time'] = date('Y-m-d H:i:s');
                $wa1_rel = model('Wallet')->addInfo($wallet1);
                if(!$wa1_rel){
                    return '直推奖发放记录添加失败';
                }
            }          
            //halt($first_rel);
            if($vip1['us_pid']){
                $second_where['id'] = $vip1['us_pid'];
                $vip2 = model('User')->where($second_where)->find();
                if($vip2['us_level'] = 1){
                    $second_rel = model('User')->where($second_where)->setInc('us_shop_bi',$total * 0.04);
                    if(!$second_rel){
                        return '二推奖发放失败';
                    }
                    $wallet2['us_id'] = $vip1['us_pid'];
                    $wallet2['wa_num'] = $total * 0.04;
                    $wallet2['wa_type'] = 1;
                    $wallet2['wa_note'] = '二推奖发放';
                    $wallet2['add_time'] = date('Y-m-d H:i:s');
                    $wa2_rel = model('Wallet')->addInfo($wallet2);
                    if(!$wa2_rel){
                        return '二推奖发放记录添加失败';
                    }
                }               
                if($vip2['us_pid']){
                    $third_where['id'] = $vip2['us_pid'];
                    $vip3 = model('User')->where($third_where)->find();
                    if($vip3['us_level'] = 1){
                        $third_rel = model('User')->where($third_where)->setInc('us_shop_bi',$total * 0.02);
                        if(!$third_rel){
                            return '三推奖发放失败';
                        }
                        $wallet3['us_id'] = $vip2['us_pid'];
                        $wallet3['wa_num'] = $total * 0.02;
                        $wallet3['wa_type'] = 1;
                        $wallet3['wa_note'] = '三推奖发放';
                        $wallet3['add_time'] = date('Y-m-d H:i:s');
                        $wa3_rel = model('Wallet')->addInfo($wallet3);
                        if(!$wa3_rel){
                            return '三推奖发放记录添加失败';
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
                    return '自营店铺提成发放失败';
                }
                if(!$rel_store){
                    return '自营店销售额添加失败';
                }
                $wallet1['us_id'] = $shop['us_id'];
                $wallet1['wa_num'] = $total*$award;
                $wallet1['wa_type'] = 5;
                $wallet1['wa_note'] = '自营店铺销售额发放';
                $wallet1['add_time'] = date('Y-m-d H:i:s');
                $wa1_rel = model('Wallet')->addInfo($wallet1);
                if(!$wa1_rel){
                    return '自营店铺销售额发放记录添加失败';
                }
            }else{
                $rel = model('User')->where('id',$shop['us_id'])->setInc('us_shop_bi',$total*0.1);
                if(!$rel){
                    return '店铺提成发放失败';
                }
                $wallet2['us_id'] = $shop['us_id'];
                $wallet2['wa_num'] = $total * 0.1;
                $wallet2['wa_type'] = 1;
                $wallet2['wa_note'] = '店铺提成发放';
                $wallet2['add_time'] = date('Y-m-d H:i:s');
                $wa2_rel = model('Wallet')->addInfo($wallet2);
                if(!$wa2_rel){
                    return '店铺提成发放记录添加失败';
                }
                $areashop = model('AreaStore')->getInfo($shop['area_id']);
                $rel2 = model('User')->where('id',$areashop['us_id'])->setInc('us_shop_bi',$total*0.08);
                if(!$rel){
                    return '区域商提成发放失败';
                }
                $wallet3['us_id'] = $areashop['us_id'];
                $wallet3['wa_num'] = $total * 0.08;
                $wallet3['wa_type'] = 1;
                $wallet3['wa_note'] = '区域商提成发放';
                $wallet3['add_time'] = date('Y-m-d H:i:s');
                $wa3_rel = model('Wallet')->addInfo($wallet3);
                if(!$wa3_rel){
                    return '区域商提成发放记录添加失败';
                }

            }           
        }
    }
    //根据订单id，查询折扣，付款信息等等
    //2018年6月25日 15:31:03
    //fkl
    public function getOrderPay($or_id,$us_id){
        $where['or_id'] = $or_id;
        $or_detail = model('OrderDetail')->getInfo($where);
        $total = 0;
        foreach ($or_detail as $k => $v) {          
            $total += (float)$v['or_pd_num'] * (float)$v['or_pd_price'];
        }
        $us_model = model('User');
        $us_msg = $us_model->where('id',$us_id)->find();
        $quan = $us_msg['us_shop_quan'];
        $bi = $us_msg['us_shop_bi'];
        $or_data['total'] = $total;
        $or_data['discount'] = 0.1 * $total;
        $map['id'] = $us_id;
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

}
