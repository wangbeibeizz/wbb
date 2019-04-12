<?php
/**
 * Created by fengkl
 * User: Administrator
 * Date: 2018年5月16日 11:12:52
 * 
 */

namespace app\admin\logic;

use app\common\model\User;
use app\common\validate;
use think\Db;
use hyperjiang\BankCard;

class UserLogic 
{
    /**
     * 用户列表
     * author fengkl
     * time 2018年5月16日 10:56:09
     * @return mixed
     */
    public function getList($map,$order,$size)
    {
        $usermodel = new User();
        $info = $usermodel->getList($map,$order,$size);
        foreach ($info as $k => $v) {
            if($v['us_pid']){
                $v['ptel'] = model("User")->where('id', $v['us_pid'])->value('us_tel');
            }else{
                $v['ptel'] = '';
            }
        }
        //var_dump($info);exit;
        return $info;
    }

    /**
     * 用户逻辑删除
     * author fengkl
     * time 2018年5月16日 17:37:23
     * @return mixed
     */
    public function deleteUser($id)
    {        
        $map['id'] = $id;
        $data['us_delete_status'] = 0;
        $usermodel = new User();
        $rel = $usermodel->deleteUser($map,$data);       
        //var_dump($info);exit;
        return $rel;
    }

    /**
     * 用户修改
     * author fengkl
     * time 2018年5月17日 09:41:03
     * @return mixed
     */
    public function edit($data)
    {      
        //var_dump($data);
        $validate = validate('Verify');
        $rel = array();
        $rst = $validate->scene('editUser')->check($data);
        //var_dump($rst);exit;
        if (!$rst) {
            $rel['code'] = 0;
            $rel['msg'] = $validate->getError();
            return $rel;
        }
        $data['us_head_pic'] = str_replace("\\", "/", $data['us_head_pic']);
        //验证手机号是否已存在
        $where['id'] = $data['id'];
        $tel = model("User")->where($where)->value('us_tel');
        if($tel !== $data['us_tel']){
            $count = model("User")->where('us_tel', $data['us_tel'])->count();
            if ($count) {
                return [
                    'code' => 0,
                    'msg' => '该手机号已存在',
                ];
            }

        }      
        unset($data['id']);           
        if ($data['us_pwd'] != "") {
            $data['us_pwd'] = encrypt($data['us_pwd']);
        } else {
            unset($data['us_pwd']);
        }
        if (key_exists('us_safe_pwd', $data)) {
            $data['us_safe_pwd'] = encrypt($data['us_safe_pwd']);
        } 
        $usermodel = new User();
        $rell = $usermodel->editInfo($data,$where);
        //var_dump($rel);exit;
        if($rell){
            $rel['code'] = 1;
            $rel['msg'] = '修改成功！';
        }else{
            $rel['code'] = 0;
            $rel['msg'] = '您并没有作出修改！';
        }
        return $rel;
    }
    /**
     * 用户自己修改
     * author fengkl
     * time 2018年6月12日 12:54:50
     * @return mixed
     */
    public function selfedit($data)
    {      
        //var_dump($data);
        $validate = validate('Verify');
        $rel = array();
        $rst = $validate->scene('selfeditUser')->check($data);
        //var_dump($rst);exit;
        if (!$rst) {
            $rel['code'] = 0;
            $rel['msg'] = $validate->getError();
            return $rel;
        }
        if(empty($data['us_head_pic'])){
            unset($data['us_head_pic']);
        }
        $data['us_head_pic'] = str_replace("\\", "/", $data['us_head_pic']);
        //验证手机号是否已存在
        $where['id'] = $data['id']; 
        $data['us_bank_number'] = preg_replace('/[ ]/', '', $data['us_bank_number']);
        $bank_info = BankCard::info($data['us_bank_number']);
        //halt($data);
        if(empty($bank_info)){
            $rel['code'] = 0;
            $rel['msg'] = '银行卡号非法';
            return $rel;
        }  
        if($data['us_bank'] != $bank_info['bankName']){
            $rel['code'] = 0;
            $rel['msg'] = '银行卡号和银行不匹配';
            return $rel;
        }   
        unset($data['id']);           
        $usermodel = new User();
        $rell = $usermodel->editInfo($data,$where);
        //var_dump($rel);exit;
        if($rell){
            $rel['code'] = 1;
            $rel['msg'] = '修改成功！';
        }else{
            $rel['code'] = 0;
            $rel['msg'] = '您并没有作出修改！';
        }
        return $rel;
    }

    /**
     * 用户添加
     * author fengkl
     * time 2018年5月19日 14:50:51
     * @return mixed
     */
    public function addUser($data)
    {
        //var_dump($data);exit();
        $validate = validate('Verify');
        $rel = array();
        $rst = $validate->scene('addUser')->check($data);
        if (!$rst) {
            $rel['code'] = 0;
            $rel['msg'] = $validate->getError();
            return $rel;
        }
        if(array_key_exists('us_head_pic', $data) && $data['us_head_pic']){           
            $data['us_head_pic'] = str_replace("\\", "/", $data['us_head_pic']);                  
        }else{
            $data['us_head_pic'] = "/static/admin/img/head1.jpg";
        }
        if(array_key_exists('us_head_pic', $data)){
            $data['us_head_pic'] = str_replace("\\", "/", $data['us_head_pic']);
        }        
        //验证推荐人是否存在
        if($data['ptel']){
            $pinfo = model("User")->where('us_tel', $data['ptel'])->find();
            if (count($pinfo)){
                $data['us_pid'] = $pinfo['id'];
                $data['us_path'] = $pinfo['us_path'].','.$pinfo['id'];
            }else{
                $rel['code'] = 0;
                $rel['msg'] = '您填写的推荐人不存在';
                return $rel;
            }
        }else{
            $data['us_pid'] = 0;
            $data['us_path'] = 0;
        }
        //验证手机号是否已存在
        $count = model("User")->where('us_tel', $data['us_tel'])->count();
        if ($count) {
            return [
                'code' => 0,
                'msg' => '该手机号已存在',
            ];
        }
        //工号生成
        $number = model("User")->order('id desc')->value('us_account');
        if ($number) {
            $bb = substr($number, -5);
            $cc = substr($number, 0, 3);
            $dd = $bb + 1;
            $new_number = $cc . $dd;
        } else {
            $new_number = 'yxg10001';
        }
        $data['us_account'] = $new_number;
        $data['us_add_time'] = date('Y-m-d H:i:s');
        $data['us_pwd'] = encrypt($data['us_pwd']);
        if (key_exists('us_safe_pwd', $data)) {
            $data['us_safe_pwd'] = encrypt($data['us_safe_pwd']);
        }
        //操作      
        $rell = model('User')->addUser($data);
        if($rell){
            $rel['code'] = 1;
            $rel['msg'] = '添加成功！';
            $rel['us_id'] = $rell;
        }else{
            $rel['code'] = 0;
            $rel['msg'] = '添加失败！';
        }
        return $rel;
    }
    
    //用户支付300元成为会员时发放奖励
    //2018年6月8日 11:38:35
    //fkl
    public function gaveReferAward($p_id){     
        if($p_id){
            $first_where['id'] = $p_id;
            $vip1 = model('User')->where($first_where)->find();
            if($vip1['us_level'] = 1){
                $first_rel = model('User')->where($first_where)->setInc('us_shop_bi',90);
                if(!$first_rel){
                    $this->e_msg('直推奖发放失败');
                }
                $wallet1['us_id'] = $p_id;
                $wallet1['wa_num'] = 90;
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
                    $second_rel = model('User')->where($second_where)->setInc('us_shop_bi',30);
                    if(!$second_rel){
                        return '二推奖发放失败';
                    }
                    $wallet2['us_id'] = $vip1['us_pid'];
                    $wallet2['wa_num'] = 30;
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
                        $third_rel = model('User')->where($third_where)->setInc('us_shop_bi',10);
                        if(!$third_rel){
                           return '三推奖发放失败';
                        }
                        $wallet3['us_id'] = $vip2['us_pid'];
                        $wallet3['wa_num'] = 10;
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

}