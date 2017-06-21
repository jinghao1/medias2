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
		
		<p>请输入口碑地址：<input type="text" name="url" style="width:500px;"    value="http://k.autohome.com.cn/496/" /></p>
		<p>采集页数 开始： <input type="number" value="1" name="beg"> 结束：<input type="number" value="2" name="end"></p>
		<p>满意-词频： <input type="text" name="good"></p>
		<p>不满意-词频： <input type="text" name="bad"></p>
		<input type="submit" name="test" value="采集" />
	</form>

	<form action="actionpl.php" method="post">
		
		<p>请输入文章评论地址：<input type="text" name="url" style="width:500px;"   value="http://www.autohome.com.cn/comment/Articlecomment.aspx?articleid=903464" /></p>
		<p>采集页数 开始： <input type="number" value="1" name="beg"> 结束：<input type="number" value="2" name="end"></p>
		<p>满意-词频： <input type="text" name="good"></p>
		<p>不满意-词频： <input type="text" name="bad"></p>
		<input type="submit" name="test" value="抓取" />
	</form>

	<form action="actionvideo.php" method="post">
		
		<p>请输入视频评论id：<input type="text" name="urlid" style="width:500px;"   value="111715" /></p>
		<p>采集页数 开始： <input type="number" value="1" name="beg"> 结束：<input type="number" value="2" name="end"></p>
		<p>满意-词频： <input type="text" name="good"></p>
		<p>不满意-词频： <input type="text" name="bad"></p>
		<input type="submit" name="test" value="抓取" />
	</form>
	
</body>
</html>