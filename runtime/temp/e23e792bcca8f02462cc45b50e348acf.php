<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\excel\putin.html";i:1499220682;}*/ ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>经销商信息导入</title>
</head>
<body>
	<p>各地经销商信息导入</p>
	<form action="<?php echo url('Excel/readerBW'); ?>" method="post" enctype="multipart/form-data">
	    <input type="file" name="importexcel"/> 
	    <input type="submit" value="导入"/>
	</form>

<!--智慧云数据对接-->
<br>
<br>
<br>
<br>
<p>智慧云数据对接</p>
	<form action="<?php echo url('Cbhtozhy/Tozhydata'); ?>" method="post" enctype="multipart/form-data">
	    <input type="file" name="importexcel"/> 
	    <input type="submit" value="导入"/>
	</form>


	<!--秦-->
<br>
<br>
<br>
<br>
 
</body>
</html>