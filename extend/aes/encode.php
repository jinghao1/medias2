<?php 
require_once('aes.php');
$key = "www.qinlinhui.cn";//加密秘钥
$aes = new Aes($key);
$str = "秦林慧";//加密字符串
$str = $aes->encrypt($str);//加密
// $str = $aes->decrypt($str);//解密
echo $str;
