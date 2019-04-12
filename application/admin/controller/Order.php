<?php
/**
 * Created by fengkl
 * User: Administrator
 * Date: 2018年5月25日 10:38:27
 * 
 */

namespace app\admin\controller;

use app\admin\logic\OrderLogic;
use app\common\model\Order as OrderModel;
use think\Db;
use think\Container;
use PHPExcel_IOFactory;
use PHPExcel;

class Order extends Common
{
    /**
     * 订单列表
     * author fengkl
     * time 2018年5月25日 10:40:07
     * @return mixed
     */
    public function index()
    {
        $map=[];
        if (is_get()) {
            if (input('get.or_status')==1) {
                $map['or_type']=0;
            }
            if (input('get.or_status')==2) {
                $map['or_type']=1;
            }
            if (input('get.or_status')==3) {
                $map['or_type']=2;
            }
            if (input('get.or_status')==4) {
                $map['or_type']=3;
            }
            if (input('get.or_num')!=null) {
                $map['id']=input('get.or_num');
            }
            if (input('get.start') && input('get.end') == "") {
                $map[] = ['or_creat_time', '>=', input('get.start')];
            }
            if (input('get.start')=="" && input('get.end')) {
                $map[] = ['or_creat_time', '=<', input('get.end')];
            }
            if (input('get.start') && input('get.end')) {
                $map[] = ['or_creat_time', 'between', array(input('get.start'), input('get.end'))];
            }
        }
        $list=model('order')->where($map)->order('or_creat_time desc')->paginate(10);
        foreach ($list as $key => $v) {
            $user=model('User')->where('id',$v['us_id'])->find();
            $list[$key]['us_name']=$user['us_name'];
            $list[$key]['us_tel']=$user['us_tel'];
            $list[$key]['us_node_time']=$user['us_node_time'];
            $list[$key]['tuijian_number']=model('user')->where('us_zhitui',$user['id'])->count();
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function zhuceindex()
    {
        $list=model('taocan_dingdan')->order('creat_time desc')->paginate(10);
        // halt($list);
        // $list = $list->all();
        foreach ($list as $key => $value) {
            // $list[$key]['us_tel']= '哈哈哈';
            $list[$key]['us_tel']=model('User')->where('id',$value['us_id'])->value('us_tel');
        }
        // halt($list);
        $this->assign('list',$list);
        return $this->fetch();
        // halt($list);
    }

    public function taocan_dingdan()
    {
        $post=input('post.');
        $list=db('taocan_dingdan')->delete($post['id']);
        if ($list) {
            $this->success('删除成功！');
        }
        else
        {
            $this->error('请稍后重试');
        }
    }

    public function dodeliverr()
    {
        // halt(input('id'));
        $up=db('taocan_dingdan')->where('id',input('id'))->update(['type'=>1]);
        if ($up) {
            $this->success('已发货');
        }
        else
        {
            $this->error('请稍后重试');
        }
    }
    public function zhuce_details()
    {
        $dingdan=db('taocan_dingdan')->where('id',input('id'))->find();
        $dizhi=db('dizhi')->where('id',$dingdan['dz_id'])->find();
        $taocan=db('taocan')->where('id',$dingdan['tc_id'])->find();
        // halt($taocan);
        $this->assign('dizhi',$dizhi);
        $this->assign('taocan',$taocan);
        return $this->fetch();
    }
    //修改发货状态
    //fkl
    //2018年6月13日 16:25:10
    public function change(){
        if (is_post()) {
            $rst = model('Order')->editInfo([input('post.key') => input('post.value')], ['id' => input('post.id')]);
            //halt($rst);
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
    public function dodelete(){
        $info=input('id');
        // db('dingdan')->where('id',$info)->delete();
        $a=db('order')->where('id',$info)->delete();
        $b=db('order_detailed')->where('od_id',$info)->delete();
        if ($a&&$b) {
            $this->success('删除成功！');
        }
        else{
            $this->error('请稍后重试');
        }
    }

    public function getdetail($id)
    {
        $map['or_id'] = $id;
        $list = model('OrderDetail')->detail($map);
        $total = 0;
        foreach ($list as $k => $v) {
            $st_map['id'] = $v['st_id'];
            $st_info = model('Store')->where($st_map)->find();
            $list[$k]['st_name'] = $st_info['st_name'];
            $area_id = $st_info['area_id'];
            $total += $v['or_pd_price'] * $v['or_pd_num'];
            $pics = explode(",", $v['or_pd_pic']);
                    if(!$pics[0]){
                        array_shift($pics);
                    }
                    $list[$k]['or_pd_pic'] = $pics;
        }
        $list['total'] = $total;
        $list['area_id'] = $area_id;
        $addr = model('Order')->getAddr($id);
        $addr['addr_add'] = $addr['addr_addr'].$addr['addr_detail'];
        //halt($list);
        return array(
            'list' => $list,
            'addr' => $addr,
        );
    }


    /**
     * 订单详情
     * author fengkl
     * time 2018年5月25日 11:38:36
     * @return mixed
     */
    public function detail($id)
    {
        $info=input('id');
        // halt(123);
        $order=db('order')->where('id',$info)->find();
        $dizhi=db('dizhi')->where('id',$order['or_address_id'])->find();
        $product=db('order_detailed')->where('od_id',$info)->select();
        // halt($product);
        $this->assign('dizhi',$dizhi);
        $this->assign('product',$product);
        return $this->fetch();
    }
    //发货
    //2018年6月28日 19:00:32
    //fkl
    public function deliver($id)
    {
        $info=input('id');
        halt($info);
        $data=db('dingdan')->where('id',$info)->find();
        // if (is_post()) {
        //     $post=input('post.');
        //     halt($post);
        //     db('dingdan')->where('id',$info)->update(['type'=>3]);
        // }
        $this->assign('id',$id);
        return $this->fetch();
    }
    /**
     * 发货
     * author fengkl
     * time 2018年5月29日 15:46:40
     * @return mixed
     */
    public function dodeliver()
    {
        if (db('order')->where('id',input('id'))->value('or_type')==0) {
            $this->error('该订单未支付');
        }
        $up=db('order')->where('id',input('id'))->update(['or_type'=>2]);
        $date=date('y-m-d H:i:s');
        db('order')->where('id',input('id'))->update(['or_fh_time'=>$date]);
        if ($up) {
            zhitui(input('id'));
            $this->success('已发货');
        }
        else{
            $this->error('请勿重复发货');
        }
    }
    public function record()
    {
        $Order = new OrderLogic;
        if (input('get.or_num')) {
            $this->map[] = ['or_num', '=', input('get.or_num')];
        }
        if (is_numeric(input('get.or_status'))) {
            $this->map[] = ['or_status', '=', input('get.or_status')];
        }
        $this->map[] = ['or_status', '=', 2];
        if (is_numeric(input('get.st_id'))) {
            $this->map[] = ['st_id', '=', input('get.st_id')];
        }
        if (input('get.start') && input('get.end') == "") {
            $this->map[] = ['or_add_time', '>=', input('get.start')];
        }
        if (input('get.start') == "" && input('get.end')) {
            $this->map[] = ['or_add_time', '=<', input('get.end')];
        }
        if (input('get.start') && input('get.end')) {
            $this->map[] = ['or_add_time', 'between', array(input('get.start'), input('get.end'))];
        }
        if (is_post()) {
            $rst = model('Order')->editInfo([input('post.key') => input('post.value')], ['id' => input('post.id')]);
            if ($rst) {
                return [
                    'code' => 1,
                    'msg' => '修改成功',
                    'data' => $rst,
                ];
            } 
        }
        $ad_info = session('admin');
        //halt($ad_info);
        $st_id_arr = 0;
        if($ad_info['ro_id'] == 7){
            $area_info = model('area_store')->where('ad_id',$ad_info['id'])->select();
            foreach ($area_info as $k => $v) {
                $store_info = model('store')->where('area_id',$v['id'])->select();               
                foreach ($store_info as $key => $value) {
                    $st_id_arr = $st_id_arr.','.$value['id'];
                }
            } 
            //halt($st_id_arr); 
            $this->map[] = ['st_id', 'in', $st_id_arr];       
        }elseif($ad_info['ro_id'] == 8) {
            $store_info = model('store')->where('ad_id',$ad_info['id'])->select();               
            foreach ($store_info as $key => $value) {
                $st_id_arr = $st_id_arr.','.$value['id'];
            }
            //halt($st_id_arr);
            $this->map[] = ['st_id', 'in', $st_id_arr];
        }
        $list = $Order->getList($this->map, $this->order, $this->size);
        foreach ($list as $k => $v) {
           $total = $this->getdetail($v['id']);
           //halt($total);
           $list[$k]['or_total'] = $total['list']['total'];
           $list[$k]['area_id'] = $total['list']['area_id'];
        }
        //halt($list);
        $st_list = db('store')->select();
        $this->assign(array(
            'yuming' => $_SERVER['HTTP_HOST'],
            'list' => $list,
            'st_list' => $st_list,
        ));
        return $this->fetch();
    }
    public function excel(){//导出Excel
        $xlsName  = "export";
        $xlsCell  = array(
            array('id','订单ID'),
            array('us_name','姓名'),
            array('us_tel','电话'),
            array('us_node_time','晋级次数'),
            array('tuijian_number','推荐人数'),
            array('or_sum','订单金额'),
            array('type','订单状态'),
            array('or_creat_time','下单时间'),
            array('dz_name','收货人'),
            array('dz_dizhi','收货地址'),
            array('dz_tel','收货人联系方式'),
            array('product','订单商品'),
        );
        $xlsData=model('order')->order('or_creat_time desc')->select();
        foreach ($xlsData as $key => $v) {
            $user=model('User')->where('id',$v['us_id'])->find();
            $xlsData[$key]['us_name']=$user['us_name'];
            $xlsData[$key]['us_tel']=$user['us_tel'];
            $xlsData[$key]['us_node_time']=$user['us_node_time'];
            $xlsData[$key]['tuijian_number']=model('user')->where('us_zhitui',$user['id'])->count();
            if ($xlsData[$key]['or_type']==0) {
                $xlsData[$key]['type']='未支付';
            }
            if ($xlsData[$key]['or_type']==1) {
                $xlsData[$key]['type']='已支付';
            }
            if ($xlsData[$key]['or_type']==2) {
                $xlsData[$key]['type']='已发货';
            }
            if ($xlsData[$key]['or_type']==3) {
                $xlsData[$key]['type']='已确认收货';
            }
            $dizhi=db('dizhi')->where('id',$xlsData[$key]['or_address_id'])->find();
            $xlsData[$key]['dz_name']=$dizhi['us_name'];
            $xlsData[$key]['dz_dizhi']=$dizhi['province'].'/'.$dizhi['city'].'/'.$dizhi['area'].'/'.$dizhi['us_detailed'];
            $xlsData[$key]['dz_tel']=$dizhi['us_tel'];
            // $xlsData[$key]['product']=='';
            $product=db('order_detailed')->where('od_id',$xlsData[$key]['id'])->select();
            foreach ($product as $k => $v) {
                if (empty($pd)) {
                    $pd='';
                }
                $pd=$pd.$v['pd_name'].','.'数量:'.$v['gw_number'].','.'总价：'.$v['pd_sum'].'/';
            }
            $xlsData[$key]['product']=$pd;
        }
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