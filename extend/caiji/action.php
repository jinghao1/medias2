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
	$end = explode("<br/>",$str);
	$new = array('最满意的一点'=>"",'最不满意的一点'=>"");
	if(isset($end[1])){
		$new['最满意的一点'] =	$end[1];
	}
	if(isset($end[3])){
		$new['最不满意的一点'] = $end[3];
	}
	return $new;   
} 
//end

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
	return array('title'=>$title,'info'=>$newarr);
 
}

//分页规划
function cainfo($numurl='692',$beg,$num){
	$beg = $beg-1;
	$arr = array();
	$end = array('title'=>"无");
	for($beg;$num>0;$num--){
		 
		$thepage = $beg+1;
	 	$beg = $beg+1;
		$url = "http://k.autohome.com.cn/".$numurl."/index_".$thepage.".html";
		$end = curcontent($url);
		$arr[$thepage] = $end['info'];
		
	}
	return array("info"=>$arr,'title'=>$end['title']); 
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
		 
		
		 $column = 2;
		foreach ($allarr as $key => $cells) { //分页读取
		    foreach($cells as $celobj){ //行写入
			    $span = ord("A");
				foreach($celobj as $keyName=>$value) {// 列写入
					//echo $value."==";
					
	                $j = chr($span);
	                $objActSheet->getRowDimension($column)->setRowHeight(20);
	                //echo $j.$column."==";
	                $objActSheet->setCellValue($j.$column,$value);
	                $span++;
	            }
	           // echo "<br>";
	            $column++;
		    } 
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