<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\brand\add.html";i:1488875712;}*/ ?>
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
			<p style="height:24px;">&nbsp;品牌/车系添加&nbsp;</p>
		</div>
		<form action="<?php echo url('BrandAdd'); ?>" method="post">
			<table width='98%'  border='0' cellpadding='1' cellspacing='1' align="center" class="tabody">
				<tr>
					<td>车系名称</td>
					<td><input type="text" name="brand_name"></td>
				</tr>
				<tr>
					<td>品牌选择</td>
					<td>
						<select name="pid" id="">
							<option value="0">==请选择==</option>
						<?php if(is_array($brandData) || $brandData instanceof \think\Collection): if( count($brandData)==0 ) : echo "" ;else: foreach($brandData as $key=>$v): ?>
							<option value="<?php echo $v['brand_id']; ?>"><?php echo str_repeat('&nbsp;--|',$v['leave'])?><?php echo $v['brand_name']?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>是否显示</td>
					<td>
						<input type="radio" name="start" checked value="0">是
						<input type="radio" name="start" value="1">否
					</td>
				</tr>
				<tr>
					<td colspan="2" class="midum">
						<input class="getSubmit" type="submit" value="提交">
						<input type="reset" class="getReset" value="重置"/>
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>