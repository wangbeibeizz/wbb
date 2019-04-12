<?php
namespace app\index\controller;
use think\facade\Config;
use app\index\controller\Every;
use app\common\controller\Api;
use app\common\model\User as UserModel;
/**
 * 登陆控制器
 */
class Login extends Basis {
	function __construct() {
		parent::__construct();
	}


// class Login extends Api {
//     function __construct() {
//         parshouyimxent::__construct();
//     }

	//2018年5月31日 09:53:29
    //登录
    public function index()
    {
        $data = $this->user;
        $this->s_msg(null, $data);
    }
    //注册页面显示  节点人id:us_node,左区us_area=0，右区us_area=1
    public function reg_view()
    {
        $data=$this->user;
        $post=input('post.');
        if (empty($post['us_node'])) {
            $list['zhitui']=model('User')->where('id',$data['id'])->value('us_nickname');
            $list['zhitui_sum']=db('jiangli')->value('zhitui');
            $list['us_id']=rand(10000, 99999);
            // $a=model('User')->where('us_id',$list['us_id'])->find();
            while (model('User')->where('us_id',$list['us_id'])->find()) {
                $list['us_id']=rand(10000, 99999);
            }
            $this->s_msg(null,$list);
        }
        $list['zhitui']=model('User')->where('id',$data['id'])->value('us_nickname');
        $list['node_nickname']=model('User')->where('id',$post['us_node'])->value('us_nickname');
        $list['us_area']=$post['us_area'];
        $list['us_id']=rand(10000, 99999);
        $list['zhitui_sum']=db('jiangli')->value('zhitui');
        // $a=model('User')->where('us_id',$list['us_id'])->find();
        while (model('User')->where('us_id',$list['us_id'])->find()) {
            $list['us_id']=rand(10000, 99999);
        }
        $this->s_msg(null,$list);
    }

    //获取注册积分比例
    public function zc_gw()
    {
        $jiangli=db('jiangli')->where('id',1)->value('zc_gw');
        $jiangli=$jiangli*100;
        $time=floor($jiangli/10);
        $i=0;
        while ($i <= $time) {
            $list[$i]=$i;
            $i++;
        }
        $data = array();
        foreach ($list as $key => $value) {
            $a = $value/100;
            $b = $value*10;
            $c = 100-$value*10;
            $data[$key][]= $a*10;
            $data[$key][]='购物积分'.$b.'%+创业积分'.$c.'%';
        };
        $this->s_msg($data);
    }

    //注册 节点人id:us_node,左区us_area=0，右区us_area=1
    //id:us_id  昵称us_nickname  姓名us_name  手机us_tel   登录密码us_pwd  直推人us_zhitui 身份证号id_card
    public function reg()
    {
        $jiangli=db('jiangli')->where('id',1)->find();
        $data=$this->user;
        $post=input('post.');
        // if (!isIdCard($post['id_card'])) {
        //     $this->e_msg('请输入正确身份证号');
        // }
        // halt($post['proportion']);
        if ($data['us_reg']<$jiangli['zhitui']*(1-$post['proportion'])) {
            $this->e_msg('创业积分余额不足，请前往充值');
        }
        if ($data['us_cash']<$jiangli['zhitui']*$post['proportion']) {
            $this->e_msg('购物积分余额不足');
        }
        //注册
        if ($post['us_node']===null) {
            $this->e_msg('请输入节点人账号/昵称');
        }
        //判断当前选择位置是否可以注册
        //选择的节点人的信息$jd_user
        $jd_user=model('User')->where('us_nickname',$post['us_node'])->find();
        if ($jd_user['us_node']==0) {
            $td=1;
        }else{
            $ur=model('User')->where('id',$jd_user['us_node'])->find();
            if ($ur['us_node']==0) {
                $td=2;
            }
            else{
                $td=3;//节点人是第三梯队
            }
        }
        if (!$jd_user) {
            $this->e_msg('请输入正确节点人账号/昵称');
        }
        //判断当前选择位置有没有存在用户
        if (model('user')->where('us_node',$jd_user['id'])->where('us_area',$post['us_area'])->find()) {
            $this->e_msg('当前位置已存在用户');
        }
        //如果节点人是第二梯队
        if ($td==2) {
            if ($jd_user['us_area']==1) {//节点人是第二梯队的右区
                if (model('user')->where('us_node',$jd_user['us_node'])->where('us_area',0)->value('us_node_number')<2) {
                    //判断第二梯队的左区下方小于两个用户
                    $this->e_msg('当前位置不可以注册');
                }
            }
        }
        //如果节点人是第三梯队
        if ($td==3) {
            $this->e_msg('当前位置不可以注册');
        }
        $list = $this->reg_function($post['us_node'],$post['us_area'],$post['us_id'],$post['us_nickname'],$post['us_name'],$post['us_tel'],$post['us_pwd'],$post['us_zhitui'],0,$post['proportion'],$post['id_card']);
        if ($list) {
            $this->s_msg(null,$list);
        }
        else{
            $this->e_msg('请稍后重试');
        }
    }

    //套餐列表
    public function taocan()
    {
        $list=db('taocan')->select();
        $this->s_msg(null,$list);
    }
    //选择奖励
    //刚注册成功的用户id:us_id  姓名us_name  电话us_tel  区域 us_area  详细地址us_detailed
    //套餐id:tc_id
    public function xz_taocan()
    {
        $post=input('post.');
        // halt($post);
        if ($post['us_name']==null) {
            $this->e_msg('请输入收货人姓名');
        }
        if ($post['us_tel']==null) {
            $this->e_msg('请输入收货人电话');
        }
        // if ($post['area']==null) {
        //     $this->e_msg('请选择区域');
        // }
        // if ($post['city']==null) {
        //     $this->e_msg('请选择城市');
        // }
        if ($post['province']==null) {
            $this->e_msg('请选择省');
        }
        if ($post['us_detailed']==null) {
            $this->e_msg('请输入详细地址');
        }
        //将数组装换成字符串
        $post['addr_code']=implode(',',$post['addr_code']);
        //添加地址
        $info=db('dizhi')->insertGetId($post);
        $list = array(
            'us_id' => $post['us_id'],
            'us_name'=>db('user')->where('us_id',$post['us_id'])->value('us_name'),
            'dz_id'=>$info,
            'tc_id'=>$post['tc_id'],
            'creat_time'=>date('y-m-d H-i-s'),
        );
        $inff=db('taocan_dingdan')->insertGetId($list);
        if ($inff&&$info) {
            // $up=db('user')->where('id',$post['us_id'])->update(['us_type'=>1]);
            $this->s_msg('添加成功');
        }
        else{
            $this->e_msg('失败');
        }
    }


    //注册 节点人id:us_node,左区us_area=0，右区us_area=1
    //id:us_id  昵称us_nickname  姓名us_name  手机us_tel   登录密码us_pwd  直推人us_zhitui  type=0：插入，type=1:修改
    function reg_function($us_node,$us_area,$us_id,$us_nickname,$us_name,$us_tel,$us_pwd,$us_zhitui,$type,$proportion='',$id_card='')
    {
        $post['us_node']=$us_node;
        $post['us_area']=$us_area;
        $post['us_id']=$us_id;
        $post['us_nickname']=$us_nickname;
        $post['us_name']=$us_name;
        $post['us_tel']=$us_tel;
        $post['us_pwd']=$us_pwd;
        $post['us_zhitui']=$us_zhitui;
        $post['id_card']=$id_card;
        $data=$this->user;
        //如果是从树状图上注册
        if ($type==0) {
            $post['us_node']=model('User')->where('us_nickname',$post['us_node'])->value('id');
            //如果是选择的是节点人的右区，判断左区是不是有人
            if ($post['us_area']==1) {
                $us=db('user')->where('us_node',$post['us_node'])->find();
                if (!$us) {//如果选择节点人下没有人
                    $this->e_msg('优先选择左区');
                }
            }
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
            $post['us_creattime']=date('y-m-d H-i-s');
            //添加注册人信息,未注册完成，默认未启用
            $list=db('user')->insertGetId($post);

            //发放直推奖励并添加奖励记录
            $this->zt_jiangli($data['id'],$list,$proportion);

            //判如果存在上上节点
            if (!empty($a)) {
                $b=model('User')->where('id',$user_node['us_node'])->find();
                if ($b['us_node_number']==7) {//如果上上节点人为公排数量为7
                    //此处应有发放出排奖励
                    $this->my_node_jiangli($b['id']);
                    $this->my_jiangli($b['id']);
                    $user=model('User')->where('us_node',$b['id'])->select();
                    foreach ($user as $key) {
                        //清空节点人关系，设为没有节点人
                        model('User')->where('id',$key['id'])->update(['us_node'=>0]);
                    }
                    //将上上节点数量修改为1
                    model('User')->where('id',$user_node['us_node'])->update(['us_node_number'=>1]);
                    //出排次数加1
                    model('User')->where('id',$user_node['us_node'])->setInc('us_node_time',1);
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
            // $add_node_number=model('User')->where('id',$post['us_zhitui'])->setInc('us_node_number',1);
            $this->zt_jianglii($post['us_zhitui'],$post['us_id']);
            //出排用户的节点人
            $user_node=model('User')->where('id',$post['us_node'])->find();
            $a=model('User')->where('id',$user_node['id'])->setInc('us_node_number',1);
            if ($user_node['us_node']!=0) {//如果出排用户的节点人上面还存在节点人
                //找到他的节点人用户
                $user_node=model('User')->where('id',$user_node['us_node'])->find();
                //节点人公排数量+1
                $a=model('User')->where('id',$user_node['id'])->setInc('us_node_number',1);
                //如果节点人上面还存在节点人
                if ($user_node['us_node']!=0) {
                //找到他的节点人用户
                $user_node=model('User')->where('id',$user_node['us_node'])->find();
                //节点人公排数量+1
                $a=model('User')->where('id',$user_node['us_node'])->setInc('us_node_number',1);
                //找到他的节点人用户
                $user_node=model('User')->where('id',$user_node['us_node'])->find();
            }
            }
            model('User')->where('us_id',$post['us_id'])->update(['us_node'=>$post['us_node']]);
            model('User')->where('us_id',$post['us_id'])->update(['us_area'=>$post['us_area']]);
            if (!empty($a)) {
                $b=model('User')->where('id',$user_node['id'])->find();
                if ($b['us_node_number']==7) {//如果上上节点人为公排数量为7
                    //此处应有发放出排奖励
                    $this->my_node_jiangli($b['id']);
                    $this->my_jiangli($b['id']);
                    $user=model('User')->where('us_node',$b['id'])->select();
                    foreach ($user as $key) {
                        //清空节点人关系，设为没有节点人
                        model('User')->where('id',$key['id'])->update(['us_node'=>0]);
                    }
                    //将上上节点数量修改为1
                    model('User')->where('id',$user_node['id'])->update(['us_node_number'=>1]);
                    //出排次数加1
                    model('User')->where('id',$user_node['id'])->setInc('us_node_time',1);
                    //出排人查找他的推荐人的公排
                    $mynode=$this->my_node($b['id']);
                    // halt($b['id']);
                    if ($mynode) {
                        $this->reg_function($mynode['us_node'],$mynode['us_area'],$b['us_id'],$b['us_nickname'],$b['us_name'],$b['us_tel'],$b['us_pwd'],$b['us_zhitui'],1);
                    }
                    //此处应有发放出排奖励
                }
            }
        }
        return 1;
    }

    function my_node($id)
    {
        $user=model('User');
        //查找直推人的id
        $data['id']=$user->where('id',$id)->value('us_zhitui');
        // halt($data['id']);
        if ($data['id']!=0) {
            $head=$user->where('id',$data['id'])->find();
            if ($head['us_node']!=0) {
                $head=$user->where('id',$head['us_node'])->find();
            }
            if ($head['us_node']!=0) {
                $head=$user->where('id',$head['us_node'])->find();
            }
        }else{
            $head=$user->where('id',$id)->find();
        }
        $list[0]=$user->where('id',$head['id'])->find();
        $ct=$user->where('us_node',$list[0]['id'])->count();
        if ($ct==0) {
            $my_node['us_node']=$list[0]['id'];
            $my_node['us_area']=0;
            return $my_node;
        }
        if ($ct==1) {
            $my_node['us_node']=$list[0]['id'];
            $my_node['us_area']=1;
            return $my_node;
        }
        if ($ct==2) {
            $i=1;
            $li=$user->where('us_node',$list[0]['id'])->order('us_area')->select();
            foreach ($li as $key) {
                $list[$i]=$user->where('id',$key['id'])->find();
                $i++;
            }
            $ct=$user->where('us_node',$list[1]['id'])->count();
            if ($ct==0) {
                $my_node['us_node']=$list[1]['id'];
                $my_node['us_area']=0;
                return $my_node;
            }
            if ($ct==1) {
                $my_node['us_node']=$list[1]['id'];
                $my_node['us_area']=1;
                return $my_node;
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
                    $my_node['us_node']=$list[2]['id'];
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
    //出排奖励
    public function my_jiangli($id)
    {
        //出局会员
        $head=db('user')->where('id',$id)->find();
        $jiangli=db('jiangli')->where('id',1)->find();
        // $jl=$jiangli['chuju_hd']*$jiangli['chuju'];
        $jiangli['chuju']=$jiangli['chuju']*(1-$jiangli['kouchu']);
        model('User')->where('id',$id)->setInc('us_cash',$jiangli['chuju']);
        $list = array(
                're_type' => 1,
                're_time' => date('y-m-d H:i:s'),
                'rd_mony' => $jiangli['chuju'],
                'rd_id'   => $head['id'],
                'us_id'   => $head['id'],
             );
        db('reward')->insertGetId($list);
    }

    //出排奖励  1出局之后，2、3、4、5、6、7均可以获得10%的奖励
    function my_node_jiangli($id)
    {
        // $data=$this->user;
        $user=model('User');
        // $head=$user->where('id',$data['id'])->find();
        // if ($head['us_node']!=0) {
        //     $head=$user->where('id',$head['us_node'])->find();
        // }
        // if ($head['us_node']!=0) {
        //     $head=$user->where('id',$head['us_node'])->find();
        // }
        //出局会员
        $head=$user->where('id',$id)->find();
        // halt($head);
        $list[0]=$user->where('id',$head['id'])->find();
        // halt($list[0]['id']);
        $ct=$user->where('us_node',$list[0]['id'])->count();
        // halt($ct);
        // if ($ct==0) {
        //     $this->s_msg('null',$list);
        // }
        if ($ct==1) {
            $list[1]=$user->where('us_node',$list[0]['id'])->find();
            // $this->s_msg('null',$list);
        }
        if ($ct==2) {
            $i=1;
            $li=$user->where('us_node',$list[0]['id'])->order('id asc')->select();
            // halt($li);
            foreach ($li as $key) {
                $list[$i]=$user->where('id',$key['id'])->find();
                $i++;
                // halt($list);
            }
            // halt($list);
            $ct=$user->where('us_node',$list[1]['id'])->count();
            // if ($ct==0) {
            //     $this->s_msg(null,$list);
            // }
            if ($ct==1) {
                $list[3]=$user->where('us_node',$list[1]['id'])->find();
                $this->s_msg(null,$list);
            }
            if ($ct==2) {
                $i=3;
                $ii=$user->where('us_node',$list[1]['id'])->select();
                foreach ($ii as $key) {
                    $list[$i]=$user->where('id',$key['id'])->find();
                    $i++;
                }
                $ct=$user->where('us_node',$list[2]['id'])->count();
                // if ($ct==0) {
                //     $this->s_msg(null,$list);
                // }
                if ($ct==1) {
                    $list[5]=$user->where('us_node',$list[2]['id'])->find();
                    // $this->s_msg(null,$list);
                }
                if ($ct==2) {
                    $j=5;
                    $lii=$user->where('us_node',$list[2]['id'])->order('id asc')->select();
                    // $list[6]=$user->where()->find();
                    foreach ($lii as $key) {
                        $list[$j]=$user->where('id',$key['id'])->find();
                        $j++;
                    }
                }
            }
        }
        // halt($list);
        $jiangli=db('jiangli')->where('id',1)->find();
        $jl=$jiangli['chuju_hd']*$jiangli['chuju'];
        $jl=$jl*(1-$jiangli['kouchu']);
        for ($i=1; $i < 7; $i++) {
            model('User')->where('id',$list[$i]['id'])->setInc('us_cash',$jl);
            $info = array(
                're_type' => 2,
                're_time' => date('y-m-d H:i:s'),
                'rd_mony' => $jl,
                'us_id'   => $list[$i]['id'],
                'rd_id'   => $head['id'],
                 );
            db('reward')->insertGetId($info);
        }
    }

    //直推奖励
    public function zt_jiangli($us_id,$rd_id,$proportion)
    {
        $jiangli=db('jiangli')->where('id',1)->find();
        $jl=$jiangli['zhitui']*$jiangli['zhitui_hd'];
        $xj=$jiangli['zhitui']*$proportion;
        $zc=$jiangli['zhitui']*(1-$proportion);
        //扣除注册积分
        $upzc=model('User')->where('id',$us_id)->setDec('us_reg',$zc);
        $upxj=model('User')->where('id',$us_id)->setDec('us_cash',$xj);
        //直推奖励20%
        $jl=$jl*(1-$jiangli['kouchu']);
        $upzt=model('User')->where('id',$us_id)->setInc('us_cash',$jl);
        $list = array(
            're_type' => 0,
            're_time' => date('y-m-d H:i:s'),
            'rd_mony' => $jl,
            'rd_id' => $rd_id,
            'us_id' => $us_id,
        );
        $info=db('reward')->insertGetId($list);
    }
    public function zt_jianglii($us_id,$rd_id)
    {
        $jiangli=db('jiangli')->where('id',1)->find();
        $jl=$jiangli['zhitui']*$jiangli['zhitui_hd'];
        // //扣除注册积分
        // $upzc=model('User')->where('id',$us_id)->setDec('us_reg',$jiangli['zhitui']);
        //直推奖励20%
        $jl=$jl*(1-$jiangli['kouchu']);
        $upzt=model('User')->where('id',$us_id)->setInc('us_cash',$jl);
        $list = array(
            're_type' => 0,
            're_time' => date('y-m-d H:i:s'),
            'rd_mony' => $jl,
            'rd_id'   => $rd_id,
            'us_id'   => $us_id,
        );
        $info=db('reward')->insertGetId($list);
    }
}