<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\project\show.html";i:1490262360;}*/ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>文档管理</title>
<base href="__PUBLIC__/">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/base.css">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/content.css">
<script language="javascript">
function viewArc(aid){
	if(aid==0) aid = getOneItem();
	window.open("archives.asp?aid="+aid+"&action=viewArchives");
}
function editArc(aid){
	if(aid==0) aid = getOneItem();
	location="archives.asp?aid="+aid+"&action=editArchives";
}
function updateArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives.asp?aid="+aid+"&action=makeArchives&qstr="+qstr+"";
}
function checkArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives.asp?aid="+aid+"&action=checkArchives&qstr="+qstr+"";
}
function moveArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives.asp?aid="+aid+"&action=moveArchives&qstr="+qstr+"";
}
function adArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives.asp?aid="+aid+"&action=commendArchives&qstr="+qstr+"";
}
function delArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives.asp?aid="+aid+"&action=delArchives&qstr="+qstr+"";
}

//获得选中文件的文件名
function getCheckboxItem()
{
	var allSel="";
	if(document.form2.id.value) return document.form2.id.value;
	for(i=0;i<document.form2.id.length;i++)
	{
		if(document.form2.id[i].checked)
		{
			if(allSel=="")
				allSel=document.form2.id[i].value;
			else
				allSel=allSel+"`"+document.form2.id[i].value;
		}
	}
	return allSel;
}

//获得选中其中一个的id
function getOneItem()
{
	var allSel="";
	if(document.form2.id.value) return document.form2.id.value;
	for(i=0;i<document.form2.id.length;i++)
	{
		if(document.form2.id[i].checked)
		{
				allSel = document.form2.id[i].value;
				break;
		}
	}
	return allSel;
}
function selAll()
{
	for(i=0;i<document.form2.id.length;i++)
	{
		if(!document.form2.id[i].checked)
		{
			document.form2.id[i].checked=true;
		}
	}
}
function noSelAll()
{
	for(i=0;i<document.form2.id.length;i++)
	{
		if(document.form2.id[i].checked)
		{
			document.form2.id[i].checked=false;
		}
	}
}
</script>
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
<form name='form3' action='' method='post'>
<input type='hidden' name='dopost' value='' />
<table width='98%'  border='0' cellpadding='1' cellspacing='1' bgcolor='#CBD8AC' align="center" style="margin-top:8px">
  <tr bgcolor='#EEF4EA'>
    <td background='skin/images/wbg.gif'>
      <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
	        <td width='90'>合同号：</td>
            <td width='160'>
	            <input type='text' name='pj_key' value='' /> 
        	</td>
           
        	<td width='90'>项目名称：</td>
        	<td width='160'><input type='text' name='keyxm' value='' /> </td>
        	<td width='90'>车系信息：</td>
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
</form> 
<!--  内容列表   -->
<form name="form2">

<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
	<tr bgcolor="#E7E7E7">
		<td height="24" colspan="10" background="javascript/skin/images/tbg.gif">&nbsp;文档列表&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<!--<button class="outbut"><a style="float:right" href="<?php echo url('Excel/index'); ?>">导出全部</a></button>-->
	</tr>
	<tr align="center" bgcolor="#FAFAF1" height="22">
		<td width="8%">项目ID</td>
		<td width="20%">合同号</td>
		<td width="30%">项目名称</td>
		<td width="20%">车系</td> 
		<td width="22%">操作管理</td>
	</tr>

	<?php if(is_array($data) || $data instanceof \think\Collection): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
	<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
		<td><?php echo $v['id']; ?></td>
		<td align="center"><?php echo $v['pj_id']; ?></td>
		<td align="center"><u><a href="<?php echo url('dealer/show',['id'=>$v['id']]); ?>"><?php echo $v['project_name']; ?></a></u></td>
		<td align="center"><?php echo $v['brand_name']; ?></td>
		
		<td><!--<a href="101">编辑</a> |-->
			<?php if($delopt): ?>
				<a onclick="return confirm('项目删除后，其下边所有用户提交信息均会被删除，确认删除？');" href="<?php echo url('project/pjdel',['id'=>$v['id']]); ?>">删除</a>
			<?php else: ?>无操作权限
			<?php endif; ?>
		 	
		</td>
	</tr>
	<?php endforeach; endif; else: echo "" ;endif; ?>

	<!--<tr bgcolor="#FAFAF1">
		<td height="28" colspan="10">
			&nbsp;
			<a href="javascript:selAll()" class="coolbg">全选</a>
			<a href="javascript:noSelAll()" class="coolbg">取消</a>
		 
			<a href="javascript:delArc(0)" class="coolbg">&nbsp;删除&nbsp;</a>
		</td>
	</tr>-->
	<tr align="right" bgcolor="#EEF4EA">
		<td height="36" colspan="10" align="center" class="pageinfo"> <?php echo $data->render(); ?></td>
	</tr>
 	 

</table>

</form>

</body>
</html>