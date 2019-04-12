<?php
namespace app\admin\controller;

use app\admin\logic\ProductLogic;
use app\common\model\Product as ProductModel;
use think\Db;
use think\Container;
use app\admin\controller\Every;

class News extends Common
{
    /**
     * 商品列表
     * author fengkl
     * time 2018年5月23日 17:48:24
     * @return mixed
     */
    public function index()
    {
        $map=[];
        $list=db('gonggao')->where($map)->select();
        $this->assign('list',$list);
       return $this->fetch();
    }

    public function del()
    {
        $list=db('gonggao')->where('id',input('id'))->delete();
        $this->success('已删除');
    }

    public function edit()
    {
        // halt('123');
        if (is_post()) {
            $post=input('post.');
            // halt($post);
            db('gonggao')->where('id',$post['id'])->update([
                'title'=>$post['title'],
                'message'=>$post['content'],
                'image'=>$post['image'],
                'add_time'=>date('y-m-d h:i:s'),
            ]);
                $this->success('修改成功');
        }
        $map['id']=input('id');
        $list=db('gonggao')->where($map)->find();
        $this->assign('info',$list);
        return $this->fetch();
    }
    // public function edit()
    // {
    //     $post=input('post.');
    //     halt($post);
    // }
    public function add()
    {
        if (is_post()) {
             // halt('123');
            $post=input('post.');
            // halt($post);
            $list = array(
                'title'=>$post['title'],
                'message'=>$post['content'],
                'image'=>$post['image'],
                'add_time'=>date('y-m-d h:i:s'),
            );
            db('gonggao')->insert($list);
            $this->success('添加成功','index');
        }
        return $this->fetch();
    }

}