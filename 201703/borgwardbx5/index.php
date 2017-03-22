<?php  
require("../phpfun/outaes.php"); //加密方法文件
require("../phpfun/encom.php"); //通用方法文件
$key = "o0o0o0o0o7ujm*IK<9o.00ew00O0O";//加密秘钥需要与后台一致
$aes = new Aes($key);
$ip = $_SERVER["REMOTE_ADDR"];  //获取当前ip
 
$time = substr(time(),-5); //获取当前时间戳

$name1 = "son"; // key
$name2 = "qin";	//key

$num = rand(0,9999);
$arr = array('ip'=>$ip,'tm'=>$time,'no'=>$name1,'tw'=>$name2,'nm'=>$num);
shuffle($arr); //打乱数组顺序
$arrstr = implode("-",$arr); 
$str = $aes->encrypt($arrstr);//加密
echo '<input type="hidden" name="encrystr" value="'.$str.'" />';
echo '<meta charset="UTF-8">';
echo '<script type="text/javascript" src="../../public/javascript/skin/js/jquery-3.0.0.js"></script>
<script type="text/javascript" src="../../public/javascript/skin/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="../../public/javascript/skin/js/basesong.js"></script>
';
if(!isMobile()){ //pc端
	echo "pc";
	include("./html/bwo.html");
}else{ //mobile 
	//echo 'mobile';
	include("./html/bwo.html");
	//include("../userreg/reg.html");
}

?>