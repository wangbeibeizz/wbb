<?php
namespace app\shop\Controller;

use app\admin\logic\ProductLogic;
use app\common\model\Product as ProductModel;
use think\Db;
use think\Container;
/**
 * 用户控制器
 */
//fkl
class Goods extends Basis
//class Goods extends Common
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 商品列表
     * author fengkl
     * time 2018年5月29日 10:46:36
     * @return mixed
     */
    public function index()
    {
        $Product = new ProductLogic;
        $this->map['st_id'] = $this->shop['id'];
        $cate = model('cate')->where('p_id',0)->select();
        $cate[0]['st_id'] = input('get.id');
        foreach ($cate as $k => $v) {
            $cate[$k]['erji'] = model('cate')->where('p_id',$v['id'])->select();
            $cate[$k]['title'] = $v['ca_name'];           
            $this->map['b.p_id'] = $v['id'];
            $cate[$k]['list'] = $Product->getList($this->map, $this->order, $this->size);
            /*foreach ($cate[$k]['list'] as $key => $value) {
                $pic_info = explode(',', $value['pd_pic']);
                if(!$pic_info[0]){
                    array_shift($pic_info);
                }
                $cate[$k]['list'][$key]['pd_pic'] = $pic_info;
            }*/           
        }
        //halt($cate);
        // var_dump($list);
        $this->s_msg(null, $cate);

    }

    /**
     * 商品详情
     * author fengkl
     * time 2018年5月31日 15:25:52
     * @return mixed
     */
    public function detail()
    {
        $id = input('post.pd_id');
        if(!$id){
            $this->e_msg('无法获取商品ID', 401);
        }       
        $info = model('product')
            ->alias('a')
            ->field('a.*, b.ca_name')
            ->join('cate b', 'a.ca_id = b.id','LEFT')
            ->where('a.id',$id)
            ->find();
        $pics = explode(",", $info['pd_pic']);
        if(!$pics[0]){
            array_shift($pics);
        }
        $info['pd_pic'] = $pics;
        
        $this->s_msg(null, $info);
        //var_dump($info['pd_pic']);exit();
    }

    //分类列表
    public function cate(){
        $ca_list = db('cate')->where('p_id','gt',0)->select();
        //$ca_list = db('cate')->select();
        $this->s_msg(null, $ca_list);
    }

    /**
     * 添加商品
     * author fengkl
     * time 2018年5月29日 11:57:41
     * @return mixed
     */
    public function add()
    {
        if (is_post()) {
            $data = input('post.');
            $data['st_id'] = $this->shop['id'];
            $Productlogic = new ProductLogic;
            $rel = $Productlogic->saveProduct($data);
            $this->msg($rel);           
        }
    }

    /**
     * 修改商品
     * author fengkl
     * time 2018年5月29日 14:13:06
     * @return mixed
     */
    public function edit()
    {
        $Productlogic = new ProductLogic;
        if (input('post.pd_id')) {
            $data = input('post.');
            $rel = $Productlogic->saveProduct($data,1);            
            $this->msg($rel);
        }
        $this->e_msg('无法获取商品ID');        
    }

}
