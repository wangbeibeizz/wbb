<?php
namespace app\admin\controller;

use Cache;
use app\admin\controller\Every;

/**
 * @todo 配置信息管理
 */
class Setting extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }
    // --- ---------------------------------------------------------------------
    public function index()
    {
        // $list=session('admin');
        // halt($list);
        $list=db('admin')->where('id',1)->find();
        if (is_post()) {
            $data = input('post.');
            // halt($data);
            if (!$data['ad_bank']) {
                $data['ad_bank']=$list['ad_bank'];
            }
            if (!$data['ad_bank_name']) {
                $data['ad_bank_name']=$list['ad_bank_name'];
            }
            if (!$data['ad_bank_number']) {
                $data['ad_bank_number']=$list['ad_bank_number'];
            }
            if (!$data['ad_wechat_name']) {
                $data['ad_wechat_name']=$list['ad_wechat_name'];
            }

            if (!$data['ad_wechat']) {
                $data['ad_wechat']=$list['ad_wechat'];
            }

            if (!$data['ad_alipay_name']) {
                $data['ad_alipay_name']=$list['ad_alipay_name'];
            }
            if (!$data['ad_alipay']) {
                $data['ad_alipay']=$list['ad_alipay'];
            }
            // if (!$data['ad_pwd']) {
            //     $data['ad_pwd']=$list['ad_pwd'];
            // }
            if (!$data['wechat_image']) {
                $data['wechat_image']=$list['wechat_image'];
            }
            if (!$data['ad_pwd']) {
                $data['ad_pwd']=$list['ad_pwd'];
            }
            else{
                $data['ad_pwd']=encrypt($data['ad_pwd']);
            }
                $a=db('admin')->where('ad_tel',$list['ad_tel'])->update($data);
            // }
            if ($a) {
                $this->success('修改成功!');
            }
            else{
                $this->error('未修改！');
            }
            // model('Config')->xiugai($data);
        }
        // halt($list);
        $a=$this->assign('list',$list);
        // halt($a);
        return $this->fetch();
    }

    public function jiangli()
    {
        $list=db('jiangli')->find();
        if (is_post()) {
            $post=input('post.');
            if ($post['zhitui']==null||$post['zhitui_hd']==null||$post['chuju']==null||$post['chuju_hd']==null||$post['tixian']==null) {
                $this->error('请输入数据');
            }
            $up=db('jiangli')->where('id',1)->update($post);
            if ($up) {
                $this->success('修改成功');
            }
            else{
                $this->error('未修改');
            }
        }
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function label()
    {
        if (is_post()) {
            $data = input('post.');
            $datb = $data;
            $datc = array_pop($data);
            if ($datc['name'] == "") {
                $datd = $data;
            } else {
                $datd = $datb;
            }
            $datd = serialize($datd);
            $rel = model("Config")->where('key', 'label')->update(['value' => $datd]);
            Cache::clear();
            if ($rel) {
                $this->success('修改成功');
            } else {
                $this->error('您并没有做出修改');
            }
        }
        $label = cache('setting')['label'];
        $array = [
            'name' => "",
            'pic' => "",
        ];
        $list = unserialize(cache('setting')['label']);
        array_push($list, $array);
        $this->assign(array(
            'list' => $list,
        ));
        return $this->fetch();
    }
    public function shuffling(){
        $list=db('lunbotu')->select();
        $this->assign(array(
            'list' => $list,
        ));
        return $this->fetch();
    }
    public function shufflingadd(){
        // halt(123);
        if (is_post()){
            // halt(input('post.'));
            $post['lb_image']=input('ca_pic');
            $post['lb_time']=date('y-m-d h:i:s');
            // halt($post);
            $info=db('lunbotu')->insertGetId($post);
            if ($info) {
                $this->success('添加成功');
            }
            else
            {
                $this->error('请稍后重试');
            }
        }
        // $field = 'a.*, b.ca_name, b.p_id';
        // $goods = model('product')->getListAll($field);
        // $this->assign(array(
        //     'goods' => $goods,
        // ));
        return $this->fetch();
    }
    //删除
    //fkl
    //2018年6月11日 17:08:23
    public function shuffdelete(){
        // $every = new Every;
        $info = input('id');
        $data=db('lunbotu')->where('id',$info)->delete();
        if ($data) {
            $this->success('已删除');
        }else{
            $this->error('请稍后重试');
        }
        // echo $data;
        // halt($data);
        //halt($data);
        // $rel = $every->allDel($data);
    }

    public function shuffling111()
    {
        if (is_post()) {
            $data = input('post.');
            //halt($data);
            $datb = $data;
            $datc = array_pop($data);
            // dump($data);
            // dump($datb);
            // halt($datc);
            if ($datc == "") {
                $datd = $data;
            } else {
                $datd = $datb;
            }
            $datd = implode(',', $datd);
            $rel = model("Config")->where('key', 'shuffling_figure')->update(['value' => $datd]);
            Cache::clear();
            if ($rel) {
                $this->success('修改成功');
            } else {
                $this->error('您并没有做出修改');
            }
        }
        $shuffling = cache('setting')['shuffling_figure'];
        $array = explode(',', $shuffling);
        if($array[0]){
            array_push($array, '');
        }
        $field = 'a.*, b.ca_name, b.p_id';    
        $goods = model('product')->getListAll($field);  
        $this->assign(array(
            'array' => $array,
            'goods' => $goods,
        ));
        return $this->fetch();
    }

    //项目文档
    public function api()
    {
        return $this->fetch();
    }
    public function document()
    {
        $path = env('ROUTE_PATH');
        $swagger = \Swagger\scan($path);
        header('Content-Type: application/json');
        echo $swagger;
    }
    public function change(){
        if (is_post()) {
            $rst = model('shuff')->editShuff([input('post.key') => input('post.value')], ['id' => input('post.id')]);
            return $rst;
        }
    }
}
