<?php 
//$a = 1;
session_start();
 
require("../../extend/aes/outaes.php");
$key = "o0o0o0o0o7ujm*IK<9o.00ew00O0O";//加密秘钥
$aes = new Aes($key);
error_reporting(E_ALL | E_STRICT);
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
 
//echo "**********<br>"; 
//print_r($arrstr);
//echo "<br>====----<br>"; 
//echo $str;
//echo "<br>";
//$end = $aes->decrypt($str);//解密
//var_dump($end);
//$_SESSION[$end]=1;
//echo "<br>========";
//var_dump($_SESSION) ;
//echo "<br>";
//echo "====<br>====";

function isMobile(){   
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备  
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))  
    {  
        return true;  
    }   
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息  
    if (isset ($_SERVER['HTTP_VIA']))  
    {   
        // 找不到为flase,否则为true  
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;  
    }   
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高  
    if (isset ($_SERVER['HTTP_USER_AGENT']))  
    {  
        $clientkeywords = array ('nokia',  
            'sony',  
            'ericsson',  
            'mot',  
            'samsung',  
            'htc',  
            'sgh',  
            'lg',  
            'sharp',  
            'sie-',  
            'philips',  
            'panasonic',  
            'alcatel',  
            'lenovo',  
            'iphone',  
            'ipod',  
            'blackberry',  
            'meizu',  
            'android',  
            'netfront',  
            'symbian',  
            'ucweb',  
            'windowsce',  
            'palm',  
            'operamini',  
            'operamobi',  
            'openwave',  
            'nexusone',  
            'cldc',  
            'midp',  
            'wap',  
            'mobile'  
        );   

        // 从HTTP_USER_AGENT中查找手机浏览器的关键字  
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))  
        {  
            return true;  
        }   
    }   
    // 协议法，因为有可能不准确，放到最后判断  
    if (isset ($_SERVER['HTTP_ACCEPT']))  
    {   
        // 如果只支持wml并且不支持html那一定是移动设备  
        // 如果支持wml和html但是wml在html之前则是移动设备  
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))  
        {  
            return true;  
        }   
    }   

    return false;  
}  




if(!isMobile()){ //pc端
	echo "pc";
	include("../login/login.html");
}else{ //mobile 
	echo 'mobile';
	include("../userreg/reg.html");
}

?>