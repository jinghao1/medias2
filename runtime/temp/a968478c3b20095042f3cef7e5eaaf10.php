<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\dealer\show.html";i:1499327855;}*/ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>文档管理</title>
<base href="__PUBLIC__/">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/base.css">
<script type="text/javascript" src="javascript/skin/js/jquery.1.12.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/skin/css/content.css">
		<!-- 引入时间插件 -->
<script type="text/javascript" src="javascript/time/js/laydate.js"></script>
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

<!--end 导出-->
<!--  内容列表   -->
<script type="text/javascript">
	//信息筛选
	function shaixuan(){
		var curproid = $("input[name='theproid']").val(); //项目id
		var newsid = $("input[name='theenewsid']").val(); //信息分类id
		var beginPeriod = $("input[name='beginPeriod']").val(); //开始注册时间
		var endPeriod = $("input[name='endPeriod']").val(); //截止注册时间
		//console.log(curproid,newsid,beginPeriod,endPeriod);
		//var url = "<?php echo url('dealer/show',['id'=>"+curproid+",'enews'=>"+newsid+",'begtime'=>'"+beginPeriod+"','endtime'=>'"+endPeriod+"']); ?>";
		var url ="/medias/public/index.php/admin/dealer/show/?id="+curproid+"&enews="+newsid+"&begtime="+beginPeriod+"&endtime="+endPeriod;
	  
		window.location.href = url;
	}
	//信息导出
	function outinfo(){
		var curproid = $("input[name='theproid']").val(); //项目id
		var newsid = $("input[name='theenewsid']").val(); //信息分类id
		var beginPeriod = $("input[name='beginPeriod']").val(); //开始注册时间
		var endPeriod = $("input[name='endPeriod']").val(); //截止注册时间
		//console.log(curproid,newsid,beginPeriod,endPeriod);
		//var url = "<?php echo url('dealer/show',['id'=>"+curproid+",'enews'=>"+newsid+",'begtime'=>'"+beginPeriod+"','endtime'=>'"+endPeriod+"']); ?>"; 
		var url ="/medias/public/index.php/admin/excel/index/?proid="+curproid+"&enewsid="+newsid+"&begtime="+beginPeriod+"&endtime="+endPeriod;
	 
		window.location.href = url;
	}
</script>
<form name="form2">

<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
	<tr bgcolor="#E7E7E7">
		<td height="24" colspan="10" background="javascript/skin/images/tbg.gif">
			
			<?php if($delopt == 1 && $proid==32): ?>
				 
				<a style="float:left" href="<?php echo url('dealer/showlot',['id'=>$proid]); ?>"><div class="outbutlef   ">&nbsp;抽奖情况&nbsp;</div></a> 
			<?php endif; ?>
			
			<!--<button class="outbutlef  <?php if(!empty($allout)) echo 'ckback'; ?> "><a style="float:left" href="<?php echo url('dealer/show',['enews'=>'3','id'=>$proid]); ?>">&nbsp;  全 部  &nbsp;</a></button>
			<button class="outbutlef <?php if(!empty($yxenews)) echo 'ckback'; ?>"><a style="float:left" href="<?php echo url('dealer/show',['enews'=>'1','id'=>$proid]); ?>">&nbsp; 营 销 策 划 &nbsp;</a></button>
			<button class="outbutlef <?php if(!empty($llenews)) echo 'ckback'; ?>"><a style="float:left" href="<?php echo url('dealer/show',['enews'=>'2','id'=>$proid]); ?>">&nbsp; 流 量 变 现 &nbsp;</a></button>-->
			<a style="float:left" href="<?php echo url('dealer/show',['enews'=>'3','id'=>$proid]); ?>"><div class="outbutlef  <?php if(!empty($allout)) echo 'ckback'; ?> ">&nbsp;  全 部  &nbsp;</div></a> 
			<a style="float:left" href="<?php echo url('dealer/show',['enews'=>'1','id'=>$proid]); ?>"><div class="outbutlef <?php if(!empty($yxenews)) echo 'ckback'; ?>">&nbsp; mobile &nbsp;</div></a> 
			<a style="float:left" href="<?php echo url('dealer/show',['enews'=>'2','id'=>$proid]); ?>"><div class="outbutlef <?php if(!empty($llenews)) echo 'ckback'; ?>">&nbsp; pc &nbsp;</div></a>
			<?php if($proid==43): ?>
				<a style="float:left" href="<?php echo url('dealer/show',['enews'=>'4','id'=>$proid]); ?>"><div class="outbutlef <?php if(!empty($h5out)) echo 'ckback'; ?>">&nbsp; H5 &nbsp;</div></a>
			<?php endif; ?>
			<span   style="float:left" ><div class="<?php if(!empty($begtime)) echo 'ckback'; ?> outbutlef  <?php if(!empty($endtime)) echo 'ckback'; ?> " onclick="shaixuan()"> &nbsp; 筛选 &nbsp;</div></span> 
			<span>
				<input type="hidden" name="theproid" value="<?php echo $proid; ?>">
				<input type="hidden" name="theenewsid" value="<?php echo $enewsid; ?>">
			开始注册时间:&nbsp;<input type="text" name="beginPeriod" class="inline laydate-icon" style="padding-right:0px;" value="<?php echo $begtime; ?>" id="start1" >&nbsp;~&nbsp;
			截止注册时间:&nbsp;<input type="text" name="endPeriod" class="inline laydate-icon" style="padding-right:0px;" value="<?php echo $endtime; ?>" id="end1"></span>
			
			<span>&nbsp;项目名称:&nbsp;<?php echo $proname; ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> 
			<span style="float:right" ><div onclick="outinfo()" class="outbutlef ">&nbsp;导 出 &nbsp;</div></span> 
			<!--<a style="float:right" href="<?php echo url('Excel/index',['proid'=>$proid,'enewsid'=>$enewsid]); ?>"> <div class="outbut"> &nbsp;导 出 &nbsp;</div></a>-->
			&nbsp;&nbsp;&nbsp;
		</td>
		 
		 
	</tr>
	<tr align="center" bgcolor="#FAFAF1" height="22">
		<td >用户ID</td>
 
		<td >姓名</td>
		<td >性别</td>
		<td >手机号</td>
		<?php if($proid==32): ?>
		<td >获奖信息</td>
	<!--	<td >购车时间</td>-->
		<?php endif; if($proid==36 || $proid==44): ?>
			<td>购车时间</td>
		<?php endif; ?>
		<td >经销商地址名称</td>
		<td >车系车型</td>
		<td >注册时间</td>
		<td >操作</td>
	</tr>

	<?php if(is_array($data) || $data instanceof \think\Collection): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
		<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
			<td><?php echo $v['dealer_id']; ?></td>
			 
			<td align="left"><?php echo $v['name']; ?></td>
			<td align="left"><?php if($v['sex'] == 1): ?>男<?php elseif($v['sex'] == 2): ?>女<?php else: ?>未选择<?php endif; ?></td>
			<td align="left"><?php echo $v['phone']; ?></td>
			<?php if($proid==32): ?>
		    <td ><?php echo $v['lotname']; ?></td>
			 
			<?php endif; if($proid==36 || $proid==44): ?>
		    <td ><?php echo $v['buy_car_time']; ?></td>
			 
			<?php endif; ?>
			<td ><?php echo $v['dealer_name']; ?></td>
			<td ><?php echo $v['car_series_id']; ?></td>
			<td ><?php echo $v['time']; ?></td>
			<td>
				<?php if($editopt): ?>
					<a href="<?php echo url('add',['proid'=>$v['project_id'],'dealer_id'=>$v['dealer_id'],'edit'=>1]); ?>">编辑</a>
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
<script>
var start = {
  elem: '#start1',
  format: 'YYYY/MM/DD hh:mm',
  //min: laydate.now(), //设定最小日期为当前日期
  max: '2099-06-16 23:59:59', //最大日期
  istime: true,
  istoday: false,
  choose: function(datas){
     end.min = datas; //开始日选好后，重置结束日的最小日期
     end.start = datas //将结束日的初始值设定为开始日
  }
};
var end = {
  elem: '#end1',
 // format: 'YYYY/MM/DD hh:mm',
  format: 'YYYY/MM/DD hh:mm',
  //min: laydate.now(),
  max: '2099-06-16 23:59:59',
  istime: true,
  istoday: false,
  choose: function(datas){
    start.max = datas; //结束日选好后，重置开始日的最大日期
  }
};
laydate(start);
laydate(end);

</script>
</body>
</html>