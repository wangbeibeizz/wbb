<?php /*a:3:{s:80:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\profit\jianglimx.html";i:1548231423;s:76:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_meta.html";i:1524812002;s:78:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_footer.html";i:1535678602;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="Bookmark" href="/favicon.ico" >
<link rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5shiv.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/page.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui.admin/css/style.css" />
<style>
	.table-bg thead th {
	     background-color:rgba(255,255,255,0); 
	}
	.bg-1 {
	    background-color:rgba(255,255,255,0);
	}
</style>
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title></title>
</head>

<body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span>财务管理 <span class="c-gray en">&gt;</span> 奖金明细
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a>

        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" onclick="excel()" title="导出Excel文档"><i class="Hui-iconfont">&#xe681;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form class="Huiform" method="get" action="">
            </span>
                <input type="text" class="input-text" style="width:150px" placeholder="用户姓名、手机号" id="" name="keywords" value="<?php echo htmlentities((app('request')->get('keywords') ?: '')); ?>">
                日期范围：
                <input type="text" class="input-text Wdate" name="start" id="countTimestart" onfocus="selecttime(1)" value="<?php echo htmlentities((app('request')->get('start') ?: '')); ?>" size="17" class="date" readonly style="width:140px;">
                -
                <input type="text" class="input-text Wdate" name="end" id="countTimeend" onfocus="selecttime(2)" value="<?php echo htmlentities((app('request')->get('end') ?: '')); ?>" size="17"  class="date" readonly style="width:140px;">
                <button type="submit" class="btn btn-success radius" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
            </form>
        </div>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="r">共有数据：<strong></strong><?php echo htmlentities($count); ?>条</span> </div>
        <div class="mt-20">
            <table class="table table-border  table-hover table-bg table-sort">
                <thead>
                    <tr class="text-c">
                        <th>id</th>
                        <th>昵称</th>
                        <th>手机号</th>
                        <th>姓名</th>
                        <th>奖励金额</th>
                        <th>奖励类型</th>
                        <th>奖励详情</th>
                        <th>奖励产生时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr class="text-c">
                        <td><?php echo htmlentities($vo['id']); ?></td>
                        <td><?php echo htmlentities($vo['us_nickname']); ?></td>
                        <td><?php echo htmlentities($vo['us_tel']); ?></td>
                        <td><?php echo htmlentities($vo['us_name']); ?></td>

                        <td><?php echo htmlentities($vo['rd_mony']); ?></td>
                        <?php if($vo['re_type'] == 0): ?>
                        <td>分享奖</td>
                        <?php endif; if($vo['re_type'] == 1): ?>
                        <td>我的目标达成奖励</td>
                        <?php endif; if($vo['re_type'] == 2): ?>
                        <td>首节点人达成奖励</td>
                        <?php endif; if($vo['re_type'] == 3): ?>
                        <td>分享的人消费奖励</td>
                        <?php endif; if($vo['re_type'] == 0): ?>
                        <td>分享<?php echo htmlentities($vo['rd_id_us_nickname']); ?>获得</td>
                        <?php endif; if($vo['re_type'] == 1): ?>
                        <td>自身目标达成</td>
                        <?php endif; if($vo['re_type'] == 2): ?>
                        <td>首节点人<?php echo htmlentities($vo['rd_id_us_nickname']); ?>达成奖励</td>
                        <?php endif; if($vo['re_type'] == 3): ?>
                        <td><?php echo htmlentities($vo['rd_id_us_nickname']); ?>消费产生奖励</td>
                        <?php endif; ?>
                        <td><?php echo htmlentities($vo['re_time']); ?></td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        <div class="pages" style="margin:20px;float: right; "><?php echo $list; ?></div>
        </div>
        <script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui.admin/js/H-ui.admin.js"></script> 
<script type="text/javascript" src="/static/admin/static/gojs/go.js"></script>
<script>
	var aa = "<?php echo htmlentities(app('request')->controller()); ?>";
	var bb = "<?php echo htmlentities(app('request')->action()); ?>";
	// console.log(aa+"/"+bb);
</script>
<?php if(app('session')->get('admin.id') != '1'): ?>
	<script type="text/javascript">
	var nodeData = [<?php echo htmlentities(app('session')->get('rules')); ?>];
	$('.rule_node').addClass('hidden');
	$.each(nodeData, function (index, value) {
		$('.sidebar').find('li[data-node-id="' + value + '"]').removeClass('hidden');
		$('.sidebar').find('dt[data-node-id="' + value + '"]').removeClass('hidden');
	});
	</script>
<?php endif; ?>

        <!--请在下方写此页面业务相关的脚本-->
        <script type="text/javascript" src="/static/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
        <script type="text/javascript">
        $('select[name="wa_type"]').val(<?php echo htmlentities(app('request')->get('wa_type')); ?>);

        function create(id, url, key) {
            var url = "<?php echo url('" + url + "'); ?>?id=" + id;
            creatIframe(url, key);
        }

        function change(id, value, key) {
            layer.confirm('确定已发放提现吗？', {
                btn: ['确定', '取消']
            }, function(index, layero) {
                $.ajax({
                    type: "post",
                    url: "<?php echo url('change'); ?>",
                    data: { id: id, value: value, key: key },
                    success: function(data) {
                        if (data) {
                            location.href = '';
                        }
                    }
                });
            });
        }

        function del(id, key) {
            layer.confirm('确定要删除么？', {
                btn: ['确定', '取消']
            }, function(index, layero) {
                $.ajax({
                    type: "post",
                    url: "<?php echo url('Every/allDel'); ?>",
                    data: { id: id, key, key },
                    success: function(data) {
                        layer.msg(data.msg);
                        if (data.code == 1) {
                            setTimeout(function() {
                                location.href = data.url;
                            }, 1000);
                        }
                    }
                });
            });
        }
        // function excel() {
        //     layer.confirm('导出Excel文档', {
        //         btn: ['确定', '取消']
        //     }, function() {
        //         $.ajax({
        //             url: "<?php echo url('profit/excel'); ?>",
        //         });
        //     });
        // }
        function excel(){
            var url = "<?php echo url('excel'); ?>";
            creatIframe(url,'表格导出');
        }
        function selecttime(flag){
            if(flag==1){
                var endTime = $("#countTimeend").val();
                if(endTime != ""){
                    WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:endTime})}else{
                    WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
            }else{
                var startTime = $("#countTimestart").val();
                if(startTime != ""){
                    WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',minDate:startTime})}else{
                    WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
            }
        }
        </script>
</body>

</html>