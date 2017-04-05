<?php  
	$subject = "【最满意的一点】";
	$pattern = '/【(.*)】/';
	preg_match($pattern, $subject, $matches);
	var_dump($matches) ;
?>