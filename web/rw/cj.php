 
<?php

 
error_reporting(E_ALL | E_STRICT);
 
header('content-type:text/html;charset=utf8');
 
session_start();

$prize = array(//概率
	1 => 3.5, 
	2 => 6.5, 
	3 => 15,
	4 => 75,
);

//最后确认相加等于100
$prizeList = array(
	1 => array('一等奖'), 
	2 => array('二等奖'), 
	3 => array('三等奖'),
	4 => array('谢谢惠顾'),
);
//unset($prize[7]); 直接把10Q这个奖品去掉
$times = 10;
$max = 0;
foreach ($prize as $k => $v)
{
	$max = $v * $times + $max;
	$row['v'] = $max;
	$row['k'] = $k;
	$prizeZone[] = $row;
}
 print_r($prizeZone);//die;
 echo "<br>";
 print_r($max);//die;
$max--; //临界值
$rand = mt_rand(0, $max);
$zone = 1;
foreach ($prizeZone as $k => $v)
{
	if ($rand >= $v['v'])
	{
		if ($rand >= $prizeZone[$k + 1]['v'])
		{
			continue;
		}
		else
		{
			$zone = $prizeZone[$k + 1]['k'];
			break;
		}
	}
	$zone = $v['k'];
	break;
}

$award = $prizeList[$zone][0];

if(!isset($_SESSION[$prizeList[$zone][0]])) 
{
 	$_SESSION['一等奖'] = 3; //计数器设为1.
 	$_SESSION['二等奖'] = 10; //计数器设为1.
 	$_SESSION['三等奖'] = 50; //计数器设为1.
 	$_SESSION['谢谢惠顾'] = 0; //计数器设为1.
 }
 else 
 {
 	$_SESSION[$prizeList[$zone][0]]--; 
 }
 $i = $_SESSION[$prizeList[$zone][0]];
 if($i < 0){
 	echo "谢谢惠顾";
 	exit;
 }
 else
 {
 	echo $award;
 }