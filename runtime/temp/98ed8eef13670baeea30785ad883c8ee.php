<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:99:"/Applications/XAMPP/xamppfiles/htdocs/medias/public/../application/admin/view/cityact/citylist.html";i:1495425264;}*/ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>活动城市列表</title>
<base href="__PUBLIC__/">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/bootstrap-switch.css">
	
<script type="text/javascript" src="javascript/skin/js/jquery.1.12.js"></script>
<script type="text/javascript" src="javascript/skin/js/bootstrap.min.js"></script>
<script type="text/javascript" src="javascript/skin/js/bootstrap-modal.js"></script>
	<!-- 日历插件 -->
	<!-- 引入时间插件 -->
<script type="text/javascript" src="javascript/time/js/laydate.js"></script>
<!--<script type="text/javascript" src="javascript/skin/js/jquery.SuperSlide.2.1.1.js"></script>-->
<script type="text/javascript" src="javascript/skin/js/bootstrap-switch.js"></script>	
<!--<link href="javascript/skin/css/summarize.css" rel="stylesheet" type="text/css"/>-->
<style type="text/css" media="screen" id="test">
	.citybor{
		border:1px solid #ccc;
		float:left;
		margin:2px;
		padding:2px;
	} 
	  
	.dlpro{
		width:100%;
		float:left;
		margin:5px;
	}
	body{
		width:99%;
	}
	.bkblue{
		background:green;
		color:#fff;
	}
	.bkblue.bkwarning{
		background: #D17;
		color:#fff;
	}
	.bordernone{
		display: none;
	}
	input[name="begt"]{
		color:black;
		border:none;
		width:80px;
		padding:0px;
	}
</style>

<script type="text/javascript">
	function gtident(cityid,obj){
		var proname = $(obj).parent().parent().find(".province_name").html(); //省份名称
		var cityname = $(obj).find("span").html();
		console.log(cityname);
		//alert(cityid);
		$("input[name='cityidoid']").val(cityid);
		//抬头命名 
		$("#myact").find('.modal-title').html(proname+"-"+cityname);
		//活动地点赋值
		$("#firstname").val($(obj).find("input[name='addr']").val());
		//活动时间赋值
		$("#start1").val($(obj).find("input[name='begt']").val());
		$("#end1").val($(obj).find("input[name='endt']").val());
		//负责人赋值

		$("#fzren").val($(obj).find("input[name='fzrn']").val());
		//活动状态
		var actstatu =$(obj).find("input[name='actstu']").val();
		//
		//console.log(actstatu);
		var curwrap = $("input[name='actstatu']").is(":checked");
		if(actstatu==1){
			if(curwrap==false){
				$("#switch-modal").bootstrapSwitch("toggleState");
			} 
		}else if(curwrap==true){ 
			$("#switch-modal").bootstrapSwitch("toggleState"); 

		}
		//筹备中  当前数据
		var wait = $(obj).find("input[name='cbstatu']").val();
		//模态框数据 //筹备中 
		var cbwaiting = $("input[name='cbwaiting']").is(":checked");
		if(wait==1){
			if(cbwaiting==false){
				$("#switchwaiting").bootstrapSwitch("toggleState");
			}
		}else if(cbwaiting==true){
			$("#switchwaiting").bootstrapSwitch("toggleState");
		}

	 
		//$("#conactcity").html(cityid);
		$('#myact').modal({
		  keyboard: false
		}); 
	}
	function comdata(){
		//活动地点
		var actaddr = $("#firstname").val();
		//状态
		var actstatu = $("input[name='actstatu']").is(":checked");
		//筹备中
		var cbwaiting = $("input[name='cbwaiting']").is(":checked");
		//console.log(actstatu);
		//开始活动时间
		var begt = $("input[name='beginPeriod']").val();
		//截止活动时间
		var endt = $("input[name='endPeriod']").val();
		//负责人
		var fzr = $("input[name='fzuser']").val();
		//修改城市的id
		var cityid = $("input[name='cityidoid']").val();
		//console.log(actstatu);
		//console.log("song");
		var testurl = "/medias/public/index.php/admin/Chebaihui/upactaddr";
		$.ajax({
	        type: "post",
	        url: testurl,
	        data:{actaddr:actaddr,actstatu:actstatu,cbwaiting:cbwaiting,begt:begt,endt:endt,fzr:fzr,cityid:cityid},
	        dataType: 'jsonp',
	        jsonp:'callback',
	        callback:"getHeadCallBack",
	        timeout: 5000,
	        success: function (result) {
	        	if(result.statu==1){
	        		window.location.reload('/medias/public/index.php/admin/cityact/citylist.html');
					//$('#myact').modal('hide')
	        	}
	           //console.log(result);
	        },
	        error:function(err){
	        	console.log(err.responseText);
	        }
	    }); 
	}
 
</script>
</head>
<body leftmargin="8" topmargin="8"  >
<!-- Button trigger modal -->
 <!-- Modal 弹出层更改城市活动信息 -->
<div class="modal fade" id="myact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" id="conactcity">
	    <form class="form-horizontal" role="form">
	    	<input type="hidden" name="cityidoid" type="number">
          <div class="form-group">
		    <label for="firstname" class="col-sm-2 control-label">活动地点</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="firstname" placeholder="请输入活动地址">
		    </div>
		  </div>
		 
		  <div  class="form-group">
			   <label   class="col-sm-2 control-label">活动时间</label>
			   <div class="col-sm-10">
				  <input type="text" name="beginPeriod" class="inline laydate-icon" id="start1" >~
				  <input type="text" name="endPeriod" class="inline laydate-icon" id="end1">
				</div>
		  </div>
		  <div class="form-group">
		    <label for="fzren" class="col-sm-2 control-label">负责人</label>
		    <div class="col-sm-10">
		           <input id="fzren" name="fzuser" type="text" >
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="switch-modal" class="col-sm-2 control-label">筹备中</label>
		    <div class="col-sm-10"> 
		           <input  name="cbwaiting" id="switchwaiting"   type="checkbox" checked>
		    </div>
		  </div>

		   <div class="form-group">
		    <label for="switch-modal" class="col-sm-2 control-label">状态</label>
		    <div class="col-sm-10"> 
		           <input  name="actstatu" id="switch-modal"   type="checkbox" checked>
		    </div>
		  </div>
		 
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" onclick="comdata()">提交</button>
      </div>
    </div>
  </div>
</div>

 
<script>
var start = {
  elem: '#start1',
  format: 'YYYY/MM/DD',
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
  format: 'YYYY/MM/DD',
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
<div id="headerid" class="header">
	<div class="header_nav">
	    <div class="header_nav_content">
	        <ul class="header_nav_left"> 
	            <li class="city_position" id="city_position"> 
	          
	                <div class="city_chose" id="city_chose" style="">
	                    
	                    <div class="hot_city_num"> 
	                        <div class="city_scroll">
	                        	<div  class="city_chose_region" id="city_chose_region" style="margin-top: 0px;display:none;"> 
		                        <!--循环省份字母标签-->
		            <?php if(is_array($zmlist) || $zmlist instanceof \think\Collection): if( count($zmlist)==0 ) : echo "" ;else: foreach($zmlist as $key=>$zmv): ?>
		            	<a href="#" ><?php echo $zmv; ?></a>
		            <?php endforeach; endif; else: echo "" ;endif; ?>        
		            			</div> 
	                <div class="city_scroll">
	                    <div class="city_chose_region" id="city_chose_region" style="margin-top: 0px;">  
		                    <?php foreach($proii as $proinfo): ?>
		                    	<dl class="dlpro">
									<dt>
										<span class="province_tx"><?php echo $proinfo['initial']; ?></span>
										<span class="province_name"><?php echo $proinfo['cityname']; ?></span>
									</dt> 
									<dd>
							 
								<?php foreach($procity[$proinfo['cityid']] as $pk=>$pv): ?>
									 
										<div class=" <?php if(!empty($pv['waiting'])) echo 'bkwarning'; ?>  citybor <?php if(!empty($pv['status'])) echo 'bkblue'; ?>  " onclick="gtident(<?php echo $pv['cityid']; ?>,this)"> 	
											<span><?php echo $pv['cityname']; ?></span>
											
												<?php if(!(empty($pv['begtime']) || ($pv['begtime'] instanceof \think\Collection && $pv['begtime']->isEmpty()))): ?>
													<input name="begt"  value="<?php echo date('Y-m-d',$pv['begtime']); ?>">
												<?php else: ?>
													<input name="begt" type="hidden" value="0">
												<?php endif; if(empty($pv['endtime']) || ($pv['endtime'] instanceof \think\Collection && $pv['endtime']->isEmpty())): ?>
													<input name="endt" type="hidden"  value="0">
												<?php else: ?>
													<input name="endt" type="hidden"  value="<?php echo date('Y-m-d',$pv['endtime']); ?>">
												<?php endif; ?>
 											<div class="bordernone"> 	
 												
 												<input name="addr" type="hidden"  value="<?php echo $pv['address']; ?>">
 												<input name="addr" type="hidden"  value="<?php echo $pv['address']; ?>">
 												<input name="addr" type="hidden"  value="<?php echo $pv['address']; ?>">
 												<input name="fzrn"   value="<?php echo $pv['fzrname']; ?>">
 												<input name="addr" type="hidden"  value="<?php echo $pv['address']; ?>">
 												<input name="actstu"  value="<?php echo $pv['status']; ?>">
 												<input name="cbstatu"  value="<?php echo $pv['waiting']; ?>">
											</div>
										</div> 
								 
								<?php endforeach; ?>

									</dd>  
								</dl>
		                    <?php endforeach; ?>
		                    
               			</div> 
            	    </div>
	                        <div class="scrollbar">
	                            <div class="scrollbtn" style="height: 0px; top: 43.3912px;"></div>
	                        </div>
	                    </div>
	                </div>
	            </li> 
	        </ul> 
	    </div>
	</div> 
</div>

<script type="text/javascript">
	
	$("#switch-modal").bootstrapSwitch();
	$("#switchwaiting").bootstrapSwitch();
	

</script>
</body>
</html>