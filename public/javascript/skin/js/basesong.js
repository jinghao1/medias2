//用户注册表单验证
function checkname(){ 
	//名字不为空
	var name = $("input[name='name']").val();
	if(name == "" ){
		alert("请输入姓名");
		$("input[name='name']").focus();
		return false;
	}else{
		if (!name.match(/^([\u4E00-\u9FA5]{2,4}$)|(^[a-zA-Z]{1,8}$)/)){ 
			alert("抱歉，需要输入2-4位汉字或八个英文字母");
			return false;
		}
	}
	if (!name.match(/^([\u4E00-\u9FA5]{2,4}$)|(^[a-zA-Z]{1,8}$)/)){ 
		alert("抱歉，需要输入2-4位汉字或八个英文字母");
		return false;
	}
	//手机号验证
	var phone = $("input[name='phone']").val();
	if (phone == "") { 
		alert("手机号码不能为空！");  
		$("input[name='phone']").focus(); 
		return false; 
	}  
	if (!phone.match(/^(((13[0-9]{1})|159|153|155|177|170|182|185|186|188|189)+\d{8})$/)) { 
		alert("手机号码格式不正确！");  
		$("input[name='phone']").focus(); 
		return false; 
	}else{
		 
	}
	//邮箱验证
	var email = $("input[name='email']").val();
	if(email == ""){
		alert("邮箱不能为空！");  
		$("input[name='email']").focus(); 
		return false; 
	}
	if (!email.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)) {
		alert("邮箱格式不正确"); 
		$("input[name='email']").focus(); 
		return false; 
	}

	//项目，必选
	var projid = $("select[name='project_id']").val(); 
	if(projid == 0){
		alert("请选择项目");
		$("select[name='project_id']").focus();
		return false; 
	}
	//，车系必选
	var carid = $("select[name='car_series_id[]']").val();
	if(carid == 0){
		alert("请选择车系");
		$("select[name='car_series_id[]']").focus();
		return false; 
	}   
}

//点击分配权限，分配当前用户组id 到body
function authrule(id){
	$("input[name='groupid']").val(id);
}

 

 