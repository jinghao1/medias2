<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\dbjingcai\showjc.html";i:1498111908;}*/ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>竞彩列表</title>
<base href="__PUBLIC__/">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/base.css">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/content.css">
</head>
<body leftmargin="8" topmargin="8" background='skin/images/allbg.gif'>

 
<!--  内容列表   -->
<form name="form2">

<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
	<tr bgcolor="#E7E7E7">
		<td height="24" colspan="10" background="javascript/skin/images/tbg.gif">
			&nbsp;项目名称:&nbsp;东标竞猜&nbsp;&nbsp;&nbsp;&nbsp;  
			<a style="float:left" href="<?php echo url('Dbjingcai/Showjc',['enews'=>'3','id'=>$proid]); ?>"><div class="outbutlef  <?php if(!empty($allout)) echo 'ckback'; ?> ">&nbsp;  全 部  &nbsp;</div></a> 
			<a style="float:left" href="<?php echo url('Dbjingcai/Showjc',['enews'=>'1','id'=>$proid]); ?>"><div class="outbutlef <?php if(!empty($yxenews)) echo 'ckback'; ?>">&nbsp; 真实 &nbsp;</div></a> 
			<a style="float:left" href="<?php echo url('Dbjingcai/Showjc',['enews'=>'2','id'=>$proid]); ?>"><div class="outbutlef <?php if(!empty($llenews)) echo 'ckback'; ?>">&nbsp; 虚假 &nbsp;</div></a>
			 
		<!--	<a style="float:right" href="<?php echo url('Excel/index',['proid'=>$proid,'enewsid'=>$enewsid]); ?>"> <div class="outbut"> &nbsp;导 出 &nbsp;</div></a>-->
			&nbsp;&nbsp;&nbsp;
		</td>
		 
	</tr>
	<tr align="center" bgcolor="#FAFAF1" height="22">
		<td >ID</td> 
		<td >用户手机号</td>
		<td >用户名称</td>
		<td >竞猜价格</td>
		<td >时间</td> 
		<td >真实/虚假</td>  
	</tr>

	<?php if(is_array($data) || $data instanceof \think\Collection): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
		<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
			<td><?php echo $v['id']; ?></td> 
			<td><?php echo $v['phone']; ?></td> 
			<td><?php echo $v['name']; ?></td>  
			<td ><?php echo $v['price']; ?></td>
			<td ><?php echo date('Y-m-d H:i:s',$v['time']); ?></td>
			<td ><?php echo !empty($v['type'])?'虚拟' : '真实'; ?></td> 
		</tr>
	<?php endforeach; endif; else: echo "" ;endif; ?>

	<tr align="right" bgcolor="#EEF4EA">
		 
		<td height="36" colspan="10" align="center" class="pageinfo">  <?php echo $page; ?> </td>
	</tr>
</table>

</form>

</body>
</html>