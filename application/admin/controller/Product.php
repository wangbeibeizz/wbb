<?php
/**
 * Created by fengkl
 * User: Administrator
 * Date: 2018年5月23日 11:15:10
 * 
 */

namespace app\admin\controller;

use app\admin\logic\ProductLogic;
use app\common\model\Product as ProductModel;
use think\Db;
use think\Container;
use app\admin\controller\Every;

class Product extends Common
{
    /**
     * 商品列表
     * author fengkl
     * time 2018年5月23日 17:48:24
     * @return mixed
     */
    public function index()
    {
        $map=[];$orderby='';
        if (is_get()) {
            if (input('get.orderby')==1) {
                $orderby = 'pd_price';
            }
            if (input('get.orderby')==2) {
                $orderby = 'pd_price desc';
            }
            if (input('get.pd_name')!=null) {
                $map=[
                    ['pd_name','like','%'.input('get.pd_name').'%']
                ];
            }
        }
        $product = model('Product');
        $list=$product->where($map)->order($orderby)->paginate(10);
        $ca_list = db('cate')->where('fl_type',1)->select();

        foreach ($list as $key => $value) {
            $list[$key]['pd_image']=explode(',',$value['pd_image']);
            $list[$key]['pd_image']=$list[$key]['pd_image'][0];
        }
        $this->assign(array(
            'list' => $list,
            'ca_list' => $ca_list,
        ));
        // halt(123);
        return $this->fetch();

    }
    //套餐列表
    public function taocan()
    {
        $list=db('taocan')->select();
        // halt($list);
        $this->assign('list',$list);
        return $this->fetch();
    }
    //添加套餐
    public function taocan_add()
    {
        if (is_post()) {
            $post = input('post.');
            if ($post['tc_watch']==null||$post['tc_phone']==null||$post['tc_computer']==null) {
                $this->error('请填写完整信息');
            }
            if ($post['watch_image']==null||$post['phone_image']==null||$post['computer_image']==null) {
                $this->error('请上传图片');
            }
            $post['creat_time']=date('y-m-d h:i:s');
            $list=db('taocan')->insertGetId($post);
            if ($list) {
                $this->success('添加成功');
            }
            else{
                $this->error('请稍后重试');
            }
        }
        return $this->fetch();
    }
    //修改套餐
    public function taocan_edit()
    {
        if (is_post()) {
            $info=input('post.');
            // halt($info);
            $up=db('taocan')->where('id',$info['token'])->update($info);
            if ($up) {
                $this->success('修改成功');
            }
            else{
                $this->error('未修改');
            }
        }
        $list=db('taocan')->where('id',input('id'))->find();
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 修改商品
     * author fengkl
     * time 2018年5月24日17:42:25
     * @return mixed
     */
    public function edit()
    {
        // $Productlogic = new ProductLogic;
        $pd_id=input('id');
        if (is_post()) {
            $post = input('post.');
            // halt($post);
            if (empty($post['pd_image'][0])) {
                $this->error('请上传商品主图');
            }
            if (empty($post['ca_id'])) {
                $this->error('请选择商品分类');
            }
            $post['pd_image']=array_slice($post['pd_image'],0);
            $post['pd_image']=implode(',',$post['pd_image']);
            $pd_id= $post['pd_id'];
            db('product_type')->where('pd_id',$pd_id)->delete();
            foreach ($post['type'] as $k=>$value) {
                $list = array(
                    'type'     => $post['type'][$k],
                    'type_sum' => $post['type_sum'][$k],
                    'gold_yd'  => $post['gold'][$k],
                    'pd_id'    => $pd_id,
                );
                db('product_type')->insert($list);
            }
            $pd_cate_name=db('cate')->where('id',$post['ca_id'])->value('fl_name');
            $post['pd_details'] = htmlspecialchars_decode($post['pd_details']);
            $list = array(
                'pd_name'       =>  $post['pd_name'],
                'pd_price'      =>  $post['pd_price'],
                'pd_stock'      =>  $post['pd_stock'],
                'pd_sale'       =>  $post['pd_sale'],
                'pd_details'    =>  $post['pd_details'],
                'pd_cate'       =>  $post['ca_id'],
                'pd_cate_name'  =>  $pd_cate_name,
                'pd_add_time'   =>  date('y-m-d h:i:s'),
                'pd_image'      =>  $post['pd_image'],
            );
            // halt($list);
            // halt($post['pd_id']);
            $up=db('product')->where('id',$post['pd_id'])->update($list);
            return [
                'code' => 1,
                'msg' => '更改成功',
            ];
        }
        $pd_image=db('product')->where('id',$pd_id)->value('pd_image');
        $pd_image=explode(',',$pd_image);
        // halt($pd_image);
        // halt($post);
        $list=db('product_type')->where('pd_id',$pd_id)->select();
        $info = db('product')->where('id',$pd_id)->find();
        $this->assign(array(
            // 'yuming' => $_SERVER['HTTP_HOST'],
            'list' =>$list,
            'info' => $info,
            'ca_list' => db('cate')->select(),
            'pd_image'=>$pd_image,
        ));
        // halt($info);
        return $this->fetch();
    }

    /**
     * 添加商品
     * author fengkl
     * time 2018年5月24日 15:33:58
     * @return mixed
     */
    public function add()
    {
        if (is_post()) {
            $post=input('post.');
            // halt($post);
            $ct=count($post['type']);
            // halt($post['pd_image']);
            // $post['pd_image']=array_slice($post['pd_image'],1);
            // halt($post['pd_image']);
            if (empty($post['pd_image'][1])) {
                $this->error('请上传商品主图');
            }
            if (empty($post['ca_id'])) {
                $this->error('请选择商品分类');
            }
            $post['pd_image']=array_slice($post['pd_image'],1);
            $post['pd_image']=implode(',',$post['pd_image']);
            // halt($post['pd_image']);
            $pd_cate_name=db('cate')->where('id',$post['ca_id'])->value('fl_name');
            $post['pd_details'] = htmlspecialchars_decode($post['pd_details']);
            $list = array(
                'pd_name'       =>  $post['pd_name'],
                'pd_price'      =>  $post['pd_price'],
                'pd_stock'      =>  $post['pd_stock'],
                'pd_sale'       =>  $post['pd_sale'],
                'pd_details'    =>  $post['pd_details'],
                'pd_cate'       =>  $post['ca_id'],
                'pd_cate_name'  =>  $pd_cate_name,
                'pd_creat_time' =>  date('y-m-d h:i:s'),
                'pd_image'      =>  $post['pd_image'],
            );
            $pd_id=db('product')->insertGetId($list);
            $i=0;
            while ( $i < $ct) {
                $data = array(
                    'pd_id'    => $pd_id,
                    'type'     => $post['type'][$i],
                    'type_sum' => $post['price'][$i],
                    'gold_yd'  => $post['gold'][$i],
                     );
                Db::name('product_type')->insert($data);
                $i++;
            }
            if ($pd_id) {
                $this->success('添加成功');
            }
            else{
                $this->error('添加失败');
            };
        }
        $this->assign(array(
            'yuming' => $_SERVER['HTTP_HOST'],
            'ca_list' => db('cate')->where('fl_type',0)->select(),
            'list'=>db('product_type')->select(),
        ));
        return $this->fetch();
    }

    //上传图片
    public function upload()
    {

        $bb = Container::get('env')->get('ROOT_PATH');
        $file = request()->file('file');
        $info = $file->validate(['size' => '4096000'])
            ->move($bb . 'public/uploads/');
        if ($info) {
            $path = '/uploads/' . $info->getsavename();
            $path = str_replace("\\", "/", $path);
            return $data = array(
                'code' => 1,
                'msg' => '上传成功',
                'data' => $path,
            );
        } else {
            return $data = array(
                'msg' => $file->getError(),
                'code' => 0,
            );
        }
    }

    //列表修改状态
    //fkl
    //2018年6月11日 17:08:23
    public function change(){
        //3 热门商品5个，4热门新品3个，5热门推荐2个
        if (is_post()) {
            //halt(input('post.'));
            if(input('post.value') == 3){
                $result = model('Product')->where('pd_status',input('post.value'))->count();
                // if($result > 4){
                //     return [
                //     'code' => 2,
                //     'msg' => '超过最大限制',
                //     ];
                // }
            }
            if(input('post.value') == 4){
                $result = model('Product')->where('pd_status',input('post.value'))->count();
                // if($result > 2){
                //     return [
                //     'code' => 2,
                //     'msg' => '超过最大限制',
                //     ];
                // }
            }
            if(input('post.value') == 5){
                //halt(1);
                $result = model('Product')->where('pd_status',input('post.value'))->count();
                // if($result > 1){
                //     return [
                //     'code' => 2,
                //     'msg' => '超过最大限制',
                //     ];
                // }
            }
            $rst = model('Product')->editInfo([input('post.key') => input('post.value')], ['id' => input('post.id')]);
            if ($rst) {
                return [
                    'code' => 1,
                    'msg' => '修改成功',
                    'data' => $rst,
                ];
            }
        }
    }


    //列表修改状态
    //fkl
    //2018年6月11日 17:08:23
    public function catechange(){
        if (is_post()) {
            // halt(input('post.'));
            $rst = model('Cate')->edit([input('post.key') => input('post.value')], ['id' => input('post.id')]);
            if ($rst) {
                return [
                    'code' => 1,
                    'msg' => '修改成功',
                    'data' => $rst,
                ];
            }
        }
    }
    //删除
    //fkl
    //2018年6月11日 17:08:23
    public function catedelete(){
        // $every = new Every;
        $data = input('post.');
        // halt($data);
        $dl=db('cate')->where('id',$data['id'])->delete();
        if ($dl) {
            $this->success('删除成功');
        }
        else
        {
            $this->error('请稍后重试');
        }
        // $id = $data['id'];
        // $key = $data['key'];
        // $child_info = model('Cate')->where('p_id',$id)->select();
        //halt(count($child_info));
        // if(count($child_info)){
        //     $this->error('拥有子分类的大分类不能被删除');
        // }
        // $info = model($key)->get($id);
        // if ($info) {
        //     $rel = model($key)->destroy($id);
        //     if ($rel) {
        //         $this->success('删除成功');
        //     } else {
        //         $this->error('请联系网站管理员');
        //     }
        // } else {
        //     $this->error('数据不存在');
        // }
    }

    //删除
    //fkl
    //2018年6月11日 17:08:23
    public function dodelete(){
        // $every = new Every;
        $data = input('post.');
        // halt($data);
        $rel=db('product')->where('id',$data['id'])->delete();
        if ($rel) {
            $this->success('删除成功');
        }
        else{
            $this->error('删除失败');
        }
        // $rel = $every->allDel($data);
    }
    public function taocan_delete()
    {
        $post=input('post.');
        // halt($post);
        $rel=db('taocan')->where('id',$post['id'])->delete();
        if ($rel) {
            $this->success('删除成功');
        }
        else{
            $this->error('删除失败');
        }
    }

    //分类列表
    public function cate()
    {
        // $map['p_id'] = 0;
        $list = model('Cate')->where('fl_type=1 OR fl_type=0')->paginate(10);
        //halt($list);
        $this->assign('list',$list);
        return $this->fetch();
    }

    //添加分类
    public function cate_add()
    {
        if (is_post()) {
            $post=input('post.');
            $list = array(
                'fl_name' => $post['fl_name'],
                'fl_image' => $post['fl_image'],
                'fl_creattime' => date('y-m-d h:i:s'),
            );
            $li=model('Cate')->insertGetId($list);
            if ($li) {
                $this->success('添加成功');
            }
            else{
                $this->error('请稍后重试');
            }
            // halt(input('post.'));
            // $Productlogic = new ProductLogic;
            // $rel = $Productlogic->saveCate(input('post.'));
            // return $rel;
        }
        return $this->fetch();
    }

    public function get_cate()
    {
        $list = model('Cate')->where('st_id', input('post.id'))->select();

        if (count($list)) {
            return $data = [
                'code' => 1,
                'data' => $list,
            ];
        } else {
            return $data = [
                'code' => 0,
            ];
        }
    }

}