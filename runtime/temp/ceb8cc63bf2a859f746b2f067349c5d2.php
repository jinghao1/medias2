<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\project\add.html";i:1494838202;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<base href="__PUBLIC__/">
	<meta charset="UTF-8">
	<title></title>
	<!-- 日历插件 -->
	<!-- 引入时间插件 -->
    <script type="text/javascript" src="javascript/time/js/laydate.js"></script>


	<link rel="stylesheet" href="javascript/skin/css/content.css" type="text/css" />
</head>
<body>
<script type="text/javascript">
$(document).ready(function(){

	$(".datepicker").datePicker({
		inline:true,
		selectMultiple:false
	});
	
});
</script>
	<div class="content">
		<div class="GetNav">
			<p style="height:24px;">&nbsp;文档列表&nbsp;</p>
		</div>
		<form action="<?php echo url('add'); ?>" method="post">
			<table width='98%'  border='0' cellpadding='1' cellspacing='1' align="center" class="tabody">
				<tr>
					<td>项目名称</td>
					<td><input type="text" name="project_name"></td>
				</tr>
				<tr>
					<td>品牌</td>
					<td>
						<select name="brand_id[]" class="GetBrand">
							<option value="">==请选择==</option>
							<?php if(is_array($data) || $data instanceof \think\Collection): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
								<option value="<?php echo $v['brand_id']; ?>"><?php echo $v['brand_name']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
	
					</td>
				</tr>
				<!--<tr>
					<td>项目周期</td>
					<td>
						<input type="text" name="beginPeriod" class="inline laydate-icon" id="start">
						<input type="text" name="endPeriod" class="inline laydate-icon" id="end">
					</td>
				</tr>-->
				<tr>
					<td>合同号</td>
					<td>
						<input type="text" name="pj_id"  >
						 
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
<script type="text/javascript" src="javascript/skin/js/jquery-3.0.0.js"></script>
<script type="text/javascript" src="javascript/time/js/laydatetime.js"></script>
<script>
 $(function(){
     $(document).on('change','.GetBrand',function(){
         var brand_id = $(this).val();
         var _this = $(this);
         // alert(_this);
         $.ajax({
             type : 'get',
             url  : "<?php echo url('GetBrandAdd'); ?>",
             data : {brand_id : brand_id},
             dataType : "json",
             success : function(msg){
                 // alert(msg);
                 if(msg.length){
                    var str = "&nbsp;&nbsp;<select class='GetBrand' name='brand_id[]'><option value=''>--请选择--</option>";
                    $(msg).each(function(k,v){
                    str +="<option value="+v.brand_id+">"+v.brand_name+"</option>"
                    })
                    str += "</select>";
                    _this.nextAll().remove();
                    _this.after(str);
                 }

             }
         })
     }) 
   	
   	//入库操作
  //  	$(".getSubmit").click(function(){
	 //    var name = $("input[name='name']").val();
	 //    var brand_id = $("input[name='brand_id[]']").val();
	 //    alert(brand_id);
	 //    // var sex = $("input[name='sex']").val();
	 //    // var url = "<?php echo url('login'); ?>";
	 //    // var timestamp = Date.parse(new Date());
	 //    //     timestamp = timestamp / 1000; 
	 //    // var key = hex_md5(name + password + timestamp);
	 //    // $.post(url,{name:name,password:password,sex:sex,key:key},function(res){
	 //    //   alert(res.code);
	 //    // },'json')
    
  // })
  
  })
</script>