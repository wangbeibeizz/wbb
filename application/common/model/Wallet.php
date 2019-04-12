<?php
namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 *
 */
class Wallet extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    /**
     * fkl
     * 2018年5月19日 15:23:18
     * 添加用户
     */
    public function addInfo($data) {
        return  $this->insertGetId($data);
    }
    //详情
    public function detail($where, $field = "*")
    {
        return $this->where($where)->field($field)->find();
    }
    //查询
    public function getList($map, $order, $size, $field = "*")
    {
        $list = $this->where($map)->order($order)->field($field)->paginate($size);
        return $list;
    }
    /**
     * 添加
     * @param  [array] $data [description]
     * @return [bool]       [description]
     */
    public function tianjia($uid, $jine, $type, $note = "")
    {
        $type_text = array(
            1 => '佣金提现',
        );
        $note = array(
            1 => '佣金提现',
        );
        $array = array(
            'us_id' => $uid,
            'wa_num' => $jine,
            'wa_type' => $type,
            'wa_type_text' => $type_text[$type],
            'wa_note' => $note[$type],
            'wa_add_time' => date('Y-m-d H:i:s'),
        );
        $rel = $this->insertGetId($array);
        if ($rel) {
            if (in_array($type, array(1))) {
                model('User')->where('id', $uid)->setDec('us_msc', $jine);
            }else{
                model('User')->where('id', $uid)->setInc('wallet', $jine);
            }
        }
        return $rel;
    }
    /**
     * 修改
     * @param  [array] $data  [数据]
     * @param  [array] $where [条件]
     * @return [bool]
     */
    public function xiugai($data, $where)
    {
        return $this->save($data, $where);
    }

    //用户账号
    public function getUsTextAttr($value, $data)
    {
        if ($data['us_id'] == "") {
            return '';
        }
        $name = model('User')->where('id', $data['us_id'])->value('us_account');
        return $name;
    }
    //真实姓名
    public function getUsNameAttr($value, $data)
    {
        if ($data['us_id'] == "") {
            return '';
        }
        $name = model('User')->where('id', $data['us_id'])->value('us_real_name');
        return $name;
    }
    public function getWaTypeTextAttr($value, $data) {
        $array = [
            1 => '消费币',
            2 => '消费券',
        ];
        return $array[$data['wa_type']];
    }
}
