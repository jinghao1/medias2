<?php
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
		//var_dump($test);
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
		$content =$element->find('div[class=text-con height-list]');
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
	foreach($html->find('div[class=position-r]') as $dlk=>$dlm){
		$tt = $dlm->outertext;
		//$ttstr = $tt->outertext;
		$newtt = getkeyv($tt); //key
		$ttspan = ""; //val
		foreach($dlm->find('span[class=font-arial c333]') as $dlk=>$dlv){
			$ttspan = $dlv->outertext; 
		}
		$end = array_key_exists($newtt,$comarr);
		//var_dump($newtt);
		if($end){
			$comarr[$newtt] += $ttspan;
			$comnum[$newtt] += 1;
		}else{
			$comarr[$newtt] = $ttspan;
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
	for($beg;$num>0;$num--){
		 
		$thepage = $beg+1;
	 	$beg = $beg+1;
		$url = "http://k.autohome.com.cn/".$numurl."/index_".$thepage.".html";
		$end = curcontent($url);
		$arr[$thepage] = $end['info'];
		$pfarr[$num] = array('cominfo'=>$end['cominfo'],'comnum'=>$end['comnum']); 
	}
	return array("info"=>$arr,'title'=>$end['title'],'cominfo'=>$end['cominfo'],'comnum'=>$end['comnum']); 
}
 
if(isset($_POST['url'])){
	if(!empty($_POST['url'])){
		$theurl = explode("/",$_POST['url']);
		 
		$num = 1;
		$begin = 1;
		if(isset($_POST['beg'])&&isset($_POST['end'])){
			
			if($_POST['beg']>0){
				$begin = $_POST['beg'];
			}
			if($_POST['end']>$_POST['beg']){
				$num = $_POST['end'] - $_POST['beg']+1;
			}
		}
		$endarr = cainfo($theurl[3],$begin,$num);
		// exit;
		$allarr = $endarr['info']; //抓取页面内容
		$pagetitle = $endarr['title']; //页面标题车型
		//print_r($allarr); 
		//导出
		date_default_timezone_set('Europe/London');
	 
		$name = $pagetitle; //导出excel文件名
		$objPHPExcel = new PHPExcel();  
		$objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getRowDimension(1)->setRowHeight(20);
		//设置标题
		$header = array('内容链接','发布日期','标题','最满意','最不满意');
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
	                if($mn==4 && !empty($_POST['good']) && $value){
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
	                }else if($mn==5 && !empty($_POST['bad']) && $value){
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
	 
		$objPHPExcel->getActiveSheet()->setTitle('口碑列表');
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