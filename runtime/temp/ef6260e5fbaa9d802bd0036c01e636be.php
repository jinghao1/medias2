<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\chebaihui\gtphone.html";i:1496220700;}*/ ?>
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
 .lqstat{
	 border:1px solid #ccc;
	 margin-left:40px;
	 padding:3px;
	 border-radius:3px;
 }
</style>
<script type="text/javascript">
	function lqupd(obj){
		//alert("song");
		var curphone = $("#curpho").html();
		 
		$.ajax({
			type:"post",
			url:"/medias/public/index.php/admin/Chebaihui/upuserlq",
			data:{curphone:curphone},
			dataType:"json",
			success:function(res){
				console.log(res);
				if(res==3){
					$(obj).html("未领取");
				}else if(res==1){
					$(obj).html("已领取");
				} 
				
			},
			error:function(err){
				console.log(err);
			}
			
		});
 
		
	}
</script>
<body>
	<h3 class="text-center">车百汇活动注册信息查询</h3>
	<form class="form-inline text-center" role="form" method="get" action="/medias/public/index.php/admin/Chebaihui/gtphone">
	  <div class="form-group">
	    <label class="sr-only" for="name">手机号</label>
	    <input type="text" name="phone" class="form-control" placeholder="请输入手机号">
	  </div> 
	  <button type="submit" class="btn btn-default">提交</button>
	</form>
	
 		<input type="hidden" name="curu" value="<?php echo $uid; ?>"> 
		<form class="form-horizontal ">
		  <?php if(isset($thephone)): ?> 
			  <div class="form-group">
			    <label class="col-sm-6 control-label">手机号</label>
			    <div class="col-sm-4">
			      <p class="form-control-static" id="curpho"><?php echo $thephone; ?></p>
			    </div>
			  </div>
		   <div class="form-group">
		    <label class="col-sm-6 control-label">状态</label>
		    <div class="col-sm-4">
		      <p class="form-control-static"><?php echo $havereg; ?>&nbsp;<span onclick="lqupd(this)" class="lqstat"><?php echo $havelq; ?></span></p>
		    </div>
		  </div>
		  <?php endif; if(isset($data)): ?>
		  <div class="form-group">
		    <label class="col-sm-6 control-label">姓名</label>
		    <div class="col-sm-4">
		      <p class="form-control-static"><?php echo $data['name']; ?></p>
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
		  
		 	<?php endif; ?>
		</form>

</body>
</html>