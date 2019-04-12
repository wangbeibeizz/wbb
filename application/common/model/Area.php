<?php
namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 购物车表
 * fkl
 * 2018年6月4日 10:47:35
 */
class Area extends Model
{
    /**
     * 查询列表
     */
    public function getOne($id)
    {
        return $this->get($id);
    }

}
