<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:66:"D:\tt.com\public/../application/user/view/default/login\index.html";i:1506696794;s:66:"D:\tt.com\public/../application/user/view/default/base\common.html";i:1507827237;s:63:"D:\tt.com\public/../application/user/view/default/base\var.html";i:1496373782;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->

	<!-- Bootstrap -->
	<link href="__STATIC__/app/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="__STATIC__/app/css/style.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<style>
		.main{margin-bottom: 60px;}
		.indexLabel{padding: 10px 0; margin: 10px 0 0; color: #fff;}
	</style>
<?php echo hook('pageHeader'); ?>
</head>
<body>
<div class="main">
	<!-- 头部 -->
	<!--导航部分-->
	<nav class="navbar navbar-default navbar-fixed-bottom">
		<div class="container-fluid text-center">
			<div class="col-xs-3">
				<p class="navbar-text"><a href="<?php echo url('Home/index/index'); ?>" class="navbar-link">首页</a></p>
			</div>
			<div class="col-xs-3">
				<p class="navbar-text"><a href="<?php echo url('Home/index/fuwu'); ?>" class="navbar-link">服务</a></p>
			</div>
			<div class="col-xs-3">
				<p class="navbar-text"><a href="#" class="navbar-link">发现</a></p>
			</div>
			<div class="col-xs-3">
				<p class="navbar-text"><a href="#" class="navbar-link">我的</a></p>
			</div>
		</div>
	</nav>
	<!--导航结束-->
	<!-- /头部 -->
	<!-- 主体 -->
	<div class="main-container">
	
  <div class="container-fluid">
    <form class="login-form" action="" method="post">
      <div class="form-group">
        <label class="control-label" for="inputEmail">用户名</label>
        <input type="text" id="inputEmail" class="form-control" placeholder="请输入用户名"  ajaxurl="/member/checkUserNameUnique.html" errormsg="请填写1-16位用户名" nullmsg="请填写用户名" datatype="*1-16" value="" name="username">
      </div>
      <div class="form-group">
        <label for="inputPassword">密码</label>
        <div class="form-group">
          <input type="password" id="inputPassword"  class="form-control" placeholder="请输入密码"  errormsg="密码为6-20位" nullmsg="请填写密码" datatype="*6-20" name="password">
        </div>
      </div>
      <div class="form-group">
        <label class="form-group" for="inputPassword">验证码</label>
        <div class="controls">
          <input type="text" id="inputPassword" class="form-control" placeholder="请输入验证码"  errormsg="请填写5位验证码" nullmsg="请填写验证码" datatype="*5-5" name="verify">
        </div>
      </div>
      <div class="form-group">
        <label class="form-group"></label>
        <div class="form-group verifyimg">
          <?php echo captcha_img(); ?>
        </div>
        <div class="controls Validform_checktip text-warning"></div>
      </div>
      <div class="form-group">
        <div class="form-group">
          <label class="form-group">
            <input type="checkbox" name="remember" value="remember"> 自动登陆
          </label>
          <button type="submit" class="btn btn-primary onlineBtn">登 陆</button>
        </div>
      </div>
      <!--<div class="form-group">-->
      <!--<div><a href="#"><span class="glyphicon glyphicon-plus onlineUpImg"></span></a></div>-->
      <!--<label>图片(最多上传5张,可不上传):</label>-->
      <!--</div>-->
    </form>
  </div>

	</div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="__STATIC__/app/jquery-1.11.2.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="__STATIC__/app/bootstrap/js/bootstrap.min.js"></script>

	<script type="text/javascript">
	    $(function(){
	        $(window).resize(function(){
	            $("#main-container").css("min-height", $(window).height() - 343);
	        }).resize();
	    })
	</script>
	<!-- /主体 -->
	<!-- 底部 -->
    <!-- 底部
    ================================================== -->
	<script type="text/javascript">
(function(){
	var ThinkPHP = window.Think = {
		"ROOT"   : "__ROOT__", //当前网站地址
		"APP"    : "__APP__", //当前项目地址
		"PUBLIC" : "__PUBLIC__", //项目公共目录地址
		"DEEP"   : "<?php echo config('URL_PATHINFO_DEPR'); ?>", //PATHINFO分割符
		"MODEL"  : ["<?php echo config('URL_MODEL'); ?>", "<?php echo config('URL_CASE_INSENSITIVE'); ?>", "<?php echo config('URL_HTML_SUFFIX'); ?>"],
		"VAR"    : ["<?php echo config('VAR_MODULE'); ?>", "<?php echo config('VAR_CONTROLLER'); ?>", "<?php echo config('VAR_ACTION'); ?>"]
	}
})();
</script>
	
<script type="text/javascript">

    $(document)
        .ajaxStart(function(){
            $("button:submit").addClass("log-in").attr("disabled", true);
        })
        .ajaxStop(function(){
            $("button:submit").removeClass("log-in").attr("disabled", false);
        });


    $("form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(data){
            if(data.code){
                window.location.href = data.url;
            } else {
                self.find(".Validform_checktip").text(data.msg);
                //刷新验证码
                $(".verifyimg img").click();
            }
        }
    });

    $(function(){
        //刷新验证码
        var verifyimg = $(".verifyimg img").attr("src");
        $(".verifyimg img").click(function(){
            if( verifyimg.indexOf('?')>0){
                $(".verifyimg img").attr("src", verifyimg+'&random='+Math.random());
            }else{
                $(".verifyimg img").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
            }
        });
    });
</script>
 <!-- 用于加载js代码 -->
	<!-- 页面footer钩子，一般用于加载插件JS文件和JS代码 -->
	<?php echo hook('pageFooter', 'widget'); ?>
	<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
		
	</div>
</div>
	<!-- /底部 -->
</body>
</html>
