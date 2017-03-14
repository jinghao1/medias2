<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\rar\phpstudy\WWW\medias\public/../application/port\view\userreg\reg.html";i:1489461339;}*/ ?>

<!DOCTYPE html>
<html lang="en">
<head>
 <base href="__PUBLIC__/"> 
	<meta charset="UTF-8">
	<title>信息注册</title>

	<!-- 引入时间插件 -->
    <!-- <script type="text/javascript" src="javascript/time/js/laydate.js"></script> -->
    <script type="text/javascript" src="javascript/skin/js/basesong.js"></script>
<script type="text/javascript" src="javascript/time/js/laydatetime.js"></script>
<script type="text/javascript" src="javascript/skin/js/jquery-3.0.0.js"></script>
	<link rel="stylesheet" href="javascript/skin/css/content.css" type="text/css" />
	<style>
		input{outline:none; background: none}
		.content{width:500px; padding:0 0 20px 0;overflow: hidden;margin:30px auto;  background:#2a768a;border-radius: 4px}
		ul,li{list-style: none}
		.login{}
		.login li{float:left; width:100%;height:auto;padding-bottom:10px;margin-left:35px;}
		.login li span{float:left; width:100px; text-align: center; font-size:16px;line-height: 34px;}
		.login li input[type="text"]{width:210px; padding-left:10px;height:32px;border:1px solid #bbbbbf;color:000;}
		.login li select{background:none;width:222px;padding-left:10px;height:34px;border:1px solid #bbbbbf;color:000;}
		.tj input,.cz input{border:none;background:none;margin-top:10px; width:160px; float:left; text-align: center;line-height: 40px;margin-right:20px;color:#fff; cursor: pointer;border-radius: 4px;font-size:16px;}
		.tj input{background:#f69401;}
		.cz input{background: #ccc}	
		.regtitle{
			height:24px; 
			text-align: center;
			font-weight: 600;
		}
	</style>

</head>
<body>
	<div class="content">
		<div class="GetNav">
			<p class="regtitle">&nbsp;用户预约购买信息&nbsp;</p>
		</div> 
		<!-- 注册开始  -->
		<div class="login">
			<form action="<?php echo url('reg'); ?>" method="post" onsubmit="return checkname()">
			<ul>
				<li>
					<span>姓名</span>
					<input type="text" name="name" value="<?php echo isset($dealer_info['0']['name']) ? $dealer_info['0']['name'] :  ''; ?>">
				</li>
				<li>
					<span>性别</span>
					<select name="sex">
						<option value="1" selected>男</option>
						<option value="2">女</option>
					</select>
				</li>
				<li>
					<span>手机号</span>
					<input type="text" name="phone" type="number" value="<?php echo isset($dealer_info['0']['phone']) ? $dealer_info['0']['phone'] :  ''; ?>">
				</li>
				<li>
					<span>邮箱</span>
					<input name="email" type="text" value="<?php echo isset($dealer_info['0']['email']) ? $dealer_info['0']['email'] :  ''; ?>">
				</li>
				<li>
					<span>项目</span>
					<select name="project_id" class="ProJect">
						<option value="0">==请选择==</option> 
						<?php if(is_array($ProjectName) || $ProjectName instanceof \think\Collection): if( count($ProjectName)==0 ) : echo "" ;else: foreach($ProjectName as $key=>$v): ?>
							<option value="<?php echo $v['id']; ?>"  <?php if(($dealer_info[0]['project_id'] ==$v['id'])): ?> selected <?php endif; ?> ><?php echo $v['project_name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</li>
				<li>
					<span>车系/车型</span>
					<span>
						<select name="car_series_id[]" class="GetCar">
							<option value="0">==请选择==</option>
							<?php if(is_array($data) || $data instanceof \think\Collection): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
								<option value="<?php echo $v['brand_id']; ?>"><?php echo $v['brand_name']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</span>
				</li>
				<li>
					<span>购车时间</span>
					<span>
						<select name="buy_car_time">
							<?php if(is_array($buytime) || $buytime instanceof \think\Collection): if( count($buytime)==0 ) : echo "" ;else: foreach($buytime as $key=>$mv): ?>
								<option value="<?php echo $mv['id']; ?>"  <?php if(($dealer_info[0]['buy_car_time'] == $mv['id'])): ?> selected <?php endif; ?> ><?php echo $mv['timename']; ?></option> 
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</span>
				</li>
				<li>
					<span>选择经销商</span>
					<span>
						<select name="dealer_name[]" class="GetDealer">
							<option value="">==请选择==</option>
						<?php if(is_array($DealerData) || $DealerData instanceof \think\Collection): if( count($DealerData)==0 ) : echo "" ;else: foreach($DealerData as $key=>$v): ?>
							<option value="<?php echo $v['dealer_id']; ?>"><?php echo $v['dealer_name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</span>
				</li>
				<li>
					<font class="tj"><input  type="submit" value="提交"></font>
					<font class="cz"><input type="reset"  value="重置"/></font>
				</li>
			</ul>
			</form>
			<!-- end-注册 -->
		</div>
	</div>

	<input type="hidden" name="blurfocus" value="" /> 
   
   
</body>
</html>
 
  <script>

	  
 $(function(){ 
	//$("input[name='name']").blur(function(){
         
 //       var nameval =$("input[name='name']").val(); 
 //       // console.log(nameval);
 //       if(nameval == ""){
	//        alert("姓名不能为空");
	//        return;
 //       }
 //       if (!nameval.match(/^([\u4E00-\u9FA5]{2,4}$)|(^[a-zA-Z]{1,8}$)/)){ 
	//		alert("抱歉，需要输入2-4位汉字或八个英文字母");
	//	}
 //   })
	 
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
	                    var str = "</br><select class='GetCar' name='car_series_id[]'>";
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
         var dealer_id = $(this).val();
         var _this = $(this);
         // alert(dealer_id);
         $.ajax({
             type : 'get',
             url  : "<?php echo url('GetDealer'); ?>",
             data : {dealer_id : dealer_id},
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