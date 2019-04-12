<?php /*a:5:{s:75:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\index\index.html";i:1539163659;s:76:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_meta.html";i:1524812002;s:78:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_header.html";i:1539163034;s:76:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_menu.html";i:1550718372;s:78:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_footer.html";i:1535678602;}*/ ?>
﻿<!DOCTYPE HTML>
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
<title>后台管理</title>
<meta name="keywords" content="后台管理">
<meta name="description" content="宜享购后台管理">
</head>
<body>
    <header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs">生元创享科技后台管理系统</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="">生元创享科技后台管理系统</a> <span class="logo navbar-slogan f-l mr-10 hidden-xs"></span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
			<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs" >
				<ul class="cl">
					<li></li>
					<li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A"><?php echo htmlentities(app('session')->get('admin')['ad_account']); ?> <i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<!-- <li><a href="#">个人信息</a></li> -->
							<li><a href="<?php echo url('login/logout'); ?>">退出</a></li>
						</ul>
					</li>
					<!-- <li id="Hui-msg"> <a href="#" title="消息"><span class="badge badge-danger">1</span><i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i></a> </li> -->
					<li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;" data-val="green" title="默认（绿色）">默认（绿色）</a></li>
							<li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
							<li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header> <aside class="Hui-aside" style="background: #FDF5F2;">
	<div class="menu_dropdown bk_2 sidebar">
		<!-- 管理员 -->
		<dl id="menu-system" class="guanli">
			<dt data-node-id="1" class="rule_node"><i class="Hui-iconfont">&#xe62d;</i> 管理列表<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="2" class="rule_node"><a data-href="<?php echo url('Admin/index'); ?>" data-title="管理员" href="javascript:void(0)">管理员</a></li>
					<li data-node-id="7" class="rule_node"><a data-href="<?php echo url('Admin/roleIndex'); ?>" data-title="角色管理" href="javascript:void(0)">角色管理</a></li>
				</ul>
			</dd>
		</dl>
		<!-- 用户管理 -->
		<dl id="menu-admin">
			<dt data-node-id="9" class="rule_node"><i class="Hui-iconfont">&#xe62c;</i> 用户管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="10" class="rule_node"><a data-href="<?php echo url('User/index'); ?>" data-title="用户列表" href="javascript:void(0)">用户列表</a></li>
				</ul>
				<!-- <ul>
					<li data-node-id="15" class="rule_node"><a data-href="<?php echo url('User/news'); ?>" data-title="用户列表" href="javascript:void(0)">新进会员</a></li>
				</ul> -->
			</dd>
		</dl>
		<!-- 区域运营商管理 -->
		<!-- <dl id="menu-admin">
			<dt data-node-id="16" class="rule_node"><i class="Hui-iconfont">&#xe62c;</i> 区域管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="17" class="rule_node"><a data-href="<?php echo url('AreaStore/index'); ?>" data-title="区域列表" href="javascript:void(0)">区域列表</a></li>
				</ul>
			</dd>
		</dl> -->
		<!-- 门店管理 -->
		<!-- <dl id="menu-member">
			<dt data-node-id="22" class="rule_node"><i class="Hui-iconfont">&#xe66a;</i> 门店管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="23" class="rule_node"><a data-href="<?php echo url('store/index'); ?>" data-title="门店列表" href="javascript:;">门店列表</a></li>
				</ul>
			</dd>
		</dl> -->
		<dl id="menu-member">
			<dt data-node-id="28" class="rule_node"><i class="Hui-iconfont">&#xe66a;</i> 商品管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="29" class="rule_node"><a data-href="<?php echo url('product/cate'); ?>" data-title="分类列表" href="javascript:;">商品分类</a></li>
					<li data-node-id="33" class="rule_node"><a data-href="<?php echo url('product/index'); ?>" data-title="商品列表" href="javascript:void(0)">商品列表</a></li>
				</ul>
			</dd>
		</dl>
		<!-- 代理 -->
		<!-- <dl id="menu-admin">
			<dt data-node-id="11" class="rule_node"><i class="Hui-iconfont">&#xe620;</i> 商品管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="11" class="rule_node"><a data-href="<?php echo url('product/index'); ?>" data-title="商品列表" href="javascript:void(0)">商品列表</a></li>
				</ul>
			</dd>
		</dl> -->
		<!-- 订单管理 -->
		<!-- <dl id="menu-member">
			<dt data-node-id="59" class="rule_node"><i class="Hui-iconfont">&#xe60d;</i> 仓库管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="59" class="rule_node"><a data-href="<?php echo url('Order/index'); ?>" data-title="我的订单" href="javascript:;">我的订单</a></li>
					<li data-node-id="65" class="rule_node"><a data-href="<?php echo url('Order/add'); ?>" data-title="录入订单" href="javascript:;">录入订单</a></li>
				</ul>
			</dd>
		</dl> -->
		<!-- 订单管理 -->
		<!-- <dl id="menu-member">
			<dt data-node-id="59" class="rule_node"><i class="Hui-iconfont">&#xe60d;</i> 配送员管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="59" class="rule_node"><a data-href="<?php echo url('Order/index'); ?>" data-title="我的订单" href="javascript:;">我的订单</a></li>
					<li data-node-id="65" class="rule_node"><a data-href="<?php echo url('Order/add'); ?>" data-title="录入订单" href="javascript:;">录入订单</a></li>
				</ul>
			</dd>
		</dl> -->
		<!-- 订单管理 -->
		<!-- <dl id="menu-member">
			<dt data-node-id="59" class="rule_node"><i class="Hui-iconfont">&#xe60d;</i> 购物车管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="59" class="rule_node"><a data-href="<?php echo url('Order/index'); ?>" data-title="我的订单" href="javascript:;">我的订单</a></li>
					<li data-node-id="65" class="rule_node"><a data-href="<?php echo url('Order/add'); ?>" data-title="录入订单" href="javascript:;">录入订单</a></li>
				</ul>
			</dd>
		</dl> -->
		<!-- 商家管理 -->
		<dl id="menu-comments">
			<dt data-node-id="38" class="rule_node"><i class="Hui-iconfont">&#xe66a;</i> 订单管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="39" class="rule_node"><a data-href="<?php echo url('Order/index'); ?>" data-title="订单列表" href="javascript:void(0)">订单列表</a></li>
				</ul>
				<ul>
					<li data-node-id="53" class="rule_node"><a data-href="<?php echo url('Order/zhuceindex'); ?>" data-title="注册套餐" href="javascript:void(0)">注册套餐</a></li>
				</ul>
				<!-- <ul>
					<li data-node-id="47" class="rule_node"><a data-href="<?php echo url('Order/record'); ?>" data-title="发货记录" href="javascript:void(0)">发货记录</a></li>
				</ul> -->
			</dd>
		</dl>
		<dl id="menu-comments">
			<dt data-node-id="41" class="rule_node"><i class="Hui-iconfont">&#xe66a;</i> 财务管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="57" class="rule_node"><a data-href="<?php echo url('profit/zhuanzhang'); ?>" data-title="购物积分转账记录" href="javascript:void(0)">购物积分转账记录</a></li>
					<li data-node-id="86" class="rule_node"><a data-href="<?php echo url('profit/zhuanzhang_cy'); ?>" data-title="创业积分转账记录" href="javascript:void(0)">创业积分转账记录</a></li>
					<li data-node-id="58" class="rule_node"><a data-href="<?php echo url('profit/chongzhi'); ?>" data-title="充值申请" href="javascript:void(0)">充值申请</a></li>
					<li data-node-id="59" class="rule_node"><a data-href="<?php echo url('profit/chongzhijl'); ?>" data-title="充值记录" href="javascript:void(0)">充值记录</a></li>
					<!-- <li data-node-id="26" class="rule_node"><a data-href="<?php echo url('profit/wechat'); ?>" data-title="微信支付记录" href="javascript:void(0)">微信支付记录</a></li> -->
					<li data-node-id="42" class="rule_node"><a data-href="<?php echo url('profit/commissionapply'); ?>" data-title="提现申请" href="javascript:void(0)">提现申请</a></li>
					<li data-node-id="43" class="rule_node"><a data-href="<?php echo url('profit/commission'); ?>" data-title="提现记录" href="javascript:void(0)">提现记录</a></li>
				</ul>
			</dd>
		</dl>
		<dl id="menu-system" class="guanli super" >
			<dt data-node-id="44" class="rule_node" ><i class="Hui-iconfont">&#xe62e;</i> 系统管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="45" class="rule_node"><a data-href="<?php echo url('setting/index'); ?>" data-title="基本设置" href="javascript:void(0)">基本设置</a></li>
					<li data-node-id="60" class="rule_node"><a data-href="<?php echo url('news/index'); ?>" data-title="公告列表" href="javascript:void(0)">公告列表</a></li>
					<li data-node-id="61" class="rule_node"><a data-href="<?php echo url('news/add'); ?>" data-title="添加公告" href="javascript:void(0)">添加公告</a></li>
					<li data-node-id="46" class="rule_node"><a data-href="<?php echo url('setting/shuffling'); ?>" data-title="主页轮播" href="javascript:void(0)">主页轮播</a></li>
				</ul>
			</dd>
		</dl>
		<dl id="menu-system" class="guanli super" >
			<dt data-node-id="44" class="rule_node" ><i class="Hui-iconfont">&#xe62e;</i> 奖励设置<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li data-node-id="63" class="rule_node"><a data-href="<?php echo url('setting/jiangli'); ?>" data-title="比例设置" href="javascript:void(0)">比例设置</a></li>
				</ul>
				<ul>
					<li data-node-id="64" class="rule_node"><a data-href="<?php echo url('product/taocan'); ?>" data-title="套餐列表" href="javascript:void(0)">套餐列表</a></li>
				</ul>
				<ul>
					<li data-node-id="65" class="rule_node"><a data-href="<?php echo url('profit/jianglimx'); ?>" data-title="奖金明细" href="javascript:void(0)">奖金明细</a></li>
				</ul>
			</dd>
		</dl>
	</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick=""></a></div>
    <section class="Hui-article-box" >
        <div id="Hui-tabNav" class="Hui-tabNav hidden-xs" style="background: #FDF5F2;">
            <div class="Hui-tabNav-wp">
                <ul id="min_title_list" class="acrossTab cl">
                    <li class="active">
                        <span title="我的桌面" data-href="welcome.html">我的桌面</span>
                        <em></em>
                    </li>
                </ul>
            </div>
            <div class="Hui-tabNav-more btn-group">
            	<a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a>
            	<a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
        </div>
        <div id="iframe_box" class="Hui-article">
            <div class="show_iframe">
                <div style="display:none" class="loading"></div>
                <iframe scrolling="yes" frameborder="0" src="<?php echo url('index/welcome'); ?>"></iframe>
            </div>
        </div>
    </section>
    <div class="contextMenu" id="Huiadminmenu">
        <ul>
            <li id="closethis">关闭当前 </li>
            <li id="closeall">关闭全部 </li>
        </ul>
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
    <script type="text/javascript" src="/static/admin/lib/jquery.contextmenu/jquery.contextmenu.r2.js"></script>
    <script type="text/javascript">
    $(function() {
        $("#min_title_list li").contextMenu('Huiadminmenu', {
            bindings: {
                'closethis': function(t) {
                    console.log(t);
                    if (t.find("i")) {
                        t.find("i").trigger("click");
                    }
                },
                'closeall': function(t) {
                    alert('Trigger was ' + t.id + '\nAction was Email');
                },
            }
        });
    });
    </script>
</body>

</html>