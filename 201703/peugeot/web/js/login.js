

$(document).ready(function(){
	 
	//eidt by song
	var encrystr = $("input[name='encrystr']").val();
	//console.log(encrystr);
	//console.log($("body").data('encrystr'));
	//end by song

	
	//默认状态下，将‘省’的值传入
	$.getJSON("http://h5.qlh520.top/media/public/index.php/port/Userreg/listdata", { han: "list"}, function(json){
		//console.log(json);
		$.each(json,function(index,value){
			//console.log(index,value);
			if(index == "DealerData"){
				$.each(value,function(i,v){
					var option_html = '<option value='+v.dealer_id+'>'+v.dealer_name+'</option>';
	  				$("#province").append(option_html);
				})
			}
		})
	})
	//点击‘省’之后，将‘市’遍历进去
	$("#province").click(function(){
		var dealer_id = $("#province").val();
		if(dealer_id == "省份"){
			var option_htmls = '<option>'+"城市"+'</option>';
		  		$("#city").html(option_htmls);
	  		var option_htmlss = '<option>'+"经销商"+'</option>';
	  			$("#dealer").html(option_htmlss);
		}else if(dealer_id != "省份"){
		$.getJSON("http://h5.qlh520.top/media/public/index.php/port/Userreg/undercitybydealerid", { han: "dealist",dealer_id:dealer_id}, function(json){
			/*if(dealer_id != "省份"){*/
				$("#city").html("");
				$.each(json,function(index,value){ 
					var option_html = '<option value='+value.dealer_id+'>'+value.dealer_name+'</option>';
		  			$("#city").append(option_html);
		  			//$("body").data("thedal",value.dealer_id);
		  			/*$("#dealer").append(option_html); */
				})
			/*}*/
			$("#city").trigger("click");
			//$("#city").val($("body").data("thedal"));
		})
		}		
	})
	//点击‘市’之后，将‘经销商’遍历进去
	$("#city").click(function(){
		var dealer_id = $("#city").val();
		if(dealer_id == '城市'){
	  		var option_htmlss = '<option>'+"经销商"+'</option>';
	  			$("#dealer").html(option_htmlss);
		}else if(dealer_id != "城市"){
		$.getJSON("http://h5.qlh520.top/media/public/index.php/port/Userreg/undercitybydealerid", { han: "dealist",dealer_id:dealer_id}, function(json){
			//console.log(dealer_id);
			$("#dealer").html("");
			$.each(json,function(index,value){
				var option_html = '<option value='+value.dealer_id+'>'+value.dealer_name+'</option>';
	  			$("#dealer").append(option_html); 
			})
		})	
		}
	})
	//选中好‘经销商’之后，将‘意向车型’遍历进去
	/*$("#dealer").click(function(){
		var dealer_id = $("#dealer").val();
		$.getJSON("http://h5.qlh520.top/media/public/index.php/port/Userreg/proundercar", { han: "prounder",dealer_id:dealer_id}, function(json){
			//console.log(prounder);
			$("#carType").html("");
			$.each(json,function(index,value){
				var option_html = '<option value='+value.dealer_id+'>'+value.dealer_name+'</option>';
	  			$("#carType").append(option_html); 
			})
		})	
	})*/
	//鼠标放入input框值清空
	$('input').each(function(){
		var default_value = this.value;
		$(this).focus(function(){  
            if(this.value == default_value) {  
                this.value = '';  
            }  
        });
        $(this).blur(function(){  
            if(this.value == '') {  
                this.value = default_value;  
            }  
        });    
	})
	//判断鼠标失去焦点后  手机号是否？
  //  $("input[name='phone']").blur(function(){ 
  //  	var phone = $("input[name='phone']").val(); 
  //          $.getJSON("http://h5.qlh520.top/media/public/index.php/port/Userreg/Checkuniquephone", { han: "ckphone",phone:phone,proid:32}, function(json){
	 //          // console.log("songkkkk");
		//	//console.log(json);
		//	$("input[name='encrystr']").focus();
		//	if(json == 2){
		//		alert('手机已注册');
		//	}else if(json == 3){
		//		alert('手机格式不正确');
		//	}else if(json == 5){
		//		console.log('参数有问题');
		//	}
		//})	
  //  });    
	
	
	$(".btn").click(function(){
		//名字不为空
	 	var name = $("input[name='name']").val();
	 	if(name == "" || name == "姓名" ){
	 		alert("请输入姓名");
	 		$("input[name='name']").focus();
	 		return false;
	 	}
	 	if (!name.match(/^([\u4E00-\u9FA5]{2,4}$)|(^[a-zA-Z]{1,8}$)/)){ 
			alert("抱歉，姓名需要输入2-4位汉字或八个英文字母");
			return false;
		}
	 	//性别，必选
	 	var sex = $("select[name='sexId']").val(); 
	 	//alert(sex); 
	 	if(sex == "请选择性别"){
	 		alert("请选择性别");
	 		$("select[name='sexId']").focus();
	 		return false; 
	 	}
	 	//手机号验证
	 	var phone = $("input[name='phone']").val();
	 	if (phone == "" || phone == "手机号") { 
	 		alert("手机号码不能为空！");  
	 		$("input[name='phone']").focus(); 
	 		return false; 
	 	}  
	 	if (!phone.match(/^(((1[3|5|7|8][0-9]{1}))+\d{8})$/)) { 
	 		alert("手机号码格式不正确！");  
	 		$("input[name='phone']").focus(); 
	 		return false; 
	 	}
	 	//邮箱验证
	 	/*var email = $("input[name='email']").val();
	 	if(email == ""){
	 		alert("邮箱不能为空！");  
	 		$("input[name='email']").focus(); 
	 		return false; 
	 	}
	 	if (!email.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)) {
	 		alert("邮箱格式不正确"); 
	 		$("input[name='email']").focus(); 
	 		return false; 
	 	}*/
	 	//项目，必选
	 	/*var projid = $("select[name='project_id']").val(); 
	 	if(projid == 0){
	 		alert("请选择项目");
	 		$("select[name='project_id']").focus();
	 		return false; 
	 	}*/

	 	//省，必选
	 	var province = $("select[name='provinceId']").val();
	 	//console.log(province);
	 	if(province == "省份"){
	 		alert("请选择省份");
	 		$("select[name='provinceId']").focus();
	 		return false; 
	 	}
	 	//市，必选
	 	var city = $("select[name='cityId']").val(); 
	 	if(city == "城市"){
	 		alert("请选择城市");
	 		$("select[name='cityId']").focus();
	 		return false; 
	 	}
	 	//经销商，必选
	 	var dealer = $("select[name='dealer']").val(); 
	 	if(dealer == "经销商"){
	 		alert("请选择经销商");
	 		$("select[name='dealer']").focus();
	 		return false; 
	 	}
	 	//车系必选
	 	var carid = $("select[name='car_series_id']").val();
	 	if(carid == "意向车型"){
	 		alert("请选择车型");
	 		$("select[name='car_series_id[]']").focus();
			return false; 
	 	}
	 	var dealer_name = province+","+city+","+dealer;
	 	//console.log(dealer_name);
	 	$.getJSON("http://h5.qlh520.top/media/public/index.php/port/Userreg/Comreg", { han: "dealreg",username:name,thesex:sex,numberphone:phone,dealer:dealer_name,model:carid,key:encrystr}, function(json){
			 
			if(jQuery.type(json)=="string"){
				var json = eval('('+json+')'); //字符串转为json格式
			} 
			alert(json.msg);
			console.log(json);
			///$('body').append(json.msg);
			//json.msg = changetoch(json.msg);
			//$("#newsongend").html(json.msg);
			//alert(json.msg);
			//if(json.statu == 2){
			//	alert('手机号已注册');
			//}else if(json.statu == 3){
			//	console.log(json.lotinfo);
			//	alert('成功');
			//}else if(json.statu == 4){
			//	alert('失败');
			//}
		})	

		//$.getJSON("http://h5.qlh520.top/media/public/index.php/port/Userreg/Comreg", { han: "dealreg",name:"宋京浩",sex:"1",phone:"17703186690",dealer_name:"4,7,65",car_series_id:'61'}, function(json){
		//	console.log(json);
		//	//han == "dealreg" 标识 
		//	//json.statu==1 请填写预约信息，谢谢！
		//	// 6 //不是手机号格式
		//	//2抱歉，此手机号已注册，请更换手机号，谢谢！
		//	//4 添加失败
		//	//3 注册成功，并返回抽奖信息 
		//		//=》statu=5，无注册信息；0，此次活动已结束；4，您已抽过奖；1，发奖成功，奖品数量减库成功；
		//		//2，发奖成功，奖品库存减少失败；3，发奖失败
		//})
		
	})
})