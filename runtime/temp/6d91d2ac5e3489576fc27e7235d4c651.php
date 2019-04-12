<?php /*a:3:{s:80:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\order\zhuceindex.html";i:1539331203;s:76:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_meta.html";i:1524812002;s:78:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_footer.html";i:1535678602;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="/static/viewer.min.css"/>
<title></title>
<style>	
		input{
			width:30px;
			border:none;
		}

</style>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span>订单管理 <span class="c-gray en">&gt;</span> 套餐订单列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="text-c">
	<!-- <form class="Huiform" method="get" action="" target="_self">
		状态：
		<span class="select-box inline">
			<select name="or_status" class="select">
				<option value="">全部</option> 
				<option value="0">未支付</option> 
				<option value="1">已支付</option>
				<option value="3">已收货</option>
			</select>
		</span>&nbsp;&nbsp;
		<button type="submit" class="btn btn-success radius" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
	</form> -->
	</div>
	<div class="cl pd-5 bg-1 bk-gray mt-20">
		<!-- <span class="l">
			<a href="javascript:;" onclick="create(0,'add','添加订单')" class="btn btn-primary radius">
			<i class="Hui-iconfont">&#xe600;</i> 订单</a>
		</span>  -->
		<span class="r"><strong></strong></span> </div>
	<div class="mt-20">
	<table class="table table-border  table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th>ID</th>
				<!-- <th>用户id</th> -->
				<!-- <th>商家信息</th> -->
				<th>姓名</th>
				<th>电话</th>
				<th>订单状态</th>
				<th>订单详情</th>
				<th>创建时间</th> 
				<th>系统操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				<tr class="text-c">
					<td><?php echo htmlentities($vo['us_id']); ?></td>
					<td><?php echo htmlentities($vo['us_name']); ?></td>
					<td><?php echo htmlentities($vo['us_tel']); ?></td>
					<?php if($vo['type'] == 0): ?>
					<td style="color: red">待发货</td>
					<?php else: ?>
					<td style="color: dodgerblue">已发货</td>
					<?php endif; ?>
					<td><a href="javascript:;" onclick="showDetail(<?php echo htmlentities($vo['id']); ?>)">[点击查看]</a></td>
					<td><?php echo htmlentities($vo['creat_time']); ?></td>
					<td class="td-manage">
						<?php if($vo['type'] == 0): ?>
						<a href="javascript:;" onclick="deliver(<?php echo htmlentities($vo['id']); ?>,'dodeliverr')">[点击发货]</a>
						<?php endif; ?>
						&nbsp;
						&nbsp;
						<a style="text-decoration:none" onclick="del(<?php echo htmlentities($vo['id']); ?>,'Order')" title="删除"><i class="Hui-iconfont">&#xe706;</i></a>
					</td>
				</tr>
			<?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>
	<div class="pages" style="margin:20px;float: right; "><?php echo $list; ?></div>
	<div class="pages" style="margin:20px;float: right; "></div>
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
   <script src="/static/viewer.min.js"></script>
<script type="text/javascript">
	  //图片放大
$('.image').viewer();
	$('select[name="or_status"]').val(<?php echo htmlentities(app('request')->get('or_status')); ?>);
	$('select[name="or_num"]').val(<?php echo htmlentities(app('request')->get('or_num')); ?>);
	$('select[name="st_id"]').val(<?php echo htmlentities(app('request')->get('st_id')); ?>);
function create(id,url,key){
	var url = "<?php echo url('"+url+"'); ?>?id="+id;
	creatIframe(url,key);
}
function change(id,value,key){
	layer.confirm('确定要更改么？', {
	      btn: ['确定', '取消']
	    }, function(index, layero){
	        $.ajax({
	            type: "post",
	            url: "<?php echo url('change'); ?>",
	            data: {id:id,value:value,key:key},
	            success: function(data) {
	             	if(data.code){
	             		location.href = '';
	             	}else{
	             		location.href = '';
	             	}
	            }
	        });
	    });
}

function del(id,key){
    layer.confirm('确定要删除么？', {
      btn: ['确定', '取消']
    }, function(index, layero){
        $.ajax({
            type: "post",
            url: "<?php echo url('taocan_dingdan'); ?>",
            data: {id:id,key:key},
            success: function(data) {
                layer.msg(data.msg);
                if(data.code==1){
                    setTimeout(function(){
                        location.href = data.url;
                    },1000);
                }
            }
        });
    });
}


function deliver(id){
    layer.confirm('确定发货', {
      btn: ['确定', '取消']
    }, function(index, layero){
        $.ajax({
            type: "post",
            url: "<?php echo url('dodeliverr'); ?>",
            data: {id:id},
            success: function(data) {
                layer.msg(data.msg);
                if(data.code==1){
                    setTimeout(function(){
                        location.href = data.url;
                    },1000);
                }
            }
        });
    });
}
function showDetail(id){
	var url = "<?php echo url('Order/zhuce_details'); ?>?id="+id;
	layer_show('订单详情',url);
}
// function deliver(id){
// 	var url = "<?php echo url('Order/Deliver'); ?>?id="+id;
// 	layer_show('发货页面',url,'600','300');
// }
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