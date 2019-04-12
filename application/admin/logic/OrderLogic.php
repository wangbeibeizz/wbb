<?php
/**
 * Created by fengkl
 * User: Administrator
 * Date: 2018年5月25日 10:44:20
 * 
 */

namespace app\admin\logic;

use app\common\model\Store;
use app\common\model\Order;
use app\common\validate;
use think\Db;

class OrderLogic 
{
    /**
     * 订单列表
     * author fengkl
     * time 2018年5月25日 10:44:42
     * @return mixed
     */
    public function getList($map,$order,$size)
    {
        $ormodel = new Order();
        $field = 'a.*, b.us_nickname, b.us_tel, c.st_name, c.st_tel';
        $info = $ormodel->getList($map,$order,$size,$field);
        /*foreach ($info as $k => $v) {
            $v['ptel'] = model("User")->where('id', $v['us_pid'])->value('us_tel');
        }*/
        //var_dump($info);exit;
        return $info;
    }

    /**
     * 商品信息
     * author fengkl
     * time 2018年5月24日18:05:42
     * @return mixed
     */
    public function getOne($id)
    {
        $pdmodel = new Order();
        $map['a.id'] = $id;
        $field = 'b.or_pd_name, b.or_pd_num, b.or_pd_price, b.or_pd_type, b.or_pd_spec, b.st_id,b.or_pd_pic,b.or_pd_content, c.addr_addr, c.addr_detail, c.addr_tel, c.addr_receiver,d.st_name';
        $info = $pdmodel->getOneOrder($map,$field);
        return $info;
    }
    /**
     * 订单一个详情商品信息
     * author fengkl
     * time 2018年6月13日 10:10:45
     * @return mixed
     */
    public function getOneDetail($id)
    {
        $ormodel = new Order();
        $map['b.id'] = $id;
        $field = 'b.or_pd_name, b.or_pd_num, b.or_pd_price, b.or_pd_type, b.or_pd_spec, b.st_id,b.or_pd_pic,b.or_pd_content, c.addr_addr, c.addr_detail, c.addr_tel, c.addr_receiver,a.or_express,a.or_express_num,d.st_name';
        $info = $ormodel->getOne($map,$field);
        return $info;
    }


    /**
     * 商品添加和修改
     * author fengkl
     * time 2018年5月24日 15:48:18
     * @return mixed
     * code为1为修改
     */
    public function saveOrder($data,$code = '')
    {
        //var_dump($data);exit();
        $rel = array();     
        if($code == 1){
            //修改操作              
            $map['id'] = $data['id'];   
            $rell = model('Order')->updateInfo($map,$data);
            if($rell){
                $rel['code'] = 1;
                $rel['msg'] = '修改成功！';
            }else{
                $rel['code'] = 0;
                $rel['msg'] = '您没有作出修改！';
            }
            return $rel;
        }
        $data['or_add_time'] = date('Y-m-d H:i:s');
        //添加操作      
        $rell = model('Order')->addInfo($data);
        if($rell){
            $rel['code'] = 1;
            $rel['msg'] = '添加成功！';
        }else{
            $rel['code'] = 0;
            $rel['msg'] = '添加失败！';
        }
        return $rel;
    }

    /**
     * 分类添加
     * author fengkl
     * time 2018年5月23日 17:38:34
     * @return mixed
     * 
     */
    public function saveCate($data)
    {
        $validate = validate('Verify');
        $rel = array();
        $rst = $validate->scene('addCate')->check($data);
        if (!$rst) {
            $rel['code'] = 0;
            $rel['msg'] = $validate->getError();
            return $rel;
        }
        $data['ca_add_time'] = date('Y-m-d H:i:s');
        //添加操作      
        $rell = model('Cate')->addInfo($data);
        if($rell){
            $rel['code'] = 1;
            $rel['msg'] = '添加成功！';
        }else{
            $rel['code'] = 0;
            $rel['msg'] = '添加失败！';
        }
        return $rel;

    }

    /**
     * 订单添加
     * author fengkl
     * time 2018年6月5日 11:55:03
     * @return mixed
     * 
     */
    public function addInfo($data)
    {
        $validate = validate('Verify');
        $rel = array();
        $rst = $validate->scene('addCate')->check($data);
        if (!$rst) {
            $rel['code'] = 0;
            $rel['msg'] = $validate->getError();
            return $rel;
        }
        $data['ca_add_time'] = date('Y-m-d H:i:s');
        //添加操作      
        
    }

    /**
     * 判断订单发货时间是否超过规定日期
     * author fengkl
     * time 2018年6月12日 17:50:59
     * @return mixed
     * 
     */
    public function delay(){
        $order = new Order;
        $list = $order->select();
    }

}