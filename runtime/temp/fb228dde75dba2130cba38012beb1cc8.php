<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\rar\phpstudy\WWW\medias\public/../application/port\view\songtest\bwup.html";i:1497942905;}*/ ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>bx7</title>
</head>
<body>
	<div style="float: right;" >
		<image src="<?php echo isset($imageurl) ? $imageurl :  ''; ?>"></image>
	</div>

	<div style="float: right;" >
		<image src="<?php echo isset($imageurlmb) ? $imageurlmb :  ''; ?>"></image>
	</div>
	<h3>宝沃bx7直播内容更新</h3>
	
	<form method="post"  enctype="multipart/form-data" action="#"  name="bx7d">
		<p>请选择pc图片：<input type="file" name="image[]" value="" /></p>
		<p>请选择mobile图片：<input type="file" name="imagemb[]" value="" /></p>
		
		<p>请填写视频源地址：<input type="text" style="width:500px;" name="linkurl" value="<?php echo isset($linkurl) ? $linkurl :  ''; ?>" /></p>
		<?php if($statu==1): ?>
		<p> 开启直播：<input type="radio" name="statu" value="1" checked /></p>
		<p>	关闭直播：<input type="radio" name="statu" value="0"  /></p>
<?php else: ?> 
	<p> 开启直播：<input type="radio" name="statu" value="1" /></p>
		<p>	关闭直播：<input type="radio" name="statu" value="0"  checked /></p>
<?php endif; ?>
	 
		<input type="hidden" name="id" value="1" />
		<input type="submit" name="tijiao" value="提交" />
	</form>
	
</body>
</html>