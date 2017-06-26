<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\user\menuadd.html";i:1498101518;}*/ ?>
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
			<p style="height:24px;">&nbsp;菜单添加&nbsp;</p>
		</div>
		<form action="<?php echo url('MenuAdd'); ?>" method="post">
			<table width='98%'  border='0' cellpadding='1' cellspacing='1' align="center" class="tabody">
				<tr>
					<td>默认顶级</td>
					<td> 
						<select name="pid" id="">
							<option value="0">默认顶级</option>
							<?php if(is_array($MenuData) || $MenuData instanceof \think\Collection): if( count($MenuData)==0 ) : echo "" ;else: foreach($MenuData as $key=>$v): ?> 
								<option value="<?php echo $v['id']; ?>" <?php echo !empty($v['id']) && $v['id']==$menuall['pid']?'selected' : ''; ?> >
									<?php if($v['pid'] == 0): ?><span class="folder-open"></span><?php else: ?><span style="display:inline-block;width:<?php echo $v['leftpin']; ?>px;"></span><span>|--</span><?php endif; ?><?php echo $v['title']; ?>
								</option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select></td>
				</tr>
				<tr>
					<td>菜单名称</td>
					<td>
						<input type="text" name="title" value="<?php echo isset($menuall['title']) ? $menuall['title'] :  ''; ?>" placeholder="菜单名称">
					</td>
				</tr>
				<tr>
					<td>节点</td>
					<td>
						<input type="text" name="name" value="<?php echo isset($menuall['name']) ? $menuall['name'] :  ''; ?>"  placeholder="模块/控制器/方法"><br>
						<span style="color:#aaa;">如：admin/user/addlist(一级节点添加#即可)</span>
					</td>
				</tr>
				<tr>
					<td>排序</td>
					<td>
						<input type="text" name="sort" value="<?php echo isset($menuall['sort']) ? $menuall['sort'] :  ''; ?>"  placeholder="">
					</td>
				</tr>
				<tr>
					<td>是否启用</td>
					<td>
						<input type="radio" name="status" value="1" checked>是
						<input type="radio" name="status" value="0">否
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php if(isset($menuall['id'])): ?>
						<input type="hidden" name="edit" value="<?php echo $menuall['id']; ?>" />
						<?php endif; ?>
						<input class="getSubmit" type="submit" value="提交">
						<input type="reset" class="getReset" value="重置"/>
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>