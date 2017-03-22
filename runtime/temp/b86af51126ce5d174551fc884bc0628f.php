<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\dealer\add.html";i:1490171658;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<base href="__PUBLIC__/">
	<meta charset="UTF-8">
	<title>注册信息</title>
	<!-- 引入时间插件 -->
    <script type="text/javascript" src="javascript/time/js/laydate.js"></script>
    <script type="text/javascript" src="javascript/skin/js/basesong.js"></script>

	<link rel="stylesheet" href="javascript/skin/css/content.css" type="text/css" />
</head>
<body>
	<div class="content">
		<div class="GetNav">
			<p style="height:24px;">&nbsp;用户预约购买信息&nbsp;</p>
		</div> 
		<form action="<?php echo url('add'); ?>" method="post" onsubmit="return checkname()">
			 
			<?php if(isset($dealer_info[0]['dealer_id'])): ?>
				<input type="text" style="display: none;" name="dealer_id" value="<?php echo $dealer_info['0']['dealer_id']; ?>">
				 
			<?php endif; ?>
			<table width='98%'  border='0' cellpadding='1' cellspacing='1' align="center" class="tabody">
				<tr>
					<td>姓名</td>
					<td><input type="text" name="name" value="<?php echo isset($dealer_info['0']['name']) ? $dealer_info['0']['name'] :  ''; ?>"></td>
				</tr>
				<tr>
					<td>性别</td>
					<td>
						<?php if($dealer_info['0']['sex'] == '2'): ?>
						<input type="radio" name="sex"  value="1">男
						<input type="radio" name="sex" checked value="2">女
						<?php else: ?>
						<input type="radio" name="sex" checked value="1">男
						<input type="radio" name="sex" value="2">女
						<?php endif; ?>
						
					</td>
				</tr>
				<tr>
					<td>手机号</td>
					<td>
						<input name="phone" type="number" value="<?php echo isset($dealer_info['0']['phone']) ? $dealer_info['0']['phone'] :  ''; ?>">
					</td>
				</tr>
				 
				<tr>
					<td>项目</td>
					<td> 
						<select name="project_id" class="ProJect">
							<option value="0">==请选择==</option> 
							<?php if(is_array($ProjectName) || $ProjectName instanceof \think\Collection): if( count($ProjectName)==0 ) : echo "" ;else: foreach($ProjectName as $key=>$v): ?>
								<option value="<?php echo $v['id']; ?>"  <?php if(($dealer_info[0]['project_id'] ==$v['id'])): ?> selected <?php endif; ?> ><?php echo $v['project_name']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>车系/车型</td>
					<td>
						<?php if(!empty($dealer_info[0]['car_series_id'])): ?>
							<?php echo $dealer_info[0]['car_series_id']; else: ?> 
							<select name="car_series_id[]" class="GetCar">
								<option value="0">==请选择==</option>
								<?php if(is_array($data) || $data instanceof \think\Collection): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
									<option value="<?php echo $v['brand_id']; ?>"><?php echo $v['brand_name']; ?></option>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td>购车时间</td>
					<td>
						<select name="buy_car_time" > 
							<?php if(is_array($buytime) || $buytime instanceof \think\Collection): if( count($buytime)==0 ) : echo "" ;else: foreach($buytime as $key=>$mv): ?>
								<option value="<?php echo $mv['id']; ?>"  <?php if(($dealer_info[0]['buy_car_time'] == $mv['id'])): ?> selected <?php endif; ?> ><?php echo $mv['timename']; ?></option> 
							<?php endforeach; endif; else: echo "" ;endif; ?>
							 
						</select>
						<!--<input type="text" name="car_time" class="inline laydate-icon" id="start"  value="<?php echo isset($dealer_info['0']['car_time']) ? $dealer_info['0']['car_time'] :  ''; ?>">-->
					</td>
				</tr>
				<tr>
					<td>选择经销商</td>
					<td>
						<?php if(!empty($dealer_info[0]['dealer_name'])): ?>
							<?php echo $dealer_info[0]['dealer_name']; else: ?> 
							<select name="dealer_name[]" class="GetDealer">
								<option value="">==请选择==</option>
							<?php if(is_array($DealerData) || $DealerData instanceof \think\Collection): if( count($DealerData)==0 ) : echo "" ;else: foreach($DealerData as $key=>$v): ?>
								<option value="<?php echo $v['dealer_id']; ?>"><?php echo $v['dealer_name']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						<?php endif; ?>
						
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
<script type="text/javascript" src="javascript/time/js/laydatetime.js"></script>
<script type="text/javascript" src="javascript/skin/js/jquery-3.0.0.js"></script>
<script>
 $(function(){

	
	//项目更改
	$(document).on('change','.ProJect',function(){
         var project_id = $(this).val();
         var _this = $(this);
         // alert(_this);
         
         $.ajax({
             type : 'get',
             url  : "<?php echo url('ProjectCar'); ?>",
             data : {project_id : project_id},
             dataType : "json",
             success : function(msg){
	             // alert($.type(msg));
	              //var thetype = $.type(msg);
	              //if(thetype=="object"){
	                 
	              //}
                 //console.log(msg);
                 //console.log(msg.length);
                 if(msg.length){
	                var str = "";
                   
                    $(msg).each(function(k,v){
                    str +="<option value="+v.brand_id+">"+v.brand_name+"</option>"
                    })
                 
                    $(".GetCar").nextAll().remove();
                    $(".GetCar").html(str);
                    
                 }

             }
         })
     })
 	//车系车型 click
     $(document).on('click','.GetCar',function(){ 
         var brand_id = $(this).val();
         var _this = $(this);
         // alert(_this);
         //console.log(brand_id);
         if(brand_id>0){
		    $.ajax({
	             type : 'get',
	             url  : "<?php echo url('CarClass'); ?>",
	             data : {brand_id : brand_id},
	             dataType : "json",
	             success : function(msg){
	                 //alert($.type(msg));
	                 //console.log(msg);
	                 if(msg.length){
	                    var str = "<select class='GetCar' name='car_series_id[]'>";
	                    $(msg).each(function(k,v){
	                    str +="<option value="+v.brand_id+">"+v.brand_name+"</option>";
	                    });
	                    str += "</select>";
	                    _this.nextAll().remove();
	                    _this.after(str);
	                 }else{
		                _this.nextAll().remove(); 
	                 }

	             }
	         })
         }else{
	         _this.nextAll().remove();
         }
        
     })

     //经销商
      $(document).on('click','.GetDealer',function(){
         var dealer_id = $(this).val();//当前id
         var proid = $("select[name='project_id']").val(); //获取项目id
         //console.log(proid);
         var _this = $(this);
         // alert(dealer_id);
         $.ajax({
             type : 'get',
             url  : "<?php echo url('GetDealer'); ?>",
             data : {dealer_id : dealer_id,proid:proid},
             dataType : "json",
             success : function(msg){
                 if(msg.length < 1){
                 	 _this.nextAll().remove();
                 }
                 
                 if(msg.length){
                    var str = "<select name='dealer_name[]' class='GetDealer'>";
                    $(msg).each(function(k,v){
                    str +="<option value="+v.dealer_id+">"+v.dealer_name+"</option>"
                    })
                    str += "</select>";
                    _this.nextAll().remove();
                    _this.after(str);
                 }

             }
         })
     }) 

     

		 
  })
</script>