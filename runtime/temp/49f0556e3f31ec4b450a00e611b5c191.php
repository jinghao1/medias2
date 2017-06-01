<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\user\groupadd.html";i:1496223940;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<base href="__PUBLIC__/">
	<meta charset="UTF-8">
	<title></title>
	<link rel="stylesheet" href="javascript/skin/css/content.css" type="text/css" />
</head>
<body>
	<div class="content">
		<div class="GetNav">
			<p style="height:24px;">&nbsp;角色添加&nbsp;</p>
		</div>
		<form action="<?php echo url('GroupAdd'); ?>" method="post">
			<table width='98%'  border='0' cellpadding='1' cellspacing='1' align="center" class="tabody">
				<tr>
					<td>角色名称</td>
					<td>
						<input type="text" name="title" placeholder="角色名称">
					</td>
				</tr>
				<tr style="display:none;">
					<td>是否启用</td>
					<td>
						<input type="radio" name="status" checked value="1">是
					 
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input class="getSubmit" type="submit" value="提交">
						<input type="reset" class="getReset" value="重置"/>
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>