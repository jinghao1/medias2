<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\user\userlist.html";i:1488958858;}*/ ?>
<html>
<head>
<base href="__PUBLIC__/">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>文档管理</title>
<link rel="stylesheet" type="text/css" href="javascript/skin/css/base.css">
</head>
<body leftmargin="8" topmargin="8" background='skin/images/allbg.gif'>

<!--  快速转换位置按钮  -->
<!-- <table width="98%" border="0" cellpadding="0" cellspacing="1" bgcolor="#D1DDAA" align="center">
<tr>
 <td height="26" background="skin/images/newlinebg3.gif">
  <table width="98%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td align="center">
    <input type='button' class="coolbg np" onClick="location='';" value='添加文档' />
    <input type='button' class="coolbg np" onClick="location='';" value='我的文档' />
    <input type='button' class='coolbg np' onClick="location='';" value='稿件审核' />
    <input type='button' class="coolbg np" onClick="location='';" value='栏目管理' />
    <input type='button' class="coolbg np" onClick="location='';" value='更新列表' />
    <input type='button' class="coolbg np" onClick="location='';" value='更新文档' />
    <input type='button' class="coolbg np" onClick="location='';" value='文章回收站' />
 </td>
 </tr>
</table>
</td>
</tr>
</table> -->
<div style="margin-left:10px;">
	<a href="<?php echo url('UserAdd'); ?>"><input type="button" value="添加用户"></a>
</div> 
<!--  内容列表   -->
<form name="form2">

	<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
	<tr bgcolor="#E7E7E7">
		<td height="24" colspan="10" background="javascript/skin/images/tbg.gif">&nbsp;文档列表&nbsp;</td>
	</tr>
	<tr align="center" bgcolor="#FAFAF1" height="22">
		<td>用户ID</td>
		<td>管理员名称</td>
		<td>管理员角色</td>
		<td>状态</td>
		<td>操作</td>
	</tr>

	<?php if(is_array($UserData) || $UserData instanceof \think\Collection): if( count($UserData)==0 ) : echo "" ;else: foreach($UserData as $key=>$v): ?>
	<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
		<td><?php echo $v['user_id']; ?></td>
		<td><?php echo $v['username']; ?></td>
		<td align="left"><?php echo $v['title']; ?></td>
		<td>
			<?php if($v['start'] == 1){?>
				已生效
			<?php
			}else{
			?>
				启动
			<?php
			}
			?>
		</td>
		<td><a href="<?php echo url('UserAdd',['user_id'=>$v['user_id'],'edit'=>1]); ?>">编辑</a> | <a href="<?php echo url('Deluser',['user_id'=>$v['user_id'],'del'=>1]); ?>">删除</a></td>
	</tr>
	<?php endforeach; endif; else: echo "" ;endif; ?>
	
	<!-- <tr bgcolor="#FAFAF1">
	<td height="28" colspan="10">
		&nbsp;
		<a href="javascript:selAll()" class="coolbg">全选</a>
		<a href="javascript:noSelAll()" class="coolbg">取消</a>
		<a href="javascript:updateArc(0)" class="coolbg">&nbsp;更新&nbsp;</a>
		<a href="javascript:checkArc(0)" class="coolbg">&nbsp;审核&nbsp;</a>
		<a href="javascript:adArc(0)" class="coolbg">&nbsp;推荐&nbsp;</a>
		<a href="javascript:moveArc(0)" class="coolbg">&nbsp;移动&nbsp;</a>
		<a href="javascript:delArc(0)" class="coolbg">&nbsp;删除&nbsp;</a>
	</td>
	</tr> -->
	<tr align="right" bgcolor="#EEF4EA">
		<td height="36" colspan="10" align="center"><!--翻页代码 --></td>
	</tr>
	</table>
</form>
</body>
</html>