<?php /*a:3:{s:77:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\setting\index.html";i:1539332841;s:76:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_meta.html";i:1524812002;s:78:"D:\phpStudy\PHPTutorial\WWW\syjk1.0\application\admin\view\public\_footer.html";i:1535678602;}*/ ?>
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

<title>基本设置</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
	<span class="c-gray en">&gt;</span>
	系统管理
	<span class="c-gray en">&gt;</span>
	基本设置
	<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<!-- <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" onclick="ooo()" title="清楚缓存" ><i class="Hui-iconfont">&#xe706;</i></a>&nbsp;&nbsp;&nbsp;&nbsp;
 --></nav>
<div class="page-container">
	<form class="form form-horizontal" id="form-article-add">
		<div id="tab-system" class="HuiTab">
			<div class="tabBar cl">
				<span>基本设置</span>
				<!-- <span>对公账户</span> -->
			</div>
		<div class="tabCon">
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-2">
						<span class="c-red">*</span>
						收款信息：</label>
					<!-- <div class="formControls col-xs-8 col-sm-9">
						<input type="text" name="bank_number"  value="" class="input-text" style="width:20%">
					</div> -->
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-2">
						<span class="c-red">*</span>
						开户银行：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" name="ad_bank"  value="<?php echo htmlentities($list['ad_bank']); ?>" class="input-text" style="width:20%">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-2">
						<span class="c-red">*</span>
						开户人：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" name="ad_bank_name"  value="<?php echo htmlentities($list['ad_bank_name']); ?>" class="input-text" style="width:20%">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-2">
						<span class="c-red">*</span>
						银行卡号：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" name="ad_bank_number"  value="<?php echo htmlentities($list['ad_bank_number']); ?>" class="input-text" style="width:20%">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-2">
						<span class="c-red">*</span>
						微信用户名：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" name="ad_wechat_name"  value="<?php echo htmlentities($list['ad_wechat_name']); ?>" class="input-text" style="width:20%">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-2">
						<span class="c-red">*</span>
						微信支付二维码：</label>
					<div class="formControls col-xs-8 col-sm-9" style="width:110px;height: 110px;position: relative;display: inline-block; ">
						<?php if($list['wechat_image'] == null): ?>
						<img src="/static/admin/img/add0.png" alt="" class="logo" style="position: absolute;left: 0;top: 0;width: 100%;height: 100%;">
						<?php else: ?>
						<img src="<?php echo htmlentities($list['wechat_image']); ?>" alt="" class="logo" style="position: absolute;left: 0;top: 0;width: 100%;height: 100%;">
						<?php endif; ?>
						<input type="file" name="file" onchange="eee($(this))" class="input-text" style="position: absolute;left: 0;top: 0;width: 100%;height: 100%;opacity: 0;">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-2">
						<span class="c-red">*</span>
						微信账号：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" name="ad_wechat"  value="<?php echo htmlentities($list['ad_wechat']); ?>" class="input-text" style="width:20%">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-2">
						<span class="c-red">*</span>
						支付宝姓名：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" name="ad_alipay_name"  value="<?php echo htmlentities($list['ad_alipay_name']); ?>" class="input-text" style="width:20%">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-2">
						<span class="c-red">*</span>
						支付宝号：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" name="ad_alipay"  value="<?php echo htmlentities($list['ad_alipay']); ?>" class="input-text" style="width:20%">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-xs-4 col-sm-2">
						<span class="c-red">*</span>
						登录密码：</label>
					<div class="formControls col-xs-8 col-sm-9">
						<input type="text" name="ad_pwd"  value="" class="input-text" style="width:20%">
					</div>
				</div>
				<div style="display:none">
	                <input type="text" name="wechat_image" value="">
	            </div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onclick="return saveConfig();" class="btn btn-primary radius" type="submit"><i clsass="Hui-iconfont">&#xe632;</i> 保存</button>
				<!-- <button class="btn btn-default radius rst" type="reset">&nbsp;&nbsp;重置&nbsp;&nbsp;</button> -->
			</div>
		</div>
	</form>
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
$('.form input[name="status"][value='+"<?php echo cache('setting')['status']; ?>"+']').attr("checked",true);

$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});
	$("#tab-system").Huitab({
		index:0
	});
});
// /setting/saveinfo
function saveConfig(){
	$.post("<?php echo url('setting/index'); ?>",$('.form').serialize()).success(function(data){
		layer.msg(data.msg);
		if(!data.code){
			$('.rst').click();
		}
	});
	return false;
}
function eee(dada) {
        var data = new FormData();
        data.append('file', dada[0].files[0]);
        var index = layer.load(1, { shade: false }); //0代表加载的风格，支持0-2
        $.ajax({
            url: '<?php echo url("Product/upload"); ?>',
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                layer.msg(data.msg);
                if (data.code) {
                    $('.logo').attr('src',data.data);
                    $('input[name="wechat_image"]').val(data.data);
                }
                layer.close(index);
            },
            error: function() {
                layer.close(index);
                layer.msg('上传出错');
            }
        });
    }
// function ooo(){
// 	$.post("<?php echo url('every/clear'); ?>").success(function(data){
// 		layer.msg('清除成功');
// 	});
// 	return false;
// }
$("#file_upload").change(function() {
        //提交
        var data = new FormData();
        // console.log($(this)[0].files[0]);
        data.append('img',$(this)[0].files[0]);
        $.ajax({
            url: '<?php echo url("tupian"); ?>',
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
            	if(data.code){
            		$('#background').val(data.data);
                	$('.preview').attr('src', data.data);
            	}else{
            		layer.msg(data.msg);
            	}
            	
            },
            error: function() {
                layer.msg('上传出错');
            }
        });
    });
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>
