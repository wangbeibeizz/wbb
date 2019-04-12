<?php
/**
 * Created by fengkl
 * User: Administrator
 * Date: 2018年5月25日 10:46:51
 * 
 */

namespace app\common\model;

use think\Model;

class Order extends Model
{

    public function getList($map, $order, $size, $field = "*")
    {
        return $this
            ->alias('a')
            ->join('user b','a.us_id = b.id','LEFT')
            ->join('store c','a.st_id = c.id','LEFT')
            ->field($field)
            ->where($map)
            ->order($order)
            ->paginate($size,false,$config = ['query'=>request()->param()]);
    }

    /**
     * fkl
     * 2018年5月21日 16:28:09
     * 关联查询一条数据
     */
    public function getOne($map,$field )
    {
        return $this
            ->alias('a')
            ->join('order_detail b', 'a.id = b.or_id' , 'LEFT')
            ->join('user_addr c', 'a.addr_id = c.id', 'LEFT')
            ->join('store d', 'b.st_id = d.id', 'LEFT')
            ->field($field)
            ->where($map)
            ->find();
    }
    public function getOneOrder($map,$field )
    {
        return $this
            ->alias('a')
            ->join('order_detail b', 'a.id = b.or_id' , 'LEFT')
            ->join('user_addr c', 'a.addr_id = c.id', 'LEFT')
            ->join('store d', 'b.st_id = d.id', 'LEFT')
            ->field($field)
            ->where($map)
            ->select();
    }

    /**
     * fkl
     * 2018年5月16日 15:40:52
     * 获取状态
     */
    public function getStatusTextAttr($value, $data) {
		$array = [
			0 => '待支付',
			1 => '待发货',
            2 => '待收货',
            3 => '已收货'
		];
		return $array[$data['or_status']];
	}

	/**
     * fkl
     * 2018年5月21日 16:07:54
     * 更新
     */
    public function updateInfo($map,$data)
    {
        return $this->where($map)->update($data);        
    }

    /**
     * fkl
     * 2018年5月17日 09:23:40
     * 根据id获取单条信息
     */
    public function getInfo($id)
    {
        return $this->get($id);        
    }


    /**
     * fkl
     * 2018年5月17日 09:57:58
     * 根据id保存
     */
    public function editInfo($data, $where) {
    	//var_dump($data);exit();
		//return $this->where($where)->update($data);
		return $this->save($data,$where);
	}

    /**
     * fkl
     * 2018年5月21日 14:33:11
     * 添加
     */
    public function addInfo($data) {
        return  $this->insertGetId($data);
    }

    /**
     * fkl
     * 2018年6月1日 17:59:41
     * id 订单id
     * 获取收货地址
     */
    public function getAddr($id) {
        $where['a.id'] = $id;
        return  $this
        ->alias('a')
        ->join('user_addr b','a.addr_id = b.id', 'LEFT')
        ->field('b.*')
        ->where($where)
        ->find();
    }
}