<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:94:"/Applications/XAMPP/xamppfiles/htdocs/medias/public/../application/admin/view/login/login.html";i:1491446274;}*/ ?>
<!DOCTYPE html>  
<html lang="en">  
<head>  
	<base href="__PUBLIC__/javascript/"/>
    <meta charset="UTF-8">
	      
    <title>管理员登录</title>  
    <link type="text/css" rel="stylesheet" href="popup/css/plug.css">

<link rel="stylesheet" type="text/css" href="popup/css/jquery.prompt.css">
<link href="popup/css/style.css" type="text/css" rel="stylesheet" />

<!--    <link rel="stylesheet" type="text/css" href="Login.css"/>  -->
</head>  
<style type="text/css" media="screen" id="test">
html{   
    width: 100%;   
    height: 100%;   
    overflow: hidden;   
    font-style: sans-serif;   
}   
body{   
    width: 100%;   
    height: 100%;   
    font-family: 'Open Sans',sans-serif;   
    margin: 0;   
    background-color: #4A374A;   
}   
#login{   
    position: absolute;   
    top: 50%;   
    left:50%;   
    margin: -150px 0 0 -150px;   
    width: 300px;   
    height: 300px;   
}   
#login h1{   
    color: #fff;   
    text-shadow:0 0 10px;   
    letter-spacing: 1px;   
    text-align: center;   
}   
h1{   
    font-size: 2em;   
    margin: 0.67em 0;   
}   
input{   
    width: 278px;   
    height: 18px;   
    margin-bottom: 10px;   
    outline: none;   
    padding: 10px;   
    font-size: 13px;   
    color: #fff;   
  /*  text-shadow:1px 1px 1px;  */ 
    border-top: 1px solid #312E3D;   
    border-left: 1px solid #312E3D;   
    border-right: 1px solid #312E3D;   
    border-bottom: 1px solid #56536A;   
    border-radius: 4px;   
    background-color: #2D2D3F;   
}   
.but{   
    width: 300px;   
    min-height: 20px;   
    display: block;   
    background-color: #4a77d4;   
    border: 1px solid #3762bc;   
    color: #fff;   
    padding: 9px 14px;   
    font-size: 15px;   
    line-height: normal;   
    border-radius: 5px;   
    margin: 0;   
}  	
</style>
<body>  
    <div id="login">  
        <h1>商务运营后台</h1>  
       
	    <form action="<?php echo url('loginsong'); ?>" method="post" onsubmit="return ckform()"> 
            <input type="text" name="username" required="required" placeholder="用户名" name="u"></input>  
            <input type="password"  name="password" required="required" placeholder="密码" name="p"></input>  
            <button class="but" type="submit">登录</button>  
        </form>  
    </div>  
</body>  
</html>  
<script type="text/javascript" src="popup/js/jquery.1.7.2.min.js"></script>
<script type="text/javascript" src="popup/js/jquery.prompt.min.js"></script>
<script>
function ckform(){
	$("body").data("fromend",0);
	var username = $("input[name='username']").val();
    var password = $("input[name='password']").val();
    if(!username){$.Prompt("用户名不能为空",1000);return false;}
    if(!password){$.Prompt("密码不能为空",1000);return false;}
    var url = "<?php echo url('login'); ?>";
    $.ajax({  
         type : "post",  
          url : url,  
          data : {username:username,password:password},  
          async : false,  
          dataType:'json',
          success : function(msg){  
              if(msg['code'] == -1 || msg['code'] == -2){
	              $.Prompt(msg['msg'],2000);
	              $("body").data("fromend",1);
	              return false;
	            } 
            if(msg['code'] == 1){
              $.Prompt(msg['msg'],4000);
              //window.location.href = "<?php echo url('index/index'); ?>";
                setTimeout(window.location.href = "<?php echo url('index/index'); ?>", 300);  
              //reutrn false; 
            }
          },
          error:function(err){

			console.log(err);
			alert("请稍后");
	        }  
     }); 
    if($("body").data("fromend")==1) {
	    return false;
    }   
} 
</script>
