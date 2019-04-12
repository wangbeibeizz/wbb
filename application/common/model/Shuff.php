<?php
namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 *充值
 */
class Shuff extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getPIdTextAttr($value, $data)
    {
        return model('product')->where('id', $data['p_id'])->value('pd_name');
    }
    public function editShuff($data, $where)
    {
        //var_dump($data);     
        $rel = $this->where($where)->update($data);
        if ($rel) {
            return [
                'code' => 1,
                'msg' => '修改成功',
                'data' => $rel,
            ];
        } else {
            return [
                'code' => 0,
                'msg' => '您并没有做出修改',
            ];
        }
    }

}
