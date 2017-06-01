<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\rar\phpstudy\WWW\medias\public/../application/port\view\chebaih\gtphone.html";i:1495856245;}*/ ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
	<title>查询</title>
	<base href="__PUBLIC__/">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/bootstrap-switch.css">
	<script type="text/javascript" src="javascript/skin/js/jquery.1.12.js"></script>
<script type="text/javascript" src="javascript/skin/js/bootstrap.min.js"></script>
<script type="text/javascript" src="javascript/skin/js/bootstrap-modal.js"></script>
</head>
<style>
 form{
	 margin:20px;
 }
</style>
<body>
	<h3 class="text-center">车百汇活动注册信息查询</h3>
	<form class="form-inline text-center" role="form" method="get" action="/medias/public/index.php/port/Chebaih/gtphone">
	  <div class="form-group">
	    <label class="sr-only" for="name">手机号</label>
	    <input type="text" name="phone" class="form-control" placeholder="请输入手机号">
	  </div> 
	  <button type="submit" class="btn btn-default">提交</button>
	</form>
	<?php if(isset($data)): ?>
 
		<form class="form-horizontal ">
		  <div class="form-group">
		    <label class="col-sm-6 control-label">姓名</label>
		    <div class="col-sm-4">
		      <p class="form-control-static"><?php echo $data['name']; ?></p>
		    </div>
		  </div>
		   <div class="form-group">
		    <label class="col-sm-6 control-label">手机号</label>
		    <div class="col-sm-4">
		      <p class="form-control-static"><?php echo $data['phone']; ?></p>
		    </div>
		  </div>
		   <div class="form-group">
		    <label class="col-sm-6 control-label">选择车型</label>
		    <div class="col-sm-4">
		      <p class="form-control-static"><?php echo $data['brand_name']; ?></p>
		    </div>
		  </div>

		   <div class="form-group">
		    <label class="col-sm-6 control-label">购买时间</label>
		    <div class="col-sm-4">
		      <p class="form-control-static"><?php echo $data['timename']; ?></p>
		    </div>
		  </div>
		    <div class="form-group">
		    <label class="col-sm-6 control-label">入口城市</label>
		    <div class="col-sm-4">
		      <p class="form-control-static"><?php echo $data['localaddr']; ?></p>
		    </div>
		  </div>
		   <div class="form-group">
		    <label class="col-sm-6 control-label">定位城市</label>
		    <div class="col-sm-4">
		      <p class="form-control-static"><?php echo $data['localaddr']; ?></p>
		    </div>
		  </div>
		   <div class="form-group">
		    <label class="col-sm-6 control-label">选择城市</label>
		    <div class="col-sm-4">
		      <p class="form-control-static"><?php echo $data['changeaddr']; ?></p>
		    </div>
		  </div>
		    <div class="form-group">
		    <label class="col-sm-6 control-label">注册时间</label>
		    <div class="col-sm-4">
		      <p class="form-control-static"><?php echo date('Y-m-d H:i:s',$data['time']); ?></p>
		    </div>
		  </div>
		  
		 
		</form>
	<?php endif; ?>
</body>
</html>