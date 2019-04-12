<?php
namespace app\index\controller;

use app\admin\logic\ProductLogic;
use app\common\model\Product as ProductModel;
use think\Db;
use think\Container;

class Index extends Common
{
	public function __construct()
    {
        parent::__construct();
    }

    //查找用户电话
    public function fanhui()
    {
        $post=input('id');
        $tel=db('user')->where('id',$post)->value('us_tel');
        if ($tel) {
            $this->s_msg(null,$tel);
        }
    }
    //商城首页
    public function index()
    {
        $jiangli = db('jiangli')->find();
        //轮播图
        $lunbotu=db('lunbotu')->select();
        //商品分类
        $fenlei=db('cate')->where('fl_type',0)->select();
        //折扣优品
        $zkyp=db('product')->where('pd_status',3)->where('pd_status','>',0)->order('pd_creat_time desc')->select();
        foreach ($zkyp as $key => $value) {
            $zkyp[$key]['pd_image']=explode(',',$value['pd_image']);
            $zkyp[$key]['pd_image']=$zkyp[$key]['pd_image'][0];
            $zkyp[$key]['yj']=$value['pd_price']/$jiangli['zekou'];
            $zkyp[$key]['yj']=ceil($zkyp[$key]['yj']);
        }
        $product=model('product')->where('pd_status','>',0)->where('pd_status','<>',1)->order('pd_creat_time desc')
        ->paginate(4,false,$config = ['query'=>request()->param()]);

        foreach ($product as $key => $value) {
            $product[$key]['pd_image']=explode(',',$value['pd_image']);
            $product[$key]['pd_image']=$product[$key]['pd_image'][0];
            $product[$key]['yj']=$value['pd_price']/$jiangli['zekou'];
            $product[$key]['yj']=ceil($product[$key]['yj']);
        }
        $data['lunbotu']=$lunbotu;
        $data['fenlei']=$fenlei;
        $data['product']=$product;
        $data['zkyp']=$zkyp;
        $this->s_msg(null,$data);
    }
    //搜索   商品名称pd_name
    public function sousuo()
    {
        $name=input('pd_name');
        $data=db('product')->where('pd_name','like','%'.$name.'%')->where('pd_status','>',1)->paginate(4,false,$config = ['query'=>request()->param()]);
        if ($data) {
            $this->s_msg(null,$data);
        }
        else{
            $this->e_msg('没有找到商品');
        }
    }

    //商品分类
    public function fenlei()
    {
        // $jiangli = db('jiangli')->find();
        if (input('id')) {
            $post=input('id');
        }
        else{
            $list=db('cate')->order('id asc')->find();
            $post=$list['id'];
        }
        //分类商品
        $fenlei=db('product')->where('ca_id',$post)->where('pd_status','>',1)->order('pd_add_time desc')->select();
        //热门推荐
        $remen=db('product')->where('pd_status',5)->where('pd_status','>',1)->order('pd_add_time desc')->select();
        $data['fenlei']=$fenlei;
        $data['remen']=$remen;
        $this->s_msg(null,$data);
    }

    //商品排序 type价格升序0降序1，商品分类ca_id
    public function paixu()
    {
        $post=input('post.');
        if ($post['type']==1) {
            $list=db('product')->where('ca_id',$post['ca_id'])->where('pd_status','>',1)->order('pd_price desc')->select();
            if ($list) {
                $this->s_msg(null,$list);
            }
            else{
                $this->e_msg('请稍后重试');
            }
        }
        if ($post['type']==0) {
            $list=db('product')->where('ca_id',$post['ca_id'])->where('pd_status','>',1)->order('pd_price asc')->select();
            if ($list) {
                $this->s_msg(null,$list);
            }
            else{
                $this->e_msg('请稍后重试');
            }
        }
    }

    //商品详情
    public function xiangqing()
    {
        $jiangli = db('jiangli')->find();
        //传过来的商品id
        $id=input('id');
        //商品详情
        $list = db('product')->where('id',$id)->find();
        // $list['pd_image']=implode(',',$list['pd_image']);
        $list['yj'] = $list['pd_price']/$jiangli['zekou'];
        $list['yj'] = ceil($list['yj']);
        $list['pd_image'] = explode(',',$list['pd_image']);
        //商品类型
        $list['pd_type'] = explode(' ',$list['pd_type']);
        foreach ($list['pd_type'] as $key => $v) {
            if(!$v){
                unset($list['pd_type'][$key]);
            }
        }
        $guige=db('product_type')->where('pd_id',$id)->select();
        //热门推荐
        $list['guige']=$guige;
        $remen=db('product')->where('pd_status',5)->select();
        $data['list']=$list;
        $data['remen']=$remen;
        $this->s_msg(null,$data);
    }

    //获得不同类型的价格
    public function jiage()
    {
        //获得商品的id和选择的商品类型type
        $post=input('post.');
        $map['pd_id'] = $post['id'];
        $map['pd_type'] = $post['type'];
        $jiage=db('product_type')->where($map)->value('pd_sum');
        if ($jiage) {
            $this->s_msg(null,$jiage);
        }else{
            $this->e_msg('失败');
        }
    }


   //上传图片
    // public function upload()
    // {
    //     $bb = Container::get('env')->get('ROOT_PATH');
    //     $file = request()->file('imgFile');
    //     //halt($file);
    //     $info = $file->validate(['size' => '4096000'])
    //         ->move($bb . 'public/uploads/');
    //     if ($info) {
    //         $path = '/uploads/' . $info->getsavename();
    //         $path = str_replace("\\", "/", $path);
    //         $this->s_msg(null,$path);
    //     } else {
    //         $data = array(
    //             'msg' => $file->getError(),
    //             'code' => 0,
    //         );
    //         $this->e_msg($data['msg']);
    //     }
    // }

        //头像上传
    public function upload(){
        // $data=$this->user;
        // 获取表单上传文件 例如上传了001.jpg
        $bb = Container::get('env')->get('ROOT_PATH');
        // halt(ROOT_PATH);
        $file = request()->file('image');
        // halt($file);
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move($bb. 'public/uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();

                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getSaveName();
                $image='/uploads/'.$info->getSaveName();
                $image = str_replace("\\", "/", $image);
                $list=db('user')->where('id',input('id'))->update(['us_head_pic'=>$image]);
                if ($list) {
                    $this->s_msg('头像上传成功',$image);
                }
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

    //上传凭证
    public function upimg()
    {
        // $data=$this->user;
        $bb = Container::get('env')->get('ROOT_PATH');
        $file = request()->file('image');
        if($file){
            $info = $file->move($bb. 'public/uploads');
            if($info){
                $image='/uploads/'.$info->getSaveName();
                $list=db('dingdanzh')->where('id',input('id'))->update(['zf_image'=>$image]);
                if ($list) {
                    $this->s_msg('上传成功',$image);
                }
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
    //上传充值凭证
    public function uploadd(){
        // halt(input('post.'));
        // $data=$this->user;
        // 获取表单上传文件 例如上传了001.jpg
        $bb = Container::get('env')->get('ROOT_PATH');
        // halt(ROOT_PATH);
        $file = request()->file('image');
        // halt($file);


        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move($bb. 'public/uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getSaveName();
                $image='/uploads/'.$info->getSaveName();
                $image = str_replace("\\", "/", $image);
                $list=db('tixian')->where('id',input('id'))->update(['tx_image'=>$image]);
                if ($list) {
                    $this->s_msg('上传凭证成功',$image);
                }
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

        //上传头像
    public function uploadddd(){
        // halt(input('post.'));
        // $data=$this->user;
        // 获取表单上传文件 例如上传了001.jpg
        $bb = Container::get('env')->get('ROOT_PATH');
        // halt(ROOT_PATH);
        $file = request()->file('image');


        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move($bb. 'public/uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getSaveName();
                $image='/uploads/'.$info->getSaveName();
                $image = str_replace("\\", "/", $image);
                $list=db('user')->where('id',input('id'))->update(['us_head_image'=>$image]);
                if ($list) {
                    $this->s_msg('上传头像成功',$image);
                }
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }



            //上传充值凭证
    public function uploaddd(){
        // halt(input('post.'));
        // $data=$this->user;
        // 获取表单上传文件 例如上传了001.jpg
        $bb = Container::get('env')->get('ROOT_PATH');
        // halt(ROOT_PATH);
        $file = request()->file('image');
        // halt($file);


        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move($bb. 'public/uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getSaveName();
                $image='/uploads/'.$info->getSaveName();
                $image = str_replace("\\", "/", $image);
                $list=db('order')->where('id',input('id'))->update(['or_zf_image'=>$image]);
                if ($list) {
                    $this->s_msg('上传凭证成功',$image);
                }
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

    public function title()
    {
        $list=db('gonggao')->order('gg_time desc')->select();
        $this->s_msg(null,$list);
    }

    public function onetitle()
    {
        $list=db('gonggao')->where('id',input('id'))->find();
        $this->s_msg(null,$list);
    }


    public function proportion()
    {
        $list=db('proportion')->where('id',1)->value('proportion');
        $this->s_msg(null,$list);
    }



 }
