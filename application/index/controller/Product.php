<?php
namespace app\index\controller;
use think\facade\Config;
use app\index\controller\Every;
use app\common\controller\Api;
use think\Container;

/**
 * 登陆控制器
 */

class Product extends Common
{
    public function __construct()
    {
        parent::__construct();
    }
// class Product extends Basis {
// 	function __construct()
//     {
// 		parent::__construct();
// 	}
    //商品分类 分类id
    public function fenlei()
    {
        $jiangli = db('jiangli')->find();
        if (input('id')==="") {
            $a=db('cate')->select();
            $list=db('product')->where('pd_cate',$a[0]['id'])->where('pd_status','<>',1)->order('pd_creat_time desc')->select();
        }
        else{
            $list=db('product')->where('pd_cate',input('id'))->where('pd_status','<>',1)->order('pd_creat_time desc')->select();
        }
        foreach ($list as $key => $value) {
            $list[$key]['pd_image']=explode(',',$value['pd_image']);
            $list[$key]['pd_image']=$list[$key]['pd_image'][0];
            $list[$key]['yj']=$value['pd_price']/$jiangli['zekou'];
        }
        $this->s_msg(null,$list);
    }
    //商品详情  商品id
    public function product_new()
    {
        $list=db('product')->where('id',input('id'))->find();
        $type==db('product_type')->where('pd_id',$list['id'])->select();
        $list['type']=$type;
        $this->s_msg(null,$list);
    }
    //商品详情
    public function product()
    {
        $post=input('id');
        $product=db('product')->where('id',$post)->find();
        $product_type=db('product_type')->where('id',$product['id'])->select();
        $map['product']=$product;
        $map['product_type']=$product_type;
        $this->s_msg(null,$map);
    }
    //搜索商品
    public function sousuo()
    {
        $post=input('message');
        $list=model('product')->where('pd_name','like','%'.$post.'%')->where('pd_status','<>',1)->select();
        foreach ($list as $key => $value) {
            $list[$key]['pd_image']=explode(',',$value['pd_image']);
            $list[$key]['pd_image']=$list[$key]['pd_image'][0];
        }
        $this->s_msg(null,$list);
    }

    //折扣优品
    public function zkyp()
    {
        $list=db('product')->where('pd_status',3)->select();
        $this->s_msg(null,$list);
    }
}



