<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\brand\brand_show.html";i:1495683842;}*/ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>文档管理</title>
<base href="__PUBLIC__/">
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

<!--  内容列表   -->
<form name="form2">

<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
	<tr bgcolor="#E7E7E7">
		<td height="24" colspan="10" background="javascript/skin/images/tbg.gif">&nbsp;品牌/车系列表&nbsp;</td>
	</tr>
	<tr align="center" bgcolor="#FAFAF1" height="22">
		<td >车系ID</td>
		<td >品牌/车型名称</td>
		<td >是否显示</td>
		<td >操作</td>
	</tr>

	<?php if(is_array($brandData) || $brandData instanceof \think\Collection): if( count($brandData)==0 ) : echo "" ;else: foreach($brandData as $key=>$v): ?>
		<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
			<td><?php echo $v['brand_id']; ?></td>
			<td align="left">
				<?php echo str_repeat('&nbsp;&nbsp;',$v['leave'])?>
		        <?php echo $v['brand_name']?>
			</td>
			<td >
				<?php  echo $v['start']==0?"显示":"不显示"; ?>
			</td>
			<td><a onclick="return confirm('确认删除？');" href="<?php echo url('Brand/BrandDel',['brand_id'=>$v['brand_id']]); ?>">删除</a></td>
		</tr>
	<?php endforeach; endif; else: echo "" ;endif; ?>

	 
	<tr align="right" bgcolor="#EEF4EA">
		<td height="36" colspan="10" align="center"><!--翻页代码 --></td>
	</tr>
</table>

</form>

</body>
</html>