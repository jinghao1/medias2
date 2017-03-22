

$(document).ready(function(){
	 
	//eidt by song
	
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
	
	
	
})