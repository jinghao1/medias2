<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\excel\putin.html";i:1495597816;}*/ ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>经销商信息导入</title>
</head>
<body>
	<form action="<?php echo url('Excel/readerBW'); ?>" method="post" enctype="multipart/form-data">
	    <input type="file" name="importexcel"/> 
	    <input type="submit" value="导入"/>
	</form>
</body>
</html>