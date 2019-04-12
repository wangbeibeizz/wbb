<?php /*a:3:{s:74:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\user\index.html";i:1550653448;s:76:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_meta.html";i:1524812002;s:78:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_footer.html";i:1535678602;}*/ ?>
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
<nav class="breadcrumb">
	<i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span>会员管理 <span class="c-gray en">&gt;</span> 会员列表 
	<!-- <a class="btn btn-success radius r" style="line-height:1.5em;margin-top:3px" href="javascript:void(0);" onclick="downdo()" title="下载" ><i class="Hui-iconfont">&#xe640;</i></a>&nbsp;&nbsp; -->
	<a class="btn btn-success radius r" style="line-height:1.5em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
	<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" onclick="excel()" title="导出Excel文档"><i class="Hui-iconfont">&#xe681;</i></a>
</nav>
<div class="page-container">
	<div class="text-c">
	<form class="Huiform" method="get" action="" target="_self">
		状态：
		<span class="select-box inline">
			<select name="us_status" class="select">
				<option value="">全部</option>
				<option value="1">正常</option>
				<option value="0" style="color:red;">被禁用</option>
			</select>
		</span>&nbsp;&nbsp;&nbsp;&nbsp;
		<!-- 类型：
		<span class="select-box inline">
			<select name="us_level" class="select">
				<option value="">全部</option>
				<option value="1">会员</option>
				<option value="0">普通</option>
			</select>
		</span> -->
		<input type="text" class="input-text" style="width:150px" placeholder="昵称/关键字" id="" name="nickname" value="<?php echo htmlentities((app('request')->get('nickname') ?: '')); ?>">
		<input type="text" class="input-text" style="width:150px" placeholder="手机号" id="" name="keywords" value="<?php echo htmlentities((app('request')->get('keywords') ?: '')); ?>">
		<button type="submit" class="btn btn-success radius" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
		</form>
	</div>
	<div class="cl pd-5 bg-1 bk-gray mt-20">
		<span class="l">
			<a href="javascript:;" onclick="create(0,'add','添加用户')" class="btn btn-primary radius">
			<i class="Hui-iconfont">&#xe600;</i> 用户</a>
		</span>
		<!-- <span class="r"><strong>总人数:<?php echo htmlentities($count); ?></strong></span> -->
	</div>
	<div class="mt-20">
	<table class="table table-border  table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th>用户ID</th>
				<th>昵称</th>
				<th>姓名</th>
				<th>账号/手机号</th>
				<!-- <th>推荐人</th> -->
				<th>我的分享人</th>
				<!-- <th>我的推荐人</th> -->
				<th>我分享的人</th>
				<!-- <th>我的直推</th> -->
				<th>我在的小组</th>
				<th>达标次数</th>
				<th>团队人数</th>
				<!-- <th>我的公排</th> -->
				<!-- <th>现金积分</th> -->
				<th>购物积分</th>
				<th>创业积分</th>
				<!-- <th>注册积分</th> -->
				<!-- <th>商城消费总金额</th> -->
				<th>状态</th>
				<th>添加时间</th>
				<th>绑定信息</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				<tr class="text-c">
					<td><?php echo htmlentities($vo['us_id']); ?></td>
					<td><?php echo htmlentities($vo['us_nickname']); ?></td>
					<td><?php echo htmlentities($vo['us_name']); ?></td>
					<td>
						<a href="javascript:;" onclick="add(this,<?php echo htmlentities($vo['id']); ?>,3,'请输出您要修改的手机号')">
							<?php echo htmlentities($vo['us_tel']); ?>
						</a>
					</td>
					<td><a href="javascript:;" onclick="showtjdetail(<?php echo htmlentities($vo['us_zhitui']); ?>)">[点击查看]</a></td>
					<td><a href="javascript:;" onclick="showztdetail(<?php echo htmlentities($vo['id']); ?>)">[点击查看]</a></td>
					<td><a href="javascript:;" onclick="showgpdetail(<?php echo htmlentities($vo['id']); ?>)">[点击查看]</a></td>
					<td><?php echo htmlentities($vo['us_node_time']); ?></td>
					<td><?php echo htmlentities($vo['td_count']); ?></td>
					<td>
						<a href="javascript:;" onclick="add(this,<?php echo htmlentities($vo['id']); ?>,1,'请输出您要修改的现金积分')">
							<?php echo htmlentities($vo['us_cash']); ?>
						</a>
					</td>
					<td>
						<a href="javascript:;" onclick="add(this,<?php echo htmlentities($vo['id']); ?>,2,'请输出您要修改的注册积分')">
							<?php echo htmlentities($vo['us_reg']); ?>
						</a>
					</td>
					<td class="td-status">
					<?php if($vo['us_type'] == 1): ?>
						<span class="label label-success radius">已启用</span>
					<?php else: ?>
						<span class="label label-default radius">已禁用</span>
					<?php endif; ?>
					</td>
					<td><?php echo htmlentities($vo['us_creattime']); ?></td>
					<td><a href="javascript:;" onclick="showgdbdetail(<?php echo htmlentities($vo['id']); ?>)">[点击查看]</a></td>
					<td class="td-manage">
						<!-- <a style="text-decoration:none" onclick="record(<?php echo htmlentities($vo['id']); ?>,'edit','会员详情')"  title="会员详情"><i class="Hui-iconfont">&#xe6c6;</i></a> -->
						<?php if($vo['us_type'] == 1): ?>
						<a style="text-decoration:none" onClick="change(<?php echo htmlentities($vo['id']); ?>,0,'us_status')" href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a>
						<?php else: ?>
						<a onClick="change(<?php echo htmlentities($vo['id']); ?>,1,'us_status')" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>
						<?php endif; ?>
						<a title="重置密码" href="javascript:;" onclick="createe(<?php echo htmlentities($vo['id']); ?>,'edit','重置密码')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
						<!-- <a style="text-decoration:none" onclick="create(<?php echo htmlentities($vo['id']); ?>,'addr','地址列表')" title="地址"><i class="Hui-iconfont">&#xe671;</i></a> -->
						<a style="text-decoration:none" onclick="del(<?php echo htmlentities($vo['id']); ?>)" title="删除"><i class="Hui-iconfont">&#xe706;</i></a>
					</td>
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
<script type="text/javascript">
$('select[name="us_status"]').val(<?php echo htmlentities(app('request')->get('us_status')); ?>);
$('select[name="us_level"]').val(<?php echo htmlentities(app('request')->get('us_level')); ?>);


function showtjdetail(id){
	var url = "<?php echo url('User/tjdetail'); ?>?id="+id;
	layer_show('我的分享人',url);
}

function add(obj,id,type,title){
	var o_num = parseFloat($(obj).text());
	layer.prompt({title: title,},function(num){
		if(num){
			$.post("<?php echo url('User/addsum'); ?>",{ id: id,num: num ,type:type}).success(function(data) {
				layer.msg(data.msg);
				if (data.code) {
					location.href = '';
					// $(obj).text(parseFloat(num));
				}
			})
		}
	});
}

function showztdetail(id){
	var url = "<?php echo url('User/ztdetail'); ?>?id="+id;
	layer_show('我分享的人',url);
}

function showgpdetail(id){
	var url = "<?php echo url('User/gpdetail'); ?>?id="+id;
	layer_show('我的小组',url);
}

function showgdbdetail(id){
	var url = "<?php echo url('User/dbdetail'); ?>?id="+id;
	layer_show('我的信息',url);
}

function create(id,url,key){
	var url = "<?php echo url('"+url+"'); ?>?id="+id;
	creatIframe(url,key);
}
function createe(id,value,key){
	layer.confirm('确定要重置密码么？', {
	      btn: ['确定', '取消']
	    }, function(index, layero){
	        $.ajax({
	            type: "post",
	            url: "<?php echo url('changee'); ?>",
	            data: {id:id,value:value,key:key},
	            success: function(data) {
	            	layer.confirm(data.msg, {btn: ['完成']},function(){
	            		layer.msg('重置密码成功！');
	            	})
	            }
	        });
	    });
}
function excel(){
    var url = "<?php echo url('excel'); ?>";
    creatIframe(url,'表格导出');
    // location.href = '';
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
	             	}
	            }
	        });
	    });
}

function del(id){
    layer.confirm('确定要删除么？', {
      btn: ['确定', '取消']
    }, function(index, layero){
        $.ajax({
            type: "post",
            url: "<?php echo url('delete'); ?>",
            data: {id:id},
            success: function(data) {
                layer.msg(data.msg);
                setTimeout("window.location=\"<?php echo url('User/index'); ?>\"",300);
            }
        });
    });
}
function showCateLine(id){
	var url = "<?php echo url('showMyteam'); ?>?id="+id;
	layer_show('我的下级列表图',url);
	//layer_show('发货页面',url,'600','300');
}	
</script> 
</body>
</html>