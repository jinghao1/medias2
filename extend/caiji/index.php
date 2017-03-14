<?php


?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>汽车之家口碑采集</title>
</head>
<body>
	<form action="action.php" method="post">
		
		<p>请输入口碑地址：<input type="text" name="url"    value="http://k.autohome.com.cn/496/" /></p>
		<p>采集页数 开始： <input type="number" value="1" name="beg"> 结束：<input type="number" value="2" name="end"></p>
		<input type="submit" name="test" value="采集" />
	</form>
</body>
</html>