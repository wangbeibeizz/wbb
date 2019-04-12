<?php
namespace app\admin\controller;
use PHPExcel;
use PHPExcel_IOFactory;
/**
 * 利润表
 */
class Profit extends Common
{

    public function __construct()
    {
        parent::__construct();
    }
    //财富记录
    public function index()
    {
        if (is_post()) {

            $rst = model('Wallet')->xiugai([input('post.key') => input('post.value')], ['id' => input('post.id')]);
            return $rst;
        }
        if (input('get.keywords')) {
            $us_id = model("User")->where('us_account|us_real_name|us_tel', input('get.keywords'))->value('id');
            if (!$us_id) {
                $us_id = 0;
            }
            $this->map[] = ['us_id', '=', $us_id];
        }
        if (input('get.wa_type') != "") {
            $this->map[] = ['wa_type', '=', input('get.wa_type')];
        }
        $list = model('Wallet')->chaxun($this->map, $this->order, $this->size);
        $this->assign(array(
            'list' => $list,
        ));
        return $this->fetch();
    }
    //提现记录
    public function commission()
    {
        $kouchu=db('jiangli')->where('id',1)->value('tixian');
        $listt=[];
        if (is_get()) {
            if (input('get.keywords')!=null) {
                $listt = db('user')->where('us_nickname',input('get.keywords'))->field('id')->select();
                $listt = array_column($listt,'id');
                if ($listt==[]) {
                    $listt='无';
                }
            }
        }
        if ($listt==[]) {
            $list=model('tixian')->where('type',0)->where('tx_review=1 OR tx_review=3')->order('wc_time desc')->paginate(10);
        }
        else{
            $list=model('tixian')->where('type',0)->where('tx_review=1 OR tx_review=3')->where('us_id','in',$listt)->order('wc_time desc')->paginate(10,false,$config = ['query'=>request()->param()]);
        }
        foreach ($list as $k => $v) {
            $user=db('user')->where('id',$v['us_id'])->find();
            $list[$k]['us_nickname']=$user['us_nickname'];
            $list[$k]['us_tel']=$user['us_tel'];
            $list[$k]['us_name']=$user['us_name'];
            $list[$k]['sum']=$v['tx_sum']*(1-$kouchu);
            if ($v['tx_fangshi']==0) {
                $list[$k]['number']=$user['alipay'];
                $list[$k]['name']=$user['alipay_name'];
            }
            if ($v['tx_fangshi']==1) {
                $list[$k]['number']=$user['wechat'];
                $list[$k]['name']=$user['wechat_name'];
            }
            if ($v['tx_fangshi']==2) {
                $list[$k]['number']=$user['bank_number'];
                $list[$k]['name']=$user['bank_user'];
                $list[$k]['bank_name']=$user['bank_loction'];
            }
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    //奖金明细
    public function jianglimx()
    {
        $map=[];
        $listt=[];
        if (is_get()) {
            $post=input('get.');
            if (input('get.keywords')) {
                $listt = db('user')->where('us_nickname|us_tel',$post['keywords'])->field('id')->select();
                $listt = array_column($listt,'id');
                if ($listt==[]) {
                    $listt='无';
                }
            }
            if (input('get.start') && input('get.end') == "") {
                $map[] = ['re_time', '>=', $post['start']];
            }
            if (input('get.start')=="" && input('get.end')) {
                $map[] = ['re_time', '=<', $post['end']];
            }
            if (input('get.start') && input('get.end')) {
                $map[] = ['re_time', 'between', array($post['start'], $post['end'])];
            }
        }
        if ($listt==[]) {
            $list=model('reward')->where($map)->order('re_time desc')->paginate(10);
        }
        else{
            $list=model('reward')->where($map)->where('us_id','in',$listt)->order('re_time desc')->paginate(10,false,$config = ['query'=>request()->param()]);
        }
        foreach ($list as $key => $value) {
            $user=db('user')->where('id',$value['us_id'])->find();
            $list[$key]['us_nickname']=$user['us_nickname'];
            $list[$key]['us_name']=$user['us_name'];
            $list[$key]['us_tel']=$user['us_tel'];
            $rd_id=db('user')->where('id',$value['rd_id'])->find();
            $list[$key]['rd_id_us_nickname']=$rd_id['us_nickname'];
            $list[$key]['rd_id_us_name']=$rd_id['us_name'];
            $list[$key]['rd_id_us_tel']=$rd_id['us_tel'];
        }
        $count=db('reward')->count();
        $this->assign('count',$count);
        $this->assign('list',$list);
        return $this->fetch();
    }
    //提现申请
    public function commissionapply(){
        $kouchu=db('jiangli')->where('id',1)->value('tixian');
        $listt=[];
        if (is_get()) {
            if (input('get.keywords')!=null) {
                $listt = db('user')->where('us_nickname',input('get.keywords'))->field('id')->select();
                $listt = array_column($listt,'id');
                if ($listt==[]) {
                    $listt='无';
                }
            }
        }
        if ($listt==[]) {
            // $list=model('tixian')->where('type',['=','0'],['=','4'],'or')->order('tx_time desc')->paginate(10);
            $list=model('tixian')->where('type',0)->where('tx_review<>1 AND tx_review<>3')->order('tx_time desc')->paginate(10);
        }
        else{
            $list=model('tixian')->where('type',0)->where('tx_review<>1 AND tx_review<>3')->where('us_id','in',$listt)->order('tx_time desc')->paginate(10,false,$config = ['query'=>request()->param()]);
        }
        foreach ($list as $k => $v) {
            $user=db('user')->where('id',$v['us_id'])->find();
            $list[$k]['us_nickname']=$user['us_nickname'];
            $list[$k]['us_tel']=$user['us_tel'];
            $list[$k]['us_name']=$user['us_name'];
            $list[$k]['sum']=$v['tx_sum']*(1-$kouchu);
            if ($v['tx_fangshi']==0) {
                $list[$k]['number']=$user['alipay'];
                $list[$k]['name']=$user['alipay_name'];
            }
            if ($v['tx_fangshi']==1) {
                $list[$k]['number']=$user['wechat'];
                $list[$k]['name']=$user['wechat_name'];
            }
            if ($v['tx_fangshi']==2) {
                $list[$k]['number']=$user['bank_number'];
                $list[$k]['name']=$user['bank_user'];
                $list[$k]['bank_name']=$user['bank_loction'];
            }
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    //充值申请
    public function chongzhi(){
        $listt=[];
        if (is_get()) {
            if (input('get.keywords')!=null) {
                $listt = db('user')->where('us_nickname',input('get.keywords'))->field('id')->select();
                $listt = array_column($listt,'id');
                if ($listt==[]) {
                    $listt='无';
                }
            }
        }
        if ($listt==[]) {
            // halt(123);
            $list=model('tixian')->where('type',3)->where('tx_review=0 OR tx_review=4')->order('tx_time desc')->paginate(10);
        }
        else{
            // halt($listt);
            $list=model('tixian')->where('type',3)->where('tx_review=0 OR tx_review=4')->where('us_id','in',$listt)->order('tx_time desc')->paginate(10,false,$config = ['query'=>request()->param()]);
        }
        foreach ($list as $key => $v) {
            $user=db('user')->where('id',$list[$key]['us_id'])->find();
            $list[$key]['us_nickname']=$user['us_nickname'];
            $list[$key]['us_tel']=$user['us_tel'];
            $list[$key]['us_name']=$user['us_name'];
            if ($v['tx_fangshi']==0) {
                $list[$key]['tx_type_text']='支付宝';
            }
            if ($v['tx_fangshi']==1) {
                $list[$key]['tx_type_text']='微信';
            }
            if ($v['tx_fangshi']==2) {
                $list[$key]['tx_type_text']='银行卡';
            }
        }
        // halt($list);
        $this->assign('list',$list);
        return $this->fetch();
    }
    //充值记录
    public function chongzhijl()
    {
        $listt=[];
        if (is_get()) {
            if (input('get.keywords')!=null) {
                $listt = db('user')->where('us_nickname',input('get.keywords'))->field('id')->select();
                $listt = array_column($listt,'id');
                if ($listt==[]) {
                    $listt='无';
                }
            }
        }
        if ($listt==[]) {
            // halt(123);
            $list=model('tixian')->where('type',3)->where('tx_review=1 OR tx_review=3')->order('tx_time desc')->paginate(10);
        }
        else{
            // halt($listt);
            $list=model('tixian')->where('type',3)->where('tx_review=1 OR tx_review=3')->where('us_id','in',$listt)->order('tx_time desc')->paginate(10,false,$config = ['query'=>request()->param()]);
        }
        foreach ($list as $key => $v) {
            $user=db('user')->where('id',$list[$key]['us_id'])->find();
            $list[$key]['us_nickname']=$user['us_nickname'];
            $list[$key]['us_tel']=$user['us_tel'];
            $list[$key]['us_name']=$user['us_name'];
            if ($v['tx_fangshi']==0) {
                $list[$key]['tx_type_text']='支付宝';
            }
            if ($v['tx_fangshi']==1) {
                $list[$key]['tx_type_text']='微信';
            }
            if ($v['tx_fangshi']==2) {
                $list[$key]['tx_type_text']='银行卡';
            }
        }
        // halt($list);
        $this->assign('list',$list);
        return $this->fetch();
    }
    //转账记录
    public function zhuanzhang()
    {
        $map=[];$listt=[];
        if (is_get()) {
            if (input('get.keywords')!=null) {
                $listt = db('user')->where('us_nickname',input('get.keywords'))->field('id')->select();
                $listt = array_column($listt,'id');
                if ($listt==[]) {
                    $listt='无';
                }
                // halt($listt);
            }
        }
        if ($listt==[]) {
            // halt(123);
            $list=model('tixian')->where('type',1)->where('zz_type',0)->order('tx_time desc')->paginate(10);
        }
        else{
            // halt($listt);
            $list=model('tixian')->where('type',1)->where('zz_type',0)->where('us_id','in',$listt)->order('tx_time desc')->paginate(10,false,$config = ['query'=>request()->param()]);
        }
        // halt($list);
        foreach ($list as $key => $value) {
            $user=db('user')->where('id',$list[$key]['us_id'])->find();
            $list[$key]['us_nickname']=$user['us_nickname'];
            $list[$key]['us_tel']=$user['us_tel'];
            $list[$key]['us_name']=$user['us_name'];
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function zhuanzhang_cy()
    {
        $map=[];$listt=[];
        if (is_get()) {
            if (input('get.keywords')!=null) {
                $listt = db('user')->where('us_nickname',input('get.keywords'))->field('id')->select();
                $listt = array_column($listt,'id');
                if ($listt==[]) {
                    $listt='无';
                }
                // halt($listt);
            }
        }
        if ($listt==[]) {
            // halt(123);
            $list=model('tixian')->where('type',1)->where('zz_type',1)->order('tx_time desc')->paginate(10);
        }
        else{
            // halt($listt);
            $list=model('tixian')->where('type',1)->where('zz_type',1)->where('us_id','in',$listt)->order('tx_time desc')->paginate(10,false,$config = ['query'=>request()->param()]);
        }
        // halt($list);
        foreach ($list as $key => $value) {
            $user=db('user')->where('id',$list[$key]['us_id'])->find();
            $list[$key]['us_nickname']=$user['us_nickname'];
            $list[$key]['us_tel']=$user['us_tel'];
            $list[$key]['us_name']=$user['us_name'];
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    //给予充值
    public function changee(){
        if (is_post()) {
            $post=input('post.');
            if ($post['value']==1) {
                $list=db('tixian')->where('id',$post['id'])->find();
                if ($list['tx_review']==0) {
                    $this->error('请勿重复点击');
                }
                db('tixian')->where('id',$post['id'])->update(['tx_review'=>1]);
                db('user')->where('id',$list['us_id'])->setInc('us_reg',$list['tx_sum']);
                $this->success('充值成功');
            }
            if ($post['value']==3) {
                db('tixian')->where('id',$post['id'])->update(['tx_review'=>3]);
                $this->success('已驳回');
            }
        }
    }
    //给予提现
    public function change(){
        if (is_post()) {
            $post=input('post.');
            $tx = db('tixian')->where('id',$post['id'])->find();
            if ($post['value']==1) {
                $list=db('tixian')->where('id',$post['id'])->update([
                    'tx_review' =>1,
                    'wc_time'   =>date('y-m-d H:i:s'),
                ]);
                if ($list) {
                    $this->success('审核成功');
                }
            }
            if ($post['value']==3) {
                if ($tx['tx_review']==3) {
                    $this->error('请勿重复点击');
                }
                db('tixian')->where('id',$post['id'])->update([
                    'tx_review' =>3,
                    'wc_time'   =>date('y-m-d H:i:s'),
                ]);
                model('user')->where('id',$tx['us_id'])->setInc('us_cash',$tx['tx_sum']);
                $this->success('已驳回');
            }
            if ($post['value']==5) {
                $list=db('tixian')->where('id',$post['id'])->update([
                    'tx_review' =>5,
                ]);
                if ($list) {
                    $this->success('审核成功');
                }
            }
        }
    }
    //微信支付记录
    public function wechat()
    {
        if (is_post()) {

            $rst = model('Order')->xiugai([input('post.key') => input('post.value')], ['id' => input('post.id')]);
            return $rst;
        }
        if (input('get.keywords')) {
            $us_id = model("User")->where('us_account|us_real_name|us_tel', input('get.keywords'))->value('id');
            if (!$us_id) {
                $us_id = 0;
            }
            $this->map[] = ['us_id', '=', $us_id];
        }
        if (input('get.wa_type') != "") {
            $this->map[] = ['wa_type', '=', input('get.wa_type')];
        }
        $list = model('PayWechat')->chaxun($this->map, $this->order, $this->size);

        $this->assign(array(
            'list' => $list,
        ));
        return $this->fetch();
    }
    public function excel(){//导出Excel
        // halt(123);
        $xlsName  = "export";
        $xlsCell  = array(
            array('id','ID'),
            array('us_nickname','昵称'),
            array('us_name','姓名'),
            array('us_tel','手机号'),
            array('rd_mony','奖励金额'),
            array('type','奖励类型'),
            array('re_time','产生奖励时间'),
            // array('tx_add_time','审批时间'),
        );
        // $this->map[] = ['tx_status', 'eq', 1];
        //$field = 'a.*,b.us_nickname';
        $xlsData  = model('reward')->order('re_time desc')->select();
        // halt($xlsData);
        foreach ($xlsData as $k => $v) {
            $user=model('User')->where('id',$v['us_id'])->find();
            $xlsData[$k]['us_nickname']=$user['us_nickname'];
            $xlsData[$k]['us_name']=$user['us_name'];
            $xlsData[$k]['us_tel']=$user['us_tel'];
            if ($v['re_type']==0) {
                $xlsData[$k]['type']='直推奖励';
            }
            if ($v['re_type']==1) {
                $xlsData[$k]['type']='自身出局奖励';
            }
            if ($v['re_type']==2) {
                $xlsData[$k]['type']='首节点人出局奖励';
            }
        }
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
    public function zzexcel(){//导出Excel购物积分转账记录
        // halt(123);
        $xlsName  = "export";
        $xlsCell  = array(
            array('id','ID'),
            array('us_nickname','昵称'),
            array('us_name','姓名'),
            array('us_tel','手机号'),
            array('tx_name','收款人姓名'),
            array('tx_tel','收款人电话'),
            array('tx_sum','转账金额'),
            array('tx_time','转账时间'),
        );
        $xlsData=model('tixian')->where('type',1)->where('zz_type',0)->order('tx_time desc')->select();
        foreach ($xlsData as $key => $value) {
            $user=db('user')->where('id',$xlsData[$key]['us_id'])->find();
            $xlsData[$key]['us_nickname']=$user['us_nickname'];
            $xlsData[$key]['us_tel']=$user['us_tel'];
            $xlsData[$key]['us_name']=$user['us_name'];
        }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }
    public function zzexcel_cy(){//导出Excel创业积分转账记录
        // halt(123);
        $xlsName  = "export";
        $xlsCell  = array(
            array('id','ID'),
            array('us_nickname','昵称'),
            array('us_name','姓名'),
            array('us_tel','手机号'),
            array('tx_name','收款人姓名'),
            array('tx_tel','收款人电话'),
            array('tx_sum','转账金额'),
            array('tx_time','转账时间'),
        );
        $xlsData=model('tixian')->where('type',1)->where('zz_type',1)->order('tx_time desc')->select();
        foreach ($xlsData as $key => $value) {
            $user=db('user')->where('id',$xlsData[$key]['us_id'])->find();
            $xlsData[$key]['us_nickname']=$user['us_nickname'];
            $xlsData[$key]['us_tel']=$user['us_tel'];
            $xlsData[$key]['us_name']=$user['us_name'];
        }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }
    public function txexcel(){//导出Excel
        // halt(123);
        $kouchu=db('jiangli')->where('id',1)->value('tixian');
        $xlsName  = "export";
        $xlsCell  = array(
            array('id','ID'),
            array('us_nickname','昵称'),
            array('us_name','姓名'),
            array('us_tel','手机号'),
            array('tx_sum','提现金额'),
            array('sum','到账金额'),
            array('number','收款账号'),
            array('asd','账号类型'),
            array('name','收款人'),
            array('wc_time','审核完成时间'),
        );
        // $xlsData=model('tixian')->where('type',1)->order('tx_time desc')->select();
        // foreach ($xlsData as $key => $value) {
        //     $user=db('user')->where('id',$xlsData[$key]['us_id'])->find();
        //     $xlsData[$key]['us_nickname']=$user['us_nickname'];
        //     $xlsData[$key]['us_tel']=$user['us_tel'];
        //     $xlsData[$key]['us_name']=$user['us_name'];
        // }
        $xlsData=model('tixian')->where('type',0)->where('tx_review',1)->order('wc_time desc')->select();
        foreach ($xlsData as $k => $v) {
            $user=db('user')->where('id',$v['us_id'])->find();
            $xlsData[$k]['us_nickname']=$user['us_nickname'];
            $xlsData[$k]['us_tel']=$user['us_tel'];
            $xlsData[$k]['us_name']=$user['us_name'];
            $xlsData[$k]['sum']=$v['tx_sum']*(1-$kouchu);
            if ($v['tx_fangshi']==0) {
                $xlsData[$k]['number']=$user['alipay'];
                $xlsData[$k]['name']=$user['alipay_name'];
            }
            if ($v['tx_fangshi']==1) {
                $xlsData[$k]['number']=$user['wechat'];
                $xlsData[$k]['name']=$user['wechat_name'];
            }
            if ($v['tx_fangshi']==2) {
                $xlsData[$k]['number']=$user['bank_number'];
                $xlsData[$k]['name']=$user['bank_user'];
                $xlsData[$k]['bank_name']=$user['bank_loction'];
            }
            if ($v['tx_fangshi']==0) {
                $xlsData[$k]['asd']="支付宝";
            }
            if ($v['tx_fangshi']==1) {
                $xlsData[$k]['asd']="微信";
            }
            if ($v['tx_fangshi']==2) {
                $xlsData[$k]['asd']=$user['bank_loction'];
            }
        }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }
    public function czexcel(){//导出Excel
        // halt(123);
        $xlsName  = "export";
        $xlsCell  = array(
            array('id','ID'),
            array('us_nickname','昵称'),
            array('us_tel','手机号'),
            array('us_name','提现人'),
            array('tx_sum','实发金额'),
            array('tx_type_text','类型'),
            array('tx_time','申请时间'),
            array('zhuangtai','状态'),
        );
        // $xlsData=model('tixian')->where('type',1)->order('tx_time desc')->select();
        // foreach ($xlsData as $key => $value) {
        //     $user=db('user')->where('id',$xlsData[$key]['us_id'])->find();
        //     $xlsData[$key]['us_nickname']=$user['us_nickname'];
        //     $xlsData[$key]['us_tel']=$user['us_tel'];
        //     $xlsData[$key]['us_name']=$user['us_name'];
        // }


        $xlsData=model('tixian')->where('type',3)->where('tx_review','<>',1)->order('wc_time desc')->select();
        foreach ($xlsData as $key => $v) {
            $user=db('user')->where('id',$xlsData[$key]['us_id'])->find();
            $xlsData[$key]['us_nickname']=$user['us_nickname'];
            $xlsData[$key]['us_tel']=$user['us_tel'];
            $xlsData[$key]['us_name']=$user['us_name'];
            if ($v['tx_fangshi']==0) {
                $xlsData[$key]['tx_type_text']='支付宝';
            }
            if ($v['tx_fangshi']==1) {
                $xlsData[$key]['tx_type_text']='微信';
            }
            if ($v['tx_fangshi']==2) {
                $xlsData[$key]['tx_type_text']='银行卡';
            }
            $xlsData[$key]['zhuangtai']="审核通过";
        }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }
}
