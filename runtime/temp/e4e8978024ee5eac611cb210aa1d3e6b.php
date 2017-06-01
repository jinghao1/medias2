<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\user\useradd.html";i:1488960136;}*/ ?>
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
			<p style="height:24px;">&nbsp;用户添加&nbsp;</p>
		</div>
		<form action="<?php echo url('UserAdd'); ?>" method="post">
			<table width='98%'  border='0' cellpadding='1' cellspacing='1' align="center" class="tabody">
				<tr>
					<td>用户名</td>
					<td>
						<input type="text" name="username" value="<?php echo isset($userinfo['username']) ? $userinfo['username'] :  ''; ?>"> 
						<?php if(!empty($userinfo['user_id'])): ?>
						<input type="text" style="display: none;" name="userid" value="<?php echo $userinfo['user_id']; ?>"> 
						<input type="text" style="display: none;" name="edit" value="2"> 
						<?php endif; ?>
					</td>
				</tr>
				<?php if($edit==3): ?>
					<select style="display: none;" name="groupid" id="">
							<option value="0">==请选择==</option>
							<?php if(is_array($GroupData) || $GroupData instanceof \think\Collection): if( count($GroupData)==0 ) : echo "" ;else: foreach($GroupData as $key=>$v): ?>
								<option value="<?php echo $v['id']; ?>"  <?php if(($userinfo['groupid'] ==$v['id'])): ?> selected <?php endif; ?>><?php echo $v['title']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
				<?php else: ?>
				<tr>
					<td>管理员角色</td>
					<td>
						<select name="groupid" id="">
							<option value="0">==请选择==</option>
							<?php if(is_array($GroupData) || $GroupData instanceof \think\Collection): if( count($GroupData)==0 ) : echo "" ;else: foreach($GroupData as $key=>$v): ?>
								<option value="<?php echo $v['id']; ?>"  <?php if(($userinfo['groupid'] ==$v['id'])): ?> selected <?php endif; ?>><?php echo $v['title']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td>密码</td>
					<td>
						<input type="password" name="password" value="">
					</td>
				</tr>
				<tr>
					<td>邮箱</td>
					<td>
						<input type="text" name="email" value="<?php echo isset($userinfo['email']) ? $userinfo['email'] :  ''; ?>">
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