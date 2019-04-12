<?php
/**
 * Created by fengkl
 * User: Administrator
 * Date: 2018年5月15日 18:36:26
 * 
 */

namespace app\admin\controller;

use app\admin\logic\UserLogic;
use app\common\model\User as UserModel;
use think\Db;
use think\Container;
use PHPExcel_IOFactory;
use PHPExcel;

class User extends Common
{
    /**
     * 用户列表
     * author fengkl
     * time 2018年5月16日 10:56:09
     * @return mixed
     */
    public function index()
    {
        $user=model('User');
        $map=[];
        $mapp=[];
        if (is_get()) {
            if (input('get.us_status')==1) {
                $map['us_type']=1;
            }
            if (input('get.us_status')=='0') {
                $map['us_type']=0;
            }
            if (input('get.keywords')!=null) {
                $map['us_tel']=input('get.keywords');
            }
            if (input('get.nickname')!=null) {
                // $mapp=[
                //     ['us_nickname','like','%'.input('get.nickname').'%']
                // ];
                $map['us_nickname']=input('get.nickname');
            }
        }
        $list=model('user')->where($map)->where($mapp)->order('us_creattime desc')->paginate(10,false,$config = ['query'=>request()->param()]);
        $count=count($list);
        foreach ($list as $key => $value) {
            $list[$key]['td_count'] = model('User')->where('us_ztstring','like',$value['us_ztstring'].','.$value['id'].'%')->count();
        }
        // halt($count);
        $this->assign('count',$count);
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function addsum()
    {
        if (is_post()) {
            $post=input('post.');
            // halt($post);
            if ($post['type']==1) {
                $data=model('user')->where('id',$post['id'])->update(['us_cash'=>$post['num']]);
            }
            if ($post['type']==2) {
                $data=model('user')->where('id',$post['id'])->update(['us_reg'=>$post['num']]);
            }
            if ($post['type']==3) {
                $list=model('user')->where('us_tel',$post['num'])->find();
                if ($list) {
                    $this->error('该手机号存在');
                }
                else{
                    $data=model('user')->where('id',$post['id'])->update(['us_tel'=>$post['num']]);
                }
            }
            if ($data) {
                $this->success('修改成功');
            }
            else
            {
                $this->error('请勿修改相同金额');
            }
        }
    }

    //我的推荐人信息
    public function tjdetail()
    {
        // $post =input('post.');
        // halt($post);
        $list=db('user')->where('id',input('id'))->find();
        $this->assign('list',$list);
        return $this->fetch();
    }
    //我的直推
    public function ztdetail()
    {
        // halt(input('id'));
        $list=db('user')->where('us_zhitui',input('id'))->order('us_creattime desc')->select();
        // halt($list);
        $this->assign('list',$list);
        return $this->fetch();
    }
    //我的信息
    public function dbdetail()
    {
        if (is_post()) {
            $post = input('post.');
            if ($post['us_zfpwd']=='') {
                $post['us_zfpwd']=model('user')->where('id',$post['id'])->value('us_zfpwd');
            }
            else{
                $post['us_zfpwd']=encrypt($post['us_zfpwd']);
            }
            if ($post['id_card']!=model('user')->where('id',$post['id'])->value('id_card')) {
                $list = model('user')->where('id_card',$post['id_card'])->find();
                if ($list) {
                    $this->error('该身份证号已被注册');
                }
            }
            $info = model('user')->where('id',$post['id'])->update($post);
            if ($info) {
                $this->success('修改成功');
            }else{
                $this->error('未修改');
            }
        }
        $list = model('user')->where('id',input('id'))->find();
        // halt($list);
        $this->assign('list',$list);
        return $this->fetch();
    }
    //我的公排
    public function gpdetail()
    {
        $data['id']=input('id');
        $list = array(
            '0' => 0,
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,

        );
        $user=model('User');
        $head=$user->where('id',$data['id'])->find();
        $a=$head;
        if ($head['us_node']!=0) {
            $head=$user->where('id',$head['us_node'])->find();
        }
        if ($head['us_node']!=0) {
            $head=$user->where('id',$head['us_node'])->find();
        }
        // halt($head);
        $list[0]=$user->where('id',$head['id'])->find();
        // halt($list[0]['id']);
        $ct=$user->where('us_node',$list[0]['id'])->count();
        // halt($ct);
        if ($ct==0) {
            // $this->s_msg('null',$list);
        }
        if ($ct==1) {
            $list[1]=$user->where('us_node',$list[0]['id'])->find();
            // $node=$list;
            // $this->s_msg('snull',$list);
            // halt($list);
        }
        if ($ct==2) {
            $i=1;
            $li=$user->where('us_node',$list[0]['id'])->order('us_area')->select();
            // halt($li);
            foreach ($li as $key) {
                $list[$i]=$user->where('id',$key['id'])->find();
                $i++;
                // halt($list);
            }
            // halt($list);
            $ct=$user->where('us_node',$list[1]['id'])->count();
            if ($ct==0) {
                // $this->s_msg(null,$list);
            }
            if ($ct==1) {
                $list[3]=$user->where('us_node',$list[1]['id'])->find();
                // $this->s_msg(null,$list);
            }
            if ($ct==2) {
                $i=3;
                $ii=$user->where('us_node',$list[1]['id'])->order('us_area')->select();
                foreach ($ii as $key) {
                    $list[$i]=$user->where('id',$key['id'])->find();
                    $i++;
                }
                $ct=$user->where('us_node',$list[2]['id'])->count();
                if ($ct==0) {
                    // $this->s_msg(null,$list);
                }
                if ($ct==1) {
                    $list[5]=$user->where('us_node',$list[2]['id'])->find();
                    // $this->s_msg(null,$list);
                }
            }
        }
        // halt($list);
        foreach ($list as $key => $value) {
            if (empty($value)) {
                break;
            }
            $list[$key]['us_zhitui_nickname']=db('user')->where('id',$value['us_zhitui'])->value('us_nickname');
        }
        // halt($list);
        $this->assign('a',$a);
        $this->assign('list',$list);
        return $this->fetch();
    }
    //用户界面上的状态修改
    //2018年6月11日 16:47:54
    //fkl
    public function change(){
        if (is_post()) {
            $post=input('post.');
            $user=model('User');
            $type=$user->where('id',$post['id'])->value('us_type');
            // halt($type);
            if ($type==0) {
                $list=$user->where('id',$post['id'])->update(['us_type'=>1]);
            }
            else{
                $list=$user->where('id',$post['id'])->update(['us_type'=>0]);
            }
            if ($list) {
                $this->success('修改成功');
            }
            else{
                $this->error('请稍后重试');
            }
        }
    }


    /**
     * 新进会员
     * author fengkl
     * time 2018年6月9日 14:51:48
     * @return mixed
     */
    public function news()
    {
        $user = new UserLogic;
        if (is_post()) {
            $rst = model('User')->editInfo([input('post.key') => input('post.value')], ['id' => input('post.id')]);
            if ($rst) {
                return [
                    'code' => 1,
                    'msg' => '修改成功',
                    'data' => $rst,
                ];
            } 
        }
        if (input('get.keywords')) {
            $this->map[] = ['us_tel|us_account|us_nickname', '=', input('get.keywords')];
        }
        $this->map[] = ['us_delete_status', '=', 1];
        $this->map[] = ['us_level', '=', 1];
        $this->map[] = ['gave_status', '=', 1];
        $list = $user->getList($this->map, $this->order, $this->size);
        $this->assign(array(
            'yuming' => $_SERVER['HTTP_HOST'],
            'list' => $list,
        ));
        return $this->fetch();

    }

    /**
     * 逻辑删除用户
     * author fengkl
     * time 2018年5月16日 17:19:00
     * @return mixed
     */
    public function delete()
    {
        if(empty(input('post.id')))
        {
            $this->error('id不存在');
        }
        $id = input('post.id');
        // $rel = array();
        $dlt=db('user')->where('id',$id)->delete();
        if ($dlt) {
            // $rel['msg'] = '删除成功！';
            $this->success('删除成功！');
        }
        else{
            // $rel['msg'] = '请稍后重试！';
            $this->error('请稍后重试！');
        }
        // $user = new UserLogic;
        // $re = $user->deleteUser($id);
        // $rel = array();
        // if($re) {
        //     $rel['msg'] = '删除成功！';
        // } else {
        //     $rel['msg'] = '该用户已被删除！';
        // }
        //var_dump($rel);exit();
        // return $rel;
    }

    /**
     * 修改用户
     * author fengkl
     * time 2018年5月16日 18:31:58
     * @return mixed
     */
    public function edit()
    {
        $post=input('id');
        // halt($post);

        if (is_post()) {
        }
        $info=model('User')->where('id',input('id'))->find();
        // $usermodel = new UserModel;
        // $info = $usermodel->getInfo(input('get.id'));
        //halt($info);
        $this->assign('info', $info);
        return $this->fetch();
    }
    public function changee()
    {
        $post=input('id');
        // $pwd=rand(100000,999999);
        $pwd=generate_password(8);
        $us_pwd=encrypt($pwd);
        // halt($us_pwd);
        $up=model('User')->where('id',$post)->update(['us_pwd'=>$us_pwd]);
        $this->assign('pwd',$pwd);
        if ($up) {
            $this->success('重置密码成功为：'.$pwd);
        }
        else{
            $this->error('密码已重置');
        }
    }

    /**
     * 添加用户
     * author fengkl
     * time 2018年5月19日 11:43:19
     * @return mixed
     */
    public function add()
    {
        // halt(11);
        if (is_post()) {
            $post=input('post.');
            $long=strlen($post['us_pwd']);
            if ($post['us_nickname']=='') {
                $this->error('昵称不能为空');
            }
            $us_nickname=model('User')->where('us_nickname',$post['us_nickname'])->find();
            if ($us_nickname) {
                $this->error('该昵称已存在');
            }
            if ($post['us_name']=='') {
                $this->error('姓名不能为空');
            }
            if ($post['us_tel']=='') {
                $this->error('电话不能为空');
            }
            $us_tel=model('User')->where('us_tel',$post['us_tel'])->find();
            if ($us_tel) {
                $this->error('该号码已在注册');
            }
            if ($post['us_pwd']=='') {
                $this->error('请输入密码');
            }
            if ($long<6||$long>16) {
                $this->error('请输入6-16位密码');
            }
            $post['us_id']=rand(10000,99999);
            while (model('User')->where('us_id',$post['us_id'])->find()) {
                $post['us_id']=rand(10000, 99999);
            }
            if ($post['zt_name']!='') {
                //返回节点人的信息
                $node=$this->my_node($post['zt_name']);
                // halt($node);
                $post['us_zhitui']=model('user')->where('us_nickname',$post['zt_name'])->value('id');
                // halt($node['us_node']);
                $list = $this->reg_function($node['us_node'],$node['us_area'],$post['us_id'],$post['us_nickname'],$post['us_name'],$post['us_tel'],$post['us_pwd'],$post['us_zhitui'],0);
                // halt($list);
                if ($list) {
                    $this->success('注册成功');
                }
                else{
                    $this->e_msg('请稍后重试');
                }
            }
            $post['us_ztstring']=0;
            $post['us_creattime']=date('y-m-d H:i:s');
            $post['us_pwd']=encrypt($post['us_pwd']);
            $list=model('user')->insert($post);
            if ($list) {
                $this->success('已添加');
            }
            else{
                $this->error('请稍后重试！');
            }
            // $userlogic = new UserLogic;
            // $rel = $userlogic->addUser(input('post.'));
            // return $rel;
        }
        return $this->fetch();
    }

    //查找节点人
    public function my_node($li)
    {
        $user=model('user')->where('us_nickname',$li)->find();
        if (!$user) {
            $this->error('请输入正确直推人');
        }
        //查找他的节点人
        //先查找第一梯队的用户
        if ($user['us_node']!=0)
        {
            $head=model('user')->where('id',$user['us_node'])->find();
            if ($head['us_node']!=0) {
                $head=model('user')->where('id',$head['us_node'])->find();
            }
        }
        else
        {
            $head=$user;
        }
        $info[0]=$user->where('id',$head['id'])->find();
        $ct=$user->where('us_node',$info[0]['id'])->count();
        if ($ct==0) {
            $my_node['us_node']=$info[0]['id'];
            $my_node['us_area']=0;
            return $my_node;
        }
        if ($ct==1) {
            $my_node['us_node']=$info[0]['id'];
            $my_node['us_area']=1;
            return $my_node;
        }
        if ($ct==2) {
            $i=1;
            $li=$user->where('us_node',$info[0]['id'])->order('id asc')->select();
            foreach ($li as $key) {
                $info[$i]=$user->where('id',$key['id'])->find();
                $i++;
            }
            $ct=$user->where('us_node',$info[1]['id'])->count();
            if ($ct==0) {
                $my_node['us_node']=$info[1]['id'];
                $my_node['us_area']=0;
                return $my_node;
            }
            if ($ct==1) {
                $my_node['us_node']=$info[1]['id'];
                $my_node['us_area']=1;
                return $my_node;
            }
            if ($ct==2) {
                $i=3;
                $ii=$user->where('us_node',$info[1]['id'])->select();
                foreach ($ii as $key) {
                    $info[$i]=$user->where('id',$key['id'])->find();
                    $i++;
                }
                $ct=$user->where('us_node',$info[2]['id'])->count();
                if ($ct==0) {
                    $my_node['us_node']=$info[2]['id'];
                    $my_node['us_area']=0;
                    return $my_node;
                }
                if ($ct==1) {
                    $my_node['us_node']=$list[2]['id'];
                    $my_node['us_area']=1;
                    return $my_node;
                }
            }
        }
    }
    //注册 节点人id:us_node,左区us_area=0，右区us_area=1
    //id:us_id  昵称us_nickname  姓名us_name  手机us_tel   登录密码us_pwd  直推人us_zhitui  type=0：插入，type=1:修改
    function reg_function($us_node,$us_area,$us_id,$us_nickname,$us_name,$us_tel,$us_pwd,$us_zhitui,$type)
    {
        // halt($us_node);
        $post['us_node']=$us_node;
        $post['us_area']=$us_area;
        $post['us_id']=$us_id;
        $post['us_nickname']=$us_nickname;
        $post['us_name']=$us_name;
        $post['us_tel']=$us_tel;
        $post['us_pwd']=$us_pwd;
        $post['us_zhitui']=$us_zhitui;
        $data=model('user')->where('id',$us_zhitui)->find();
        // halt($post['us_node']);
        //如果是从树状图上注册
        if ($type==0) {
            // $post['us_node']=model('User')->where('us_nickname',$post['us_node'])->value('id');
            //如果是选择的是节点人的右区，判断左区是不是有人
            if ($post['us_area']==1) {
                $us=db('user')->where('us_node',$post['us_node'])->find();
                if (!$us) {//如果选择节点人下没有人
                    $this->e_msg('优先选择左区');
                }
            }
            // halt($post['us_node']);
            $jiancha=model('User')->where('us_node',$post['us_node'])->where('us_area',$post['us_area'])->find();
            if ($jiancha) {
                $this->e_msg('该节点已经存在用户');
            }
            $us_nickname=model('User')->where('us_nickname',$post['us_nickname'])->find();
            if ($us_nickname) {
                $this->e_msg('已存在该昵称');
            }
            $us_tel=model('User')->where('us_tel',$post['us_tel'])->find();
            if ($us_tel) {
                $this->e_msg('该号码已经注册');
            }
            // echo $post['us_node'];
            // halt($post['us_node']);
            //节点人公排数量+1
            $add_node_number=model('User')->where('id',$post['us_node'])->setInc('us_node_number',1);
            //节点人信息
            $user_node=model('User')->where('id',$post['us_node'])->find();
            if ($user_node['us_node']!=0) {//如果节点人上面还存在节点人
                //节点人公排数量+1
                $a=model('User')->where('id',$user_node['us_node'])->setInc('us_node_number',1);
            }
            $post['us_zhitui']=$data['id'];
            $post['us_ztstring']=$data['us_ztstring'].','.$data['id'];
            $post['us_pwd']=encrypt($post['us_pwd']);
            $post['us_creattime']=date('y-m-d h-i-s');
            //添加注册人信息,未注册完成，默认未启用
            $list=db('user')->insertGetId($post);
            //判如果存在上上节点
            if (!empty($a)) {
                $b=model('User')->where('id',$user_node['us_node'])->find();
                if ($b['us_node_number']==7) {//如果上上节点人为公排数量为7
                    $user=model('User')->where('us_node',$b['id'])->select();
                    foreach ($user as $key) {
                        //清空节点人关系，设为没有节点人
                        model('User')->where('id',$key['id'])->update(['us_node'=>0]);
                    }
                    //将上上节点数量修改为1
                    model('User')->where('id',$user_node['us_node'])->update(['us_node_number'=>1]);
                    //出排次数加1
                    model('User')->where('id',$user_node['us_node'])->setInc(['us_node_time',1]);
                    // halt($b['id']);
                    //出排人查找他的推荐人的公排
                    $mynode=$this->my_node($b['id']);
                    // halt($mynode);
                    if ($mynode) {
                        $this->reg_function($mynode['us_node'],$mynode['us_area'],$b['us_id'],$b['us_nickname'],$b['us_name'],$b['us_tel'],$b['us_pwd'],$b['us_zhitui'],1);
                }
            }
        }
        }
        else{
            //如果出排用户是首节点人
            if ($post['us_zhitui']==0) {
                return 0;
            }
            //直推人公排数量+1
            $add_node_number=model('User')->where('id',$post['us_zhitui'])->setInc('us_node_number',1);
            //节点人信息
            $user_node=model('User')->where('id',$post['us_zhitui'])->find();
            if ($user_node['us_node']!=0) {//如果节点人上面还存在节点人
                //节点人公排数量+1
                $a=model('User')->where('id',$user_node['us_node'])->setInc('us_node_number',1);
            }
            model('User')->where('us_id',$post['us_id'])->update(['us_node'=>$post['us_node']]);
            if (!empty($a)) {
                $b=model('User')->where('id',$user_node['us_node'])->find();
                if ($b['us_node_number']==7) {//如果上上节点人为公排数量为7
                    $user=model('User')->where('us_node',$b['id'])->select();
                    foreach ($user as $key) {
                        //清空节点人关系，设为没有节点人
                        model('User')->where('id',$key['id'])->update(['us_node'=>0]);
                    }
                    //将上上节点数量修改为1
                    model('User')->where('id',$user_node['us_node'])->update(['us_node_number'=>1]);
                    //出排次数加1
                    model('User')->where('id',$user_node['us_node'])->setInc(['us_node_time',1]);
                    //出排人查找他的推荐人的公排
                    $mynode=$this->my_node($b['id']);
                    if ($mynode) {
                        $this->reg_function($mynode['us_node'],$mynode['us_area'],$b['us_id'],$b['us_nickname'],$b['us_name'],$b['us_tel'],$b['us_pwd'],$b['us_zhitui'],1);
                    }
                    //此处应有发放出排奖励
                }
            }
        }
        return 1;
    }
    /**
     * 地址列表
     * author fengkl
     * time 2018年5月19日 16:51:37
     * @return mixed
     */
    public function addr()
    {
        if (is_post()) {
            $rst = model('User_addr')->xiugai([input('post.key') => input('post.value')], ['id' => input('post.id')]);
            if ($rst) {
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
            return $rst;
        }
        if (input('get.id')) {
            $this->map[] = ['us_id', '=', input('get.id')];
        } else {
            $this->error("非法操作");
        }
        $list = model('User_addr')->chaxun($this->map, $this->order, $this->size);
        $this->assign(array(
            'list' => $list,
            'name' => model('User')->where('id', input('get.id'))->value('us_account'),
        ));
        return $this->fetch();

    }
    //下级分布图
    //2018年7月24日 11:04:43
    //fkl
    public function showMyteam($id){
        $Umodel = model('User');
        $theone = $Umodel->find($id);
        $map[] = ['us_path','like',"%".$theone['us_path'].','.$theone['id']."%"];
        $info = $Umodel->where($map)->select();
        $lists[0]['name'] = $theone['us_nickname'];
        $lists[0]['key'] = $theone['id'];
        $lists[0]['parent'] = '';       
        foreach ($info as $k => $v) {
            $lists[0]['name'] = $theone['us_nickname'];
            $lists[0]['key'] = $theone['id'];
            $lists[0]['parent'] = '';
            $kk = $k +1;           
            $lists[$kk]['key'] = $v['id'];
            $lists[$kk]['parent'] = $v['us_pid'];
            $lists[$kk]['name'] = $v['us_nickname'];
        }            
        $lists = json_encode($lists);
        //halt($lists);
        $this->assign('list',$lists);
        return $this->fetch();
    }

    public function excel(){//导出Excel
        $xlsName  = "export";
        $xlsCell  = array(
            array('id','用户ID'),
            array('us_nickname','昵称/账号'),
            array('us_name','姓名'),
            array('us_tel','电话'),
            array('us_node_number','公排数量'),
            array('us_node_time','出排次数'),
            array('us_cash','现金积分'),
            array('us_reg','注册积分'),
            array('us_creattime','创建时间'),
            array('alipay_name','支付宝名称'),
            array('alipay','支付宝账号'),
            array('bank_loction','开户银行'),
            array('bank_user','持卡人'),
            array('bank_number','银行卡号'),
        );
        $xlsData=model('user')->order('us_creattime desc')->select();
        // halt($xlsData);
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }
    function exportExcel($expTitle,$expCellName,$expTableData){
        //include_once EXTEND_PATH.'PHPExcel/PHPExcel.php';//方法二
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle.date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        $objPHPExcel = new PHPExcel();//方法一
        //$objPHPExcel = new \PHPExcel();//方法二
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        ob_end_clean();//这一步非常关键，用来清除缓冲区防止导出的excel乱码
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//"xls"参考下一条备注
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//"Excel2007"生成2007版本的xlsx，"Excel5"生成2003版本的xls
        $objWriter->save('php://output');
        exit;
    }

}