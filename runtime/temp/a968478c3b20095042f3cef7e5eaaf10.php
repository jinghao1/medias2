<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\dealer\show.html";i:1490005247;}*/ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>文档管理</title>
<base href="__PUBLIC__/">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/base.css">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/content.css">
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
<!--  搜索表单  -->
<!--<form name='form3' action='' method='get'>
<input type='hidden' name='dopost' value='' />
<table width='98%'  border='0' cellpadding='1' cellspacing='1' bgcolor='#CBD8AC' align="center" style="margin-top:8px">
  <tr bgcolor='#EEF4EA'>
    <td background='skin/images/wbg.gif' align='center'>
      <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
            <td width='90'>姓名：</td>
            <td width='160'>
	            <input type='text' name='keybrand' value='' /> 
        	</td>
        	 
        	<td>
	           <input name="imageField" type="image" src="javascript/skin/images/frame/search.gif" width="45" height="20" border="0" class="np" />
	        </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>-->
<!--信息导出excel-->
 
	<!--信息导入，隐藏，已导入成功-->
<!--<form action="<?php echo url('Excel/reader'); ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="importexcel"/> 
    <input type="submit" value="导入"/>
</form>-->
<!--end 导出-->
<!--  内容列表   -->
<form name="form2">

<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
	<tr bgcolor="#E7E7E7">
		<td height="24" colspan="10" background="javascript/skin/images/tbg.gif">
			&nbsp;项目名称:&nbsp;<?php echo $proname; ?>&nbsp;
			<button class="outbutlef ckback"><a style="float:left" href="<?php echo url('dealer/show',['id'=>$proid]); ?>">&nbsp;  全 部  &nbsp;</a></button>
			<button class="outbutlef"><a style="float:left" href="<?php echo url('dealer/show',['enews'=>'1','id'=>$proid]); ?>">&nbsp; 营 销 策 划 &nbsp;</a></button>
			<button class="outbutlef"><a style="float:left" href="<?php echo url('dealer/show',['enews'=>'2','id'=>$proid]); ?>">&nbsp; 流 量 变 现 &nbsp;</a></button>
			 
			<button class="outbut"><a style="float:right" href="<?php echo url('Excel/index',['proid'=>$proid]); ?>"> 导 出 </a></button>
			&nbsp;&nbsp;&nbsp;
		</td>
		 
	</tr>
	<tr align="center" bgcolor="#FAFAF1" height="22">
		<td >用户ID</td>
 
		<td >姓名</td>
		<td >性别</td>
		<td >手机号</td>
		<td >邮箱</td>
		<td >购车时间</td>
		<td >经销商地址名称</td>
		<td >车系车型</td>
		<td >操作</td>
	</tr>

	<?php if(is_array($data) || $data instanceof \think\Collection): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
		<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
			<td><?php echo $v['dealer_id']; ?></td>
			 
			<td align="left"><?php echo $v['name']; ?></td>
			<td align="left"><?php if($v['sex'] == 1): ?>男<?php elseif($v['sex'] == 2): ?>女<?php else: ?>未选择<?php endif; ?></td>
			<td align="left"><?php echo $v['phone']; ?></td>
			<td><?php echo $v['email']; ?></td>
			<td ><?php echo $v['buy_car_time']; ?></td>
			<td ><?php echo $v['dealer_name']; ?></td>
			<td ><?php echo $v['car_series_id']; ?></td>
			<td>
				<?php if($editopt): ?>
					<a href="<?php echo url('add',['dealer_id'=>$v['dealer_id'],'edit'=>1]); ?>">编辑</a>
				<?php else: ?>|
				<?php endif; if($delopt): ?>
					<a onclick="return confirm('确认删除？');" href="<?php echo url('dealer/PjregDel',['dealer_id'=>$v['dealer_id'],'pjid'=>$v['project_id']]); ?>">删除</a>
				<?php else: ?>|
				<?php endif; ?> 
			</td>
		</tr>
	<?php endforeach; endif; else: echo "" ;endif; ?>

	<tr align="right" bgcolor="#EEF4EA">
		 
		<td height="36" colspan="10" align="center" class="pageinfo">  <?php echo $page; ?> </td>
	</tr>
</table>

</form>

</body>
</html>