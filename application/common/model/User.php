<?php
/**
 * Created by fengkl
 * User: Administrator
 * Date: 2018年5月16日 11:20:25
 *
 */

namespace app\common\model;

use think\Model;

class User extends Model
{
	/**
     * @param string $where
     * @return false|\PDOStatement|string|\think\Collection
     * fkl
     * 2018年5月16日 11:21:15
     * 查询列表
     */
    public function getList($map, $order, $size, $field = "*")
    {
        if(!$map)
        {
            $map='1=1';
        }
        return $this->where($map)->order($order)->field($field)->paginate($size,false,$config = ['query'=>request()->param()]);
        /*return $this
            ->alias('a')
            ->join('person b','a.pid = b.pid')
            ->join('person_group c','c.person_group_id = b.person_group_id')
            ->field($field)
            ->where($where)
            ->paginate($pageSize);*/
    }

    /**
     * fkl
     * 2018年5月16日 15:40:52
     * 获取状态
     */
    public function getUsStatusTextAttr($value, $data) {
		$array = [
			0 => '已禁用',
			1 => '正常',
		];
		return $array[$data['us_status']];
	}

	/**
     * fkl
     * 2018年5月16日 17:47:44
     * 逻辑删除
     */
    public function deleteUser($map,$data)
    {
        return $this->where($map)->update($data);        
    }

    /**
     * fkl
     * 2018年5月16日 17:47:44
     * 修改
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
     * 2018年5月19日 15:23:18
     * 添加用户
     */
    public function addUser($data) {
        return  $this->insertGetId($data);
    }

    //详情
    public function detail($where, $field = "*") {
        return $this->where($where)->field($field)->find();
    }
    public function getUsPidTextAttr($value, $data){
        $name = model('User')->where('id',$data['us_pid'])->value('us_tel');
        return $name;
    }
    public function getTakePlanTextAttr($value, $data){
        //默认套餐一
        $plan = $data['take_plan']?:'plan1';
        $name = cache('setting')[$plan];
        return $name;
    }


}