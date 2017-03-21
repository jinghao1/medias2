<?php 


/*********************************************************************
函数名称:encrypt
函数作用:加密解密字符串
使用方法:
加密     :encrypt('str','E','nowamagic');
解密     :encrypt('被加密过的字符串','D','nowamagic');
参数说明:
$string   :需要加密解密的字符串
$operation:判断是加密还是解密:E:加密   D:解密
$key      :加密的钥匙(密匙);
*********************************************************************/
function encrypt($string,$operation="E",$key='00O0Oo0o0o0o0o000o0o0o0o000o0o0o0o000OOOO00oOO00oOO')
{
    $key=md5($key);
    $key_length=strlen($key);
    $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
    $string_length=strlen($string);
    $rndkey=$box=array();
    $result='';
    for($i=0;$i<=255;$i++)
    {
        $rndkey[$i]=ord($key[$i%$key_length]);
        $box[$i]=$i;
    }
    for($j=$i=0;$i<256;$i++)
    {
        $j=($j+$box[$i]+$rndkey[$i])%256;
        $tmp=$box[$i];
        $box[$i]=$box[$j];
        $box[$j]=$tmp;
    }
    for($a=$j=$i=0;$i<$string_length;$i++)
    {
        $a=($a+1)%256;
        $j=($j+$box[$a])%256;
        $tmp=$box[$a];
        $box[$a]=$box[$j];
        $box[$j]=$tmp;
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
    }
    if($operation=='D')
    {
        if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8))
        {
            return substr($result,8);
        }
        else
        {
            return'';
        }
    }
    else
    {
        return str_replace('=','',base64_encode($result));
    }
}
//检测手机端与电脑端访问
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

?>