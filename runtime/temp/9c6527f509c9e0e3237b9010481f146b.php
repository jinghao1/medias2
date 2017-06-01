<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:99:"/Applications/XAMPP/xamppfiles/htdocs/medias/public/../application/admin/view/user/getmenulist.html";i:1489566760;}*/ ?>
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
	<a href="<?php echo url('MenuAdd'); ?>"><input type="button" value="添加菜单"></a>
</div> 
<!--  内容列表   -->
<form name="form2">

	<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
	<tr bgcolor="#E7E7E7">
		<td height="24" colspan="10" background="javascript/skin/images/tbg.gif">&nbsp;菜单列表&nbsp;</td>
	</tr>
	<tr align="center" bgcolor="#FAFAF1" height="22">
		<td>ID</td>
		<td>权限名称</td>
		<td>节点</td>
		<td>是否启用</td>
		<td>添加时间</td>
		<td>操作</td>
	</tr>

	<?php if(is_array($MenuData) || $MenuData instanceof \think\Collection): if( count($MenuData)==0 ) : echo "" ;else: foreach($MenuData as $key=>$v): ?>
	<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
		<td><?php echo $v['id']; ?></td>
		<td>
			 <?php if($v['pid'] == 0): ?><span class="folder-open"></span><?php else: ?><span style="display:inline-block;width:<?php echo $v['leftpin']; ?>px;"></span><span>|--</span><?php endif; ?><?php echo $v['title']; ?>
		</td>
		<td align="left"><?php echo $v['name']; ?></td>
		<td>
			<?php if($v['status'] == 1): ?><a href="">关闭</a><?php else: ?><a href="">开启</a><?php endif; ?>
		</td>
		<td align="left"><?php echo $v['create_time']; ?></td> 
		<td><a href="<?php echo url('MenuAdd',['mnid'=>$v['id']]); ?>">编辑</a> | <a href="101">删除</a></td>
	</tr>
	<?php endforeach; endif; else: echo "" ;endif; ?>
	<tr align="right" bgcolor="#EEF4EA">
		<td height="36" colspan="10" align="center"><!--翻页代码 --></td>
	</tr>
	</table>
</form>
</body>
</html>