<?php
/**
 * Created by fengkl
 * User: Administrator
 * Date: 2018年5月21日 18:03:56
 * 
 */

namespace app\admin\logic;

use app\common\model\Store;
use app\common\model\Product;
use app\common\validate;
use think\Db;

class ProductLogic 
{
    /**
     * 门店列表
     * author fengkl
     * time 2018年5月21日 18:04:09
     * @return mixed
     */
    public function getList($map,$order,$size)
    {
        $pdmodel = new Product();
        $field = 'a.*, b.ca_name, b.p_id';
        $info = $pdmodel->getList($map,$order,$size,$field);
        /*foreach ($info as $k => $v) {
            $v['ptel'] = model("User")->where('id', $v['us_pid'])->value('us_tel');
        }*/
        //var_dump($info);exit;
        foreach ($info as $k => $v) {
           $pic_info = explode(',', $v['pd_pic']);
           if(!$pic_info[0]){
                array_shift($pic_info);
            }
            $info[$k]['pd_pic'] = $pic_info;
        } 
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
        $pdmodel = new Product();
        $map['a.id'] = $id;
        $field = 'a.*,b.ca_name';
        $info = $pdmodel->getOne($map,$field);
        if($info['st_id']){
            $info['area_id'] = model('Store')->where('id',$info['st_id'])->value('area_id');
        }else{
            $info['area_id'] = 0;
        }
        
        $pics = explode(",", $info['pd_pic']);
        if(!$pics[0]){
            array_shift($pics);
        }
        $info['pd_pic'] = $pics;
        return $info;
    }


    /**
     * 商品添加和修改
     * author fengkl
     * time 2018年5月24日 15:48:18
     * @return mixed
     * code为1为修改
     */
    public function saveProduct($data,$code = '')
    {
        //var_dump($data);exit();
        $validate = validate('Verify');
        $rel = array();
        $srcs = array_column($data['pd_pic'], 'url');
        if($srcs){
            $data['pd_pic'] = implode(",", $srcs);
        }else{
            $aaa = $data['pd_pic'];
            $data['pd_pic'] = implode(",", $aaa);
        }      
        $data['pd_pic'] = str_replace("\\", "/", $data['pd_pic']);
        //var_dump($data['pd_pic']);
        $rst = $validate->scene('addProduct')->check($data);
        if (!$rst) {
            $rel['code'] = 0;
            $rel['msg'] = $validate->getError();
            return $rel;
        }    
        $data['pd_add_time'] = date('Y-m-d H:i:s');
        $cate_pid = model('cate')->where('id',$data['ca_id'])->value('p_id');
        if(!$cate_pid){
            $rel['code'] = 0;
            $rel['msg'] = '请不要选择商品大分类！';
            return $rel;
        }   
        if($code == 1){
            //修改操作              
            $map['id'] = $data['pd_id'];
            unset($data['pd_id']);   
            $rell = model('Product')->updateInfo($map,$data);
            if($rell){
                $rel['code'] = 1;
                $rel['msg'] = '修改成功！';
            }else{
                $rel['code'] = 0;
                $rel['msg'] = '您没有作出修改！';
            }
            return $rel;
        }
        
        // 编号
        /*$number = model("Store")->order('id desc')->value('st_num');
        if ($number) {
            $bb = substr($number, -5);
            $cc = substr($number, 0, 2);
            $dd = $bb + 1;
            $new_number = $cc . $dd;
        } else {
            $new_number = 'md10001';
        }
        $data['st_num'] = $new_number;*/
        //添加操作 
        //var_dump($data);exit();     
        $rell = model('Product')->addInfo($data);
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

}