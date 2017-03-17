<?php
//概率算法,6个奖项  
$prize_arr = array( 
	'0' => array('id'=>1,'prize'=>'iphone6','v'=>1),
	'1' => array('id'=>2,'prize'=>'数码相机','v'=>5),
	'2' => array('id'=>3,'prize'=>'音箱设备','v'=>10),
	'3' => array('id'=>4,'prize'=>'50Q币','v'=>24),
	'4' => array('id'=>5,'prize'=>'10Q币','v'=>60),
	'5' => array('id'=>6,'prize'=>'1Q币','v'=>1900),
);
//每个奖品的中奖几率,奖品ID作为数组下标
foreach(
	$prize_arr as $val){$item[$val['id']] = $val['v'];
}
function get($item){ 
	//中奖概率基数
	$num = array_sum($item);//当前一等奖概率1/2000  
	foreach($item as $k=>$v){ 
	//获取一个1到当前基数范围的随机数 
		$rand = mt_rand(1,$num);
        if($rand <= $v){
            //假设当前奖项$k=2,$v<=5才能中奖
            $res = $k;
            break;
        }else{
            //假设当前奖项$k=6,$v>1900,则没中六等奖,总获奖基数2000-1900,前五次循环都没中则2000-1-5-10-24-60=1900,必中6等奖
            $num -= $v;
        }
    }
    return $res;
}
   
   
$res = get($item);
$h = $prize_arr[$res-1]['prize'];
echo $h.'; ';

?>