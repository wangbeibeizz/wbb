<?php /*a:3:{s:79:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\profit\chongzhi.html";i:1547803346;s:76:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_meta.html";i:1524812002;s:78:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_footer.html";i:1535678602;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="/static/viewer.min.css"/>
</head>

<body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span>财务管理 <span class="c-gray en">&gt;</span> 财富记录<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form class="Huiform" method="get" action="">
            </span>
                <input type="text" class="input-text" style="width:150px" placeholder="昵称/关键字" id="" name="keywords" value="<?php echo htmlentities((app('request')->get('keywords') ?: '')); ?>">
                <button type="submit" class="btn btn-success radius" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
            </form>
        </div>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="r"><strong></strong> </span> </div>
        <div class="mt-20">
            <table class="table table-border  table-hover table-bg table-sort">
                <thead>
                    <tr class="text-c">
                        <th>id</th>
                        <th>昵称</th>
                        <th>手机号</th>

                        <th>提现人</th>
                        <th>金额</th>

                        <th>类型</th>
                        <th>支付凭证</th>
                        <th>申请时间</th>
                        <!-- <th>到账类型</th> -->
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr class="text-c">
                        <td><?php echo htmlentities($vo['id']); ?></td>
                        <td><?php echo htmlentities($vo['us_nickname']); ?></td>
                        <td><?php echo htmlentities($vo['us_tel']); ?></td>

                        <td><?php echo htmlentities($vo['us_name']); ?></td>
                        <td><?php echo htmlentities($vo['tx_sum']); ?></td>

                        <td><?php echo htmlentities($vo['tx_type_text']); ?></td>
                        <td><img class='product-thumb image' src="<?php echo htmlentities($vo['tx_image']); ?>" style="width:60px;height:60px"></td>
                        <td><?php echo htmlentities($vo['tx_time']); ?></td>
                        <!-- <td>即刻到账</td> -->
                        <td class="td-manage">
                            <a style="text-decoration:none" onclick="change(<?php echo htmlentities($vo['id']); ?>,1,'tx_status')" title="审核通过"><i class="Hui-iconfont">&#xe634;</i></a>
                            &nbsp;
                            &nbsp;
                            <a style="text-decoration:none" onclick="change(<?php echo htmlentities($vo['id']); ?>,3,'tx_status')" title="驳回"><i class="Hui-iconfont">&#xe66c;</i></a>
                        </td>
                            
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        <div class="pages" style="margin:20px;float: right; "><?php echo $list; ?></div>
        </div>
            <div class="cd-popupaa">
                <div class="cd-popup-containeraa">
                    <!-- <img src="/mobile/img/close1.png" class="cd-popup-closeaa" /> -->
                   <div class="cd-popup_newscon">

                   </div>
                </div>
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

        <script src="/static/viewer.min.js"></script>
        <!--请在下方写此页面业务相关的脚本-->
        <script type="text/javascript">
            //图片放大
$('.image').viewer();


        $('select[name="wa_type"]').val(<?php echo htmlentities(app('request')->get('wa_type')); ?>);

        function create(id, url, key) {
            var url = "<?php echo url('" + url + "'); ?>?id=" + id;
            creatIframe(url, key);
        }

        function change(id, value, key) {
            layer.confirm('确定要执行操作？', {
                btn: ['确定', '取消']
            }, function(index, layero) {
                $.ajax({
                    type: "post",
                    url: "<?php echo url('changee'); ?>",
                    data: { id: id, value: value, key: key },
                    success: function(data) {
                        layer.msg(data.msg, {
                          icon: 1,
                          time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
                          location.href = ''
                        });
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
        </script>
</body>

</html>