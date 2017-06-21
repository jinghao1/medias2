<?php
date_default_timezone_set('PRC');
 
error_reporting(E_ALL);
include_once('./simple_html_dom.php');
include_once('../PHPExcel/PHPExcel.php');
 
 //获取a标签内容
function acontent($str){ 
	if($str){
		preg_match_all('/>(.*?)</is',$str,$m);  
		return $m[1][0];
	}else{
		return null;
	}

}
//获取最满意一点
function getbest($str){  
    $str = str_replace("<br/>","",$str);
	$new = array('最满意的一点'=>"",'最不满意的一点'=>"");
	//最满意
	$patt = '/最满意的一点】(.*)【最不满意的一点/';
 
	preg_match($patt,$str,$mats);  
	if(isset($mats[1])){
		$new['最满意的一点'] = $mats[1];
	}
	//最不满意
	$patt2 = '/最不满意的一点】(.*)【空间/';
	preg_match($patt2,$str,$mats2);  
	if(isset($mats2[1])){
		$new['最不满意的一点'] = $mats2[1];
	} 
	return $new;   
} 
//end

//获取 键名，键值 匹配
function getkeyv($str){
	$key = "";
	$patt = '/<dt>(.*)<\/dt>/';
	preg_match($patt,$str,$mats); 
 	if(isset($mats[1])){
	 	$key = $mats[1];
 	}
 	return $key; 
}
//获取 键名，键值 匹配
function getkeyspan($str){
	$key = 0;
	$patt = '/>(.*)<\/span>/';
	preg_match($patt,$str,$mats); 
 	if(isset($mats[1])){
	 	$key = intval($mats[1]) ;
 	}
 	return $key; 
}
//获取当页内容
function curcontent($url='http://k.autohome.com.cn/692/index_1.html'){  
	$html = file_get_html($url);
	$i = 0;
	$newarr = array(); 
	foreach($html->find('div[class=subnav-title-name]') as $kt=>$ev){
		foreach($ev->find("a") as $mkv){
			$carserise = acontent($mkv->outertext);
			$title =  $carserise ;
		}
		
		
	}
	foreach($html->find('div[class=mouth-item]') as $fk=> $element) {
		$test=  $element->find('div[class=cont-title fn-clear]');
	 
		foreach($test as $kt=> $end){ //抬头 
			$mt = 1;
			foreach($end->find("a") as $vt){
				if($mt==1){
					//echo $vt->href."<br>";
					//echo $kt;
					//echo "<br>";
					//$newarr[$fk][$kt][] = $vt->href;
					$newarr[$fk][] = $vt->href;
				} 
				$ft = acontent($vt->outertext); 
				$newarr[$fk][] = $ft;
				$mt++; 
				//var_dump($ft);
				//var_dump($vt->outertext); 
			}
			//echo $end;
			//echo "<br>";
		}
		$content =$element->find('div[class=text-con]');
		 
		foreach($content as $kc=> $con){ 
			//var_dump($con->outertext); 
			$conbest = getbest($con->outertext);
		 
			$newarr[$fk][] = $conbest['最满意的一点'];
			$newarr[$fk][] = $conbest['最不满意的一点'];
			//echo "<br>";
		} 
		$i++;
		if($i>1000){
			break;
		}
		//foreach($element->find('div[class=mouth-item]') as $end){
		//	echo $end."<br>";
		//} 
	} 
	//动力 评分 
	//echo '<meta charset="utf-8">';
	$comarr = array();
	$comnum = array();
	$k = "89";
	 
	foreach($html->find('div[class=position-r]') as $dlk=>$dlm){
		$tt = $dlm->outertext;
		//$ttstr = $tt->outertext;
		$newtt = getkeyv($tt); //key
		$ttspan = ""; //val
		foreach($dlm->find('span[class=font-arial c333]') as $dlk=>$dlv){
			$ttspan = $dlv->outertext; 
		}
		$end = array_key_exists($newtt,$comarr);
		//var_dump()
		$ttspan = getkeyspan($ttspan);
		 
	 
		if($end){
			$comarr[$newtt] = $comarr[$newtt] + $ttspan ;
			$comnum[$newtt] += 1;
		}else{
			$comarr[$newtt] = $ttspan ;
			$comnum[$newtt] = 1;
		}
		 
	} 
	return array('title'=>$title,'info'=>$newarr,'cominfo'=>$comarr,'comnum'=>$comnum);
 
}

//分页规划
function cainfo($numurl='692',$beg,$num){
	$beg = $beg-1;
	$arr = array();
	$end = array('title'=>"无");
	$pfarr = array(); // 评分
	$pfnum = array(); // 评分
	for($beg;$num>0;$num--){
		 
		$thepage = $beg+1;
	 	$beg = $beg+1;
		$url = "http://k.autohome.com.cn/".$numurl."/index_".$thepage.".html";
		$end = curcontent($url);
		$arr[$thepage] = $end['info'];
	 
		if($end['cominfo']){ //评分值 汇总
			foreach($end['cominfo'] as $key=>$val){
				$thek = array_key_exists($key,$pfarr);
				if($thek){
					$pfarr[$key] +=$val;
				}else{
					$pfarr[$key] = $val;
				}
				
			}
		}
		if($end['comnum']){ //评论次数 汇总
			foreach($end['comnum'] as $ky=>$vl){
				$tk = array_key_exists($ky,$pfnum);
				if($tk){
					$pfnum[$ky] += $vl; 
				}else{
					$pfnum[$ky] = $vl; 
				}
			}
		}
	 
	}
	return array("info"=>$arr,'title'=>$end['title'],'cominfo'=>$pfarr,'comnum'=>$pfnum); 
}

if(isset($_POST['urlid'])){
	$allarr = array();
	if(!empty($_POST['urlid'])){
		
		$begin = 1;
		$endnum = 1;
		if(isset($_POST['beg'])&&isset($_POST['end'])){
			
			if($_POST['beg']>0){
				$begin = $_POST['beg'];
			}
			if($_POST['end']>$_POST['beg']){
				$endnum = $_POST['end'];
			}else{
				$endnum = $_POST['beg'];
			}
		}
	 
		$plid = $_POST['urlid'];
		for($begin;$begin<=$endnum;$begin++){ 
		
			//$plurl = 'http://reply.autohome.com.cn/api/comments/show.json?count=50&page='.$begin.'&id='.$plid.'&appid=1&datatype=jsonp&order=0&replyid=0&callback=jQuery172011866505957016305_1497249148807&_=1497249239643';
			$plurl = 'http://reply.autohome.com.cn/api/comments/show.json?id='.$plid.'&page='.$begin.'&appid=4&count=20&datatype=jsonp&callback=jsonpCallback&_=1497492592682';
			$end = file_get_contents($plurl); 
			$end = str_replace("jsonpCallback(",'',$end);
			$end = str_replace("}]})",'}]}',$end);
			$endinfo = json_decode($end);
			$con = $endinfo->commentlist;
			$hang = 1;
			foreach($con as $key=>$val){ 
				$curdatestring = substr($val->RReplyDate,6,10); //时间 
				$allarr[$begin][$hang][] = date( "Y-m-d H:i:s",$curdatestring);  
				$allarr[$begin][$hang][] = $val->RMemberName ; //用户
		 		$allarr[$begin][$hang][] = $val->RContent ; //内容
				if(isset($val->Quote)){
					$odate = substr($val->Quote->RReplyDate,6,10); 
					$allarr[$begin][$hang][] = date( "Y-m-d H:i:s",$odate);  
					$allarr[$begin][$hang][] =$val->Quote->RMemberName ;
					$allarr[$begin][$hang][] =$val->Quote->RContent ; 
				} 
				$hang++;
			} 
		}  
		$name = '评论数据'; //导出excel文件名
		$objPHPExcel = new PHPExcel();  
		$objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getRowDimension(1)->setRowHeight(20);
		//设置标题
		$header = array('评论时间','用户','内容','原评论时间','原评论用户','原评论内容');
		$key = ord("A");
        foreach($header as $v){
            $colum = chr($key);
            $objPHPExcel->getActiveSheet()->getColumnDimension($colum)->setWidth(15);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum.'1', $v);
            $key += 1;
        }
		//设置数据
		 
		$ttarr = array("goods"=>"","bads"=>"");
		if(!empty($_POST['good'])){
			$thego = explode("-",$_POST['good']);
			foreach($thego as $tk=>$tv){
				$ttarr['goods'][$tv] = 0;
			}
		}
		if(!empty($_POST['bad'])){
			$thego = explode("-",$_POST['bad']);
			foreach($thego as $tk=>$tv){
				$ttarr['bads'][$tv] = 0;
			}
		}
		$column = 2;
		foreach ($allarr as $key => $cells) { //分页读取
		    foreach($cells as $celobj){ //行写入
			    $span = ord("A");
			    $mn = 1;
				foreach($celobj as $keyName=>$value) {// 列写入
					//echo $value."=="; 
	                $j = chr($span);
	                $objActSheet->getRowDimension($column)->setRowHeight(20);
	                //echo $j.$column."==";
	                $objActSheet->setCellValue($j.$column,$value);
	                if(($mn==3||$mn==6) && !empty($_POST['good']) && $value){
		                $goods = explode('-',$_POST['good']);
		                foreach($goods as $k=>$vb){
			                if(!empty($vb)){
				                $patt = '/(.*)'.$vb.'(.*)/';
								preg_match($patt,$value,$mats); 
								if(!empty($mats)){
									$ttarr['goods'][$vb] += 1; 
								}  
			                } 
		                }
	                }
	                if(($mn==3||$mn==6) && !empty($_POST['bad']) && $value){
		                $bads = explode('-',$_POST['bad']);
		                foreach($bads as $k=>$vb){
			                if(!empty($vb)){
				                $patt = '/(.*)'.$vb.'(.*)/';
								preg_match($patt,$value,$mats); 
								if(!empty($mats)){
									$ttarr['bads'][$vb] += 1; 
								}  
			                } 
		                }
	                }
	                $span++;
	                $mn++;
	            }
	           // echo "<br>";
	            $column++;
		    } 
		} 
		//满意 词频
		if($_POST['good']){ //行写入
			$begspan = ord("A");
			$bj = chr($begspan);
			$objActSheet->setCellValue($bj.$column,"good");
		    $span = ord("B");  
			foreach($ttarr['goods'] as $keyName=>$value) {// 列写入
				//echo $value."=="; 
                $j = chr($span);
                $objActSheet->getRowDimension($column)->setRowHeight(20);
                //echo $j.$column."==";
                $objActSheet->setCellValue($j.$column,$keyName);
                $span++;
            }
            $column++;  
            $span = ord("B"); 
			foreach($ttarr['goods'] as $keyName=>$value) {// 列写入 
                $j = chr($span);
                $objActSheet->getRowDimension($column)->setRowHeight(20); 
                $objActSheet->setCellValue($j.$column,$value);
                $span++;
            }
            $column++;
        }
        //不满意 词频
        if($_POST['bad']){ //行写入
			$begspan = ord("A");
			$bj = chr($begspan);
			$objActSheet->setCellValue($bj.$column,"bad");
		    $span = ord("B");  
			foreach($ttarr['bads'] as $keyName=>$value) {// 列写入 
                $j = chr($span);
                $objActSheet->getRowDimension($column)->setRowHeight(20); 
                $objActSheet->setCellValue($j.$column,$keyName);
                $span++;
            }
            $column++;  
            $span = ord("B"); 
			foreach($ttarr['bads'] as $keyName=>$value) {// 列写入 
                $j = chr($span);
                $objActSheet->getRowDimension($column)->setRowHeight(20); 
                $objActSheet->setCellValue($j.$column,$value);
                $span++;
            }
            $column++;
        }
 
 
	 
		$objPHPExcel->getActiveSheet()->setTitle('评论列表');
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$name = iconv("utf-8", "gb2312", $name.'.xls');
		ob_end_clean(); //消除缓冲区，避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$name.'"');
		header('Cache-Control: max-age=0'); 
		$objWriter->save('php://output');
		exit;
	}
}
 
 
?>