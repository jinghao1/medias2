<?php
/**
 * 极客之家 高端PHP - Excel导出导入
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
use think\PHPExcel\PHPExcel;
use app\admin\model\ProjectModel; //获取项目信息
use app\admin\model\DealerModel; //获取经销商信息
use app\admin\model\CarModel; //获取车系信息
use app\admin\model\ChebhModel; //获取车百汇信息
class Excel extends	Controller	
{			

	//信息导入
	public function Putin(){
		return $this->fetch();
	}	

	public function index(){
       
        $proid = input('param.proid/d');
        $enewsid = input('param.enewsid/d');
        $begtime = input('param.begtime');
		$endtime = input('param.endtime');
		if($begtime){
			//$this->assign('begtime',$begtime);
			$begtime = strtotime($begtime);
			
		}else{ 
			//$this->assign('begtime',"");
			$begtime = "0";
		}
		if($endtime){
			//$this->assign('endtime',$endtime);
			$endtime = strtotime($endtime);
		}else{
			//$this->assign('endtime',"");
			$endtime = "9499788800"; //截止时间
		}
	 
        switch($proid){
	        case 32:
	       	 	$header = array('注册id','项目名称','姓名','性别','手机号','获奖信息','省份','市区','经销商','车系','创建时间'); 	
	        	break;
	        case 36:
	       	 	$header = array('注册id','项目名称','姓名','性别','手机号','购车时间','省份','市区','经销商','车系','创建时间');
	        	break;
	        case 44:
	       	 	$header = array('注册id','项目名称','姓名','性别','手机号','购车时间','省份','市区','经销商','车系','创建时间');
	        	break;
	        default:
	        	$header = array('注册id','项目名称','姓名','性别','手机号','省份','市区','经销商','车系','创建时间');
        }
       
        if($proid || $enewsid){
	        if($proid){
		        $wharr['d.project_id'] = $proid;
	        }
	        if($enewsid && $enewsid!=3){
		        $wharr['d.whreg'] = $enewsid;
	        }
	        //通过项目id 获取对应的注册用户表
	        $tablinfo =  Db::name("allpro")->where('proid',$proid)->select();
	        if(!empty($tablinfo[0]['reginfo'])){
		        $table = $tablinfo[0]['reginfo'];
	        }else{
		        $table = "user_dealer";
	        }
	        $data = db($table) 
                    ->alias("d")->field('d.dealer_id,d.project_id,d.name,d.sex,d.phone,d.car_time,d.dealer_name,d.car_series_id,d.time,d.buy_car_time,n.name as lotname')
                    ->join('zt_brand c','d.car_series_id=c.brand_id','LEFT')
                    ->join('zt_project p','d.project_id=p.id','LEFT')
                    ->join('zt_lotuser m','m.userid=d.dealer_id','LEFT')
                    ->join('zt_lottery n','m.lotid=n.id','LEFT')
                    ->where($wharr)
                    ->where('d.time>'.$begtime." AND d.time<".$endtime)
                    //->where('d.time','<',$endtime) 
                    ->order("dealer_id desc ")
                    ->select(); 
            
        }else{
	        $data = db("user_dealer")->order('project_id desc')->select();
        }
        
        $this->writer($header,$data);//导出 此导出表头最长为A-Z,如果需要更长，请自行更改
        // $list = $this->reader('./UploadFiles/excel/ceshi.xls');//导入
        // $list = $this->reader('./uploads/files/tests.xls');//导入
        // dump($list);exit;
    }
 


    /**
     * 导出数据列表
     * @param  [type]  $header [description]
     * @param  [type]  $data   [description]
     * @param  boolean $name   [description]
     * @param  integer $type   [description]
     * @return [type]          [description]
     */
    static function writer($header, $data,$name=false,$type = 0) {
        //导出
        $result = import("PHPExcel",EXTEND_PATH.'PHPExcel');
        if(!$name){$name=date("Y-m-d-H-i-s",time());}
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        //设置表头
        $key = ord("A");
        foreach($header as $v){
            $colum = chr($key);
            $key = $key + 1; 
            $objPHPExcel->getActiveSheet()->getColumnDimension($colum)->setWidth(15);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum.'1', $v);
            
        }
       // exit;
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getRowDimension(1)->setRowHeight(20);
        //获取所有项目id=>name 
        $project = new ProjectModel();
        $projinfo = $project->ProjectSelectName();
        $newpj = array(); // 存储项目id=>name
        if(!empty($projinfo)){
	        foreach($projinfo as $pjval){
		        $newpj[$pjval['id']] = $pjval['project_name'];
	        }
        }
        $dealer = new DealerModel();
        $carserise = new CarModel();
        //获取购买时间段
		$newbuycartm = array(0=>"暂无");
		$buycartm = $carserise->BuycarTime();
		if($buycartm){
			foreach($buycartm as $bctm){
				$newbuycartm[$bctm['id']] = $bctm['timename'];
			}
		} 
		//获取所有奖项
		if($pjval['id']==44){ 
			//福特购车时间段
			$ftbuycartm = $carserise->Ftbuycartime();
			if($ftbuycartm){
				foreach($ftbuycartm as $bctm){
					$newbuycartm[$bctm['id']] = $bctm['timename'];
				}
			} 
		}
        foreach($data as $key => $rows){ //行写入
            
            //信息对应
            $arrs['dealer_id'] = $rows['dealer_id']; //注册id
            $arrs['project_id'] = $newpj[$rows['project_id']]; //项目名称
            $arrs['name'] = $rows['name'];
            switch($rows['sex']){
	            case 1:
	            	$arrs['sex'] = "男";
	            	break;
	            case 2:
	            	$arrs['sex'] = "女";
	            	break;
	            default:
	            	$arrs['sex'] = "未选择";
	            	break;
            } 
            $arrs['phone'] = $rows['phone'];
            if(isset($rows['lotname']) && $rows['project_id']==32){
	            $arrs['lotname'] = $rows['lotname']; //获奖名称
            }else{
	           // $arrs['lotname'] = null;
            } 
            if( $rows['project_id'] == 36 || $rows['project_id'] ==44 ){
	            $arrs['car_time'] = $newbuycartm[$rows['buy_car_time']]; //购车时间
            }
            
            $arrs['dealer_name0'] = "";
            $arrs['dealer_name1'] = "";
            $arrs['dealer_name2'] = "";
              //根据分销商id，获取对应省市，分销商名称 
             $alldealer = $dealer->DealerSelectName($rows['dealer_name'],$rows['project_id']);
             if($alldealer){
	            $endd = explode("-",$alldealer); 
	            foreach( $endd as $key=>$ev){
		            if($key>=3){
			            break;
		            }
		            $arrs['dealer_name'.$key] = $ev; 
	            } 
             }
             
             //根据车系id获取车系名称
            $arrs['car_series_id'] = $carserise->CarSelectName($rows['car_series_id']);
            $arrs['time'] = date("Y-m-d H:i:s",$rows['time']); //创建时间
            $span = ord("A");
            //end
            foreach($arrs as $keyName=>$value) {// 列写入
                $j = chr($span);
                $span++;
                $objActSheet->getRowDimension($column)->setRowHeight(20);
                $objActSheet->setCellValue($j.$column, $value);
                
            }
            $column++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('chen.data');
        $objPHPExcel->setActiveSheetIndex(0);
        $fileName = iconv("utf-8", "gb2312", './Data/excel/'.date('Y-m-d_', time()).time().'.xls');
        $saveName = iconv("utf-8", "gb2312", $name.'.xls');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if ($type == 0) {
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=\"$saveName\"");
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        } else {
            $objWriter->save($fileName);
            return $fileName;
        }
    }


      //车百汇信息导出
    public function CbhregOut(){
	    $proinfo = input('param.');
        $enewsid = input('param.enewsid/d');
       
        $header = array('注册id','姓名','手机号','车系id','车系名称','车型品牌','创建时间','购车时间','入口城市','定位城市','选择城市','奖品领取','IP','PC/Mobile'); 
     
        if($enewsid && $enewsid!=3){
	        $wharr['d.whreg'] = $enewsid;
	        $data = db('cbh_reg') 
                ->alias("d")->field('d.dealer_id,d.name,d.phone,d.car_series_id,n.brand_name as serisename,m.brand_name as ppname,d.time,d.from,d.buy_car_time,d.localaddr,d.changeaddr,d.accept,d.curip,d.whreg') 
                ->join('zt_cbh_carserise n','d.car_series_id=n.brand_id','LEFT')
                ->join('zt_cbh_brand m','d.brand=m.brand_id','LEFT')
                ->where($wharr) 
                ->order("d.dealer_id desc ")
                ->select(); 
        }else{
	        $data = db('cbh_reg') 
                ->alias("d")->field('d.dealer_id,d.name,d.phone,d.car_series_id,n.brand_name as serisename,m.brand_name as ppname,d.time,d.from,d.buy_car_time,d.localaddr,d.changeaddr,d.accept,d.curip,d.whreg') 
                ->join('zt_cbh_carserise n','d.car_series_id=n.brand_id','LEFT') 
                ->join('zt_cbh_brand m','d.brand=m.brand_id','LEFT')
                ->order("d.dealer_id desc ")
                ->select(); 
        } 
        
        $this->Cbhwriter($header,$data);//导出 此导出表头最长为A-Z,如果需要更长，请自行更改
      
    }


    
    /**
     * 导出数据列表
     * @param  [type]  $header [description]
     * @param  [type]  $data   [description]
     * @param  boolean $name   [description]
     * @param  integer $type   [description]
     * @return [type]          [description]
     */
    static function Cbhwriter($header, $data,$name=false,$type = 0) {
        //导出
        $result = import("PHPExcel",EXTEND_PATH.'PHPExcel');
        if(!$name){$name=date("Y-m-d-H-i-s",time());}
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        //设置表头
        $key = ord("A");
        foreach($header as $v){
            $colum = chr($key);
            $key = $key + 1; 
            $objPHPExcel->getActiveSheet()->getColumnDimension($colum)->setWidth(15);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum.'1', $v);
            
        }
       // exit;
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getRowDimension(1)->setRowHeight(20);
        //获取购车时间
        $carserise = new CarModel();
        //获取购买时间段
		$newbuycartm = array(0=>"暂无");
		$buycartm = $carserise->BuycarTime();
		$cbhck = new ChebhModel();
		if($buycartm){
			foreach($buycartm as $bctm){
				$newbuycartm[$bctm['id']] = $bctm['timename'];
			}
		} 
        foreach($data as $key => $rows){ //行写入 
            //信息对应
            $arrs['dealer_id'] = $rows['dealer_id']; //注册id 
            $arrs['name'] = $rows['name']; //姓名 
            $arrs['phone'] = $rows['phone']; //手机号 
             //根据车系id获取车系名称
            $arrs['car_series_id'] = $rows['car_series_id']; //车系id
            $arrs['serisename'] = $rows['serisename']; //车系名称
            $arrs['ppname'] = $rows['ppname']; //车型品牌
            $arrs['time'] = date("Y-m-d H:i:s",$rows['time']); //创建时间
            $arrs['car_time'] = $newbuycartm[$rows['buy_car_time']]; //购车时间   
            $arrs['from'] = $rows['from'];
            if($rows['from']>0){
	            $theend = $cbhck->cynamebyid($rows['from']);
	            if(isset($theend[0])&& !empty($theend[0])){
		            $arrs['from'] = $theend[0]['cityname']; //由城市id获取城市名称
	            }
            }
            $arrs['localaddr'] = $rows['localaddr']; //定位城市
            $arrs['changeaddr'] = $rows['changeaddr']; //选择城市
            //奖品领取情况
            $arrs['havelot'] = $rows['accept']==1 ? "已领取" : "未领取";
            $arrs['ip'] = $rows['curip']; //注册ip 
            $arrs['whreg'] = $rows['whreg']; //pc/mobile标记
            $span = ord("A");
            //end
            foreach($arrs as $keyName=>$value) {// 列写入
                $j = chr($span);
                $span++;
                $objActSheet->getRowDimension($column)->setRowHeight(20);
                $objActSheet->setCellValue($j.$column, $value);
                
            }
            $column++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('chen.data');
        $objPHPExcel->setActiveSheetIndex(0);
        $fileName = iconv("utf-8", "gb2312", './Data/excel/'.date('Y-m-d_', time()).time().'.xls');
        $saveName = iconv("utf-8", "gb2312", $name.'.xls');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if ($type == 0) {
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=\"$saveName\"");
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        } else {
            $objWriter->save($fileName);
            return $fileName;
        }
    }

    /**
     * 导入数据
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function reader() { 
    //关闭
    	//return "closed";
	    $file = request()->file('importexcel');
	   
        // 移动到框架应用根目录/public/uploads/ 目录下
    	$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
    	if($info){
	        // 成功上传后 获取上传信息 
	        $extname = $info->getExtension(); //获取文件扩展名  
	        $urlname = $info->getSaveName(); //获取文件存储目录及名称
	        $urlname = ROOT_PATH . 'public' . DS . 'uploads/'.$urlname;
	        $filename = $info->getFilename();  //获取文件保存名称
	    }else{
	        // 上传失败获取错误信息
	        echo $file->getError();
	    } 
	   
        if ($extname == 'xls') {
            $result = import("Excel5",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif ($extname == 'xlsx') {
            $result = import("Excel2007",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            return '路径出错';
        }

        $PHPExcel     = $PHPReader->load($urlname);
        $currentSheet = $PHPExcel->getSheet(0);
        $allColumn    = $currentSheet->getHighestColumn();
        $allRow       = $currentSheet->getHighestRow();
        $dealer = new DealerModel();
        $allColumn ='L';
        for($currentRow = 2; $currentRow <= $allRow; $currentRow++){
            for($currentColumn='A'; $currentColumn <= $allColumn; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
               // if(is_object($arr[$currentRow][$currentColumn]) ){     //富文本转换字符串  
                //instanceof PHPExcel_RichText 需要检测字段类型 目前为强制转换
                //富文本转换
			       //$arr[$currentRow][$currentColumn] = $arr[$currentRow][$currentColumn]->__toString();   
            }
               
           // $dm = $arr[$currentRow]['F']; //经销商编号
            //$dealername = $arr[$currentRow]['D']; //经销商名称
            //$dealcity = $arr[$currentRow]['F']; //经销商所在城市名称
            //$dealprovince = $arr[$currentRow]['H']; //经销商所在省份名称
            //$addr = $arr[$currentRow]['E']; //经销商所在地址
            $dealminname = $arr[$currentRow]['E']; //经销商简称 
            $dealername = $arr[$currentRow]['D']; //经销商名称
            $dealprovince = $arr[$currentRow]['J']; //经销商所在省份名称
            $dealcity = $arr[$currentRow]['K']; //经销商所在城市名称
            //检测经销商是否存在，存在继续下一个，不存在检测省份，城市
            if(!$dealername|| !$dealcity ||!$dealprovince){
	            continue;
            }
            $result =  $dealer->ExistDealer($dealername);
            if(!$result){ //如果不存在
	            //检测省份是否存在，不存在，创建，存在获取proid
	            $endpro =  $dealer->ExistDealer($dealprovince);
	            if($endpro){ //省份存在 检测城市是否存在
		            $endcity =  $dealer->ExistDealer($dealcity);
		            if($endcity){ //城市存在，插入经销商信息
		            	//$dataarr = array('dm'=>$dm); //存放传入信息 
		            	$dataarr['pid'] = $endcity[0]['dealer_id']; //父级id
		            	$dataarr['dlname'] = $dealername; //经销商名称
		            	//$dataarr['dlmime'] = $dealminname; //经销商名字简称
			            $insertinfo = $dealer->InsertDealerInfodb($dataarr);
			            var_dump( $insertinfo);
				           echo "<br>";
		            }else{ //城市不存在，先插入城市，获取城市id，再插入经销商
			           //$dataarr = array('dm'=>'','dlmime'=>''); //存放传入信息  
			           $dataarr['dlname'] = $dealcity; //城市名称
			           $dataarr['pid'] = $endpro[0]['dealer_id']; //父级省份id
			           $insertinfo = $dealer->InsertDealerInfodb($dataarr);
			           var_dump( $insertinfo);
				           echo "<br>";
			           if($insertinfo){ //城市插入成功，插入当前经销商信息
				           $datrr = array('pid'=>$insertinfo,'dlname'=>$dealername); 
				           $insertjxs = $dealer->InsertDealerInfodb($datrr);
				           var_dump( $insertjxs);
				           echo "<br>";
			           }   
		            }
	            }else{ //省份不存在，创建省份，创建城市，创建经销商  == 未写 因为省份已提前录入
		            $dataarr['dlname'] = $dealprovince; //城市名称
			        $dataarr['pid'] = 0; //父级省份id
			        $insertinfo = $dealer->InsertDealerInfodb($dataarr);
			           var_dump( $insertinfo);
				           echo "<br>";
	            }
            }else{//存在,继续
	            continue;
            }
            //var_dump($dm,$dealername,$dealcity,$dealprovince,$dealminname);
             
            //检测城市是否存在，不存在，创建，存在获取cityid

             
        } 
        return $this->fetch();
    }


//宝沃BX5 经销商信息导入
	public function readerBW(){ 
    //关闭
    	//return "closed";
	    $file = request()->file('importexcel');
	   
        // 移动到框架应用根目录/public/uploads/ 目录下
    	$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
    	if($info){
	        // 成功上传后 获取上传信息 
	        $extname = $info->getExtension(); //获取文件扩展名  
	        $urlname = $info->getSaveName(); //获取文件存储目录及名称
	        $urlname = ROOT_PATH . 'public' . DS . 'uploads/'.$urlname;
	        $filename = $info->getFilename();  //获取文件保存名称
	    }else{
	        // 上传失败获取错误信息
	        echo $file->getError();
	    } 
	   
        if ($extname == 'xls') {
            $result = import("Excel5",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif ($extname == 'xlsx') {
            $result = import("Excel2007",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            return '路径出错';
        }

        $PHPExcel     = $PHPReader->load($urlname);
        $currentSheet = $PHPExcel->getSheet(0);
        $allColumn    = $currentSheet->getHighestColumn();
        $allRow       = $currentSheet->getHighestRow();
        $dealer = new DealerModel();
        $allColumn ='N';
        for($currentRow = 2; $currentRow <= $allRow; $currentRow++){
            for($currentColumn='A'; $currentColumn <= $allColumn; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
               // if(is_object($arr[$currentRow][$currentColumn]) ){     //富文本转换字符串  
                //instanceof PHPExcel_RichText 需要检测字段类型 目前为强制转换
                //富文本转换
			       //$arr[$currentRow][$currentColumn] = $arr[$currentRow][$currentColumn]->__toString();   
            } 
            $dealminname =  '';//经销商简称 
            $dealername = $arr[$currentRow]['C']; //经销商名称
            $dealprovince = $arr[$currentRow]['A']; //经销商所在省份名称
            $dealcity = $arr[$currentRow]['B']; //经销商所在城市名称
            //检测经销商是否存在，存在继续下一个，不存在检测省份，城市
            if(!$dealername|| !$dealcity ||!$dealprovince){
	            continue;
            }
            //var_dump($dealminname);
            //exit;
            $dataarr = array();
            $result =  $dealer->exDlMingjueZs($dealername,'ft_dealer');
            if(!$result){ //如果不存在
	            //检测省份是否存在，不存在，创建，存在获取proid
	            $endpro =  $dealer->exDlMingjueZs($dealprovince,'ft_dealer');
	            if($endpro){ //省份存在 检测城市是否存在
		            $endcity =  $dealer->exDlMingjueZs($dealcity,'ft_dealer');
		            if($endcity){ //城市存在，插入经销商信息
		            	//$dataarr = array('dm'=>$dm); //存放传入信息 
		            	$dataarr['pid'] = $endcity[0]['dealer_id']; //父级id
		            	$dataarr['dlname'] = $dealername; //经销商名称
		            	$dataarr['minname'] = $dealminname; //经销商名字简称
		             
			            $insertinfo = $dealer->ItMJZs($dataarr,'ft_dealer');
			            var_dump( $insertinfo);
				           echo "<br>";
		            }else{ //城市不存在，先插入城市，获取城市id，再插入经销商
			           //$dataarr = array('dm'=>'','dlmime'=>''); //存放传入信息  					
			            $dataarr['minname'] = ""; //经销商名字简称
		             
			           $dataarr['dlname'] = $dealcity; //城市名称
			           $dataarr['pid'] = $endpro[0]['dealer_id']; //父级省份id
			           $insertinfo = $dealer->ItMJZs($dataarr,'ft_dealer');
			           var_dump( $insertinfo);
				           echo "<br>";
			           if($insertinfo){ //城市插入成功，插入当前经销商信息
				           $datrr = array('pid'=>$insertinfo,'dlname'=>$dealername,'minname'=>$dealminname); 
				           $insertjxs = $dealer->ItMJZs($datrr,'ft_dealer');
				           var_dump( $insertjxs);
				           echo "<br>";
			           }   
		            }
	            }else{ //省份不存在，创建省份，创建城市，创建经销商  == 未写 因为省份已提前录入
		            $dataarr['dlname'] = $dealprovince; //城市名称
			        $dataarr['pid'] = 0; //父级省份id
			        $dataarr['minname'] = ""; //经销商名字简称
		         
			        $insertinfo = $dealer->ItMJZs($dataarr,'ft_dealer');
			        var_dump( $insertinfo);
				    echo "<br>";
	            }
            }else{//存在,继续
	            continue;
            }
            //var_dump($dm,$dealername,$dealcity,$dealprovince,$dealminname);
             
            //检测城市是否存在，不存在，创建，存在获取cityid

             
        } 
        return "success";
        return $this->fetch();
    }



    //汽车大全 车型信息导入
    public function allcar_cbh(){ 
    //关闭
        //return "closed";
        $file = request()->file('importexcel');
       
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息 
            $extname = $info->getExtension(); //获取文件扩展名  
            $urlname = $info->getSaveName(); //获取文件存储目录及名称
            $urlname = ROOT_PATH . 'public' . DS . 'uploads/'.$urlname;
            $filename = $info->getFilename();  //获取文件保存名称
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        } 
       
        if ($extname == 'xls') {
            $result = import("Excel5",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif ($extname == 'xlsx') {
            $result = import("Excel2007",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            return '路径出错';
        }

        $PHPExcel     = $PHPReader->load($urlname);
        $currentSheet = $PHPExcel->getSheet(0);
        $allColumn    = $currentSheet->getHighestColumn();
        $allRow       = $currentSheet->getHighestRow();
        //$allRow = 10;
        $dealer = new ChebhModel();
        $allColumn ='N';
        for($currentRow = 2; $currentRow <= $allRow; $currentRow++){
            for($currentColumn='A'; $currentColumn <= $allColumn; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
               // if(is_object($arr[$currentRow][$currentColumn]) ){     //富文本转换字符串  
                //instanceof PHPExcel_RichText 需要检测字段类型 目前为强制转换
                //富文本转换
                   //$arr[$currentRow][$currentColumn] = $arr[$currentRow][$currentColumn]->__toString();   
            }
             
            $ppid = $arr[$currentRow]['A']; //车型大品牌id 一级
            $ppname = $arr[$currentRow]['B']; //车型品牌名称
            $pid = $arr[$currentRow]['C']; //车型细品牌id 二级
            $pname =  $arr[$currentRow]['D']; //车型细品牌名称
            $did = $arr[$currentRow]['E']; //车型id 三级
            $dname =  $arr[$currentRow]['F']; //车型名称
            unset($arr);
            //检测经销商是否存在，存在继续下一个，不存在检测省份，城市 

            if(!$ppname|| !$pname ||!$dname){
                continue;
            } 
            $dataarr = array();
            //检测id，名称是否存在
            $ckend = $dealer->Ckextid($did,$dname); 
            if(!$ckend){ //如果不存在
                //检测一级是否存在，不存在，创建，存在获取proid
                $endpro =  $dealer->Ckextid($ppid,$ppname);
                if($endpro){ //一级存在 检测二级是否存在
                    $endcity =  $dealer->Ckextid($pid,$pname);
                    if($endcity){ //二级存在，插入车型信息
                        //$dataarr = array('dm'=>$dm); //存放传入信息 
                        $dataarr['brand_id'] = $did; //当前id
                        $dataarr['brand_name'] = $dname ; //车型名称
                        $dataarr['pid'] = $pid; //  二级id
                    
                        $insertinfo = $dealer->IntCarinfo($dataarr);
                        var_dump( $insertinfo);
                           echo "<br>";
                    }else{ //城市不存在，先插入城市，获取城市id，再插入经销商
                        //$dataarr = array('dm'=>$dm); //存放传入信息 
                        $dataarr['brand_id'] = $pid; //当前id
                        $dataarr['brand_name'] = $pname ; //车型名称
                        $dataarr['pid'] = $ppid; //  二级id
                    
                        $insertinfo = $dealer->IntCarinfo($dataarr);
                       var_dump( $insertinfo);
                           echo "<br>";
                       if($insertinfo){ //城市插入成功，插入当前经销商信息
                           $datrr = array('pid'=>$pid,'brand_id'=>$did,'brand_name'=>$dname); 
                           $insertjxs = $dealer->IntCarinfo($datrr);
                           unset($datrr);
                           var_dump( $insertjxs);
                           echo "<br>";
                       }   
                    }
                }else{ //一级不存在，创建 
                     //$dataarr = array('dm'=>$dm); //存放传入信息 
                    $dataarr['brand_id'] = $ppid; //当前id
                    $dataarr['brand_name'] = $ppname ; //车型名称
                    $dataarr['pid'] = 0; //  二级id
                
                    $insertinfo = $dealer->IntCarinfo($dataarr);
                    var_dump( $insertinfo);
                    echo "<br>";
                }
            }else{//存在,继续
                continue;
            }
            unset($dataarr);
            //var_dump($dm,$dealername,$dealcity,$dealprovince,$dealminname);
             
            //检测城市是否存在，不存在，创建，存在获取cityid

             
        } 
        return "success";
        return $this->fetch();
    }


     //汽车大全 车型信息导入
    public function allcar_cbh_detail(){ 
    //关闭
        //return "closed";
        $file = request()->file('importexcel');
       
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息 
            $extname = $info->getExtension(); //获取文件扩展名  
            $urlname = $info->getSaveName(); //获取文件存储目录及名称
            $urlname = ROOT_PATH . 'public' . DS . 'uploads/'.$urlname;
            $filename = $info->getFilename();  //获取文件保存名称
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        } 
       
        if ($extname == 'xls') {
            $result = import("Excel5",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif ($extname == 'xlsx') {
            $result = import("Excel2007",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            return '路径出错';
        }

        $PHPExcel     = $PHPReader->load($urlname);
        $currentSheet = $PHPExcel->getSheet(0);
        $allColumn    = $currentSheet->getHighestColumn();
        $allRow       = $currentSheet->getHighestRow();
        //$allRow = 10;
        $dealer = new ChebhModel();
        $allColumn ='N';
        for($currentRow = 2; $currentRow <= $allRow; $currentRow++){
            for($currentColumn='A'; $currentColumn <= $allColumn; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
               // if(is_object($arr[$currentRow][$currentColumn]) ){     //富文本转换字符串  
                //instanceof PHPExcel_RichText 需要检测字段类型 目前为强制转换
                //富文本转换
                   //$arr[$currentRow][$currentColumn] = $arr[$currentRow][$currentColumn]->__toString();   
            }
            $onearr = array();
            $twoarr = array();
            $threearr = array();
            $fourarr = array();
            $onearr['brand_id'] = $arr[$currentRow]['A']; //车型大品牌id 一级
            $onearr['brand_name'] = $arr[$currentRow]['B']; //车型品牌名称
            $onearr['pid'] = 0;
            $twoarr['brand_id'] = $arr[$currentRow]['C']; //车型细品牌id 二级
            $twoarr['brand_name'] =  $arr[$currentRow]['D']; //车型细品牌名称
            $twoarr['pid'] = $onearr['brand_id'];
            $threearr['brand_id'] = $arr[$currentRow]['E']; //车型id 三级
            $threearr['brand_name'] =  $arr[$currentRow]['F']; //车型名称
            $threearr['pid'] = $twoarr['brand_id'];
            $fourarr['brand_id'] = $arr[$currentRow]['G']; //车型id 四级
            $fourarr['brand_name'] =  $arr[$currentRow]['H']; //车型名称
            $fourarr['pid'] = $threearr['brand_id'];
            unset($arr);
            //检测经销商是否存在，存在继续下一个，不存在检测省份，城市 

            //检测id，名称是否存在
            $onek = $dealer->Chbint($onearr,'cbh_brand');
            echo $onek."<br>";
            $two = $dealer->Chbint($twoarr,'cbh_masterbrand');
            echo $two."<br>";
            $thre = $dealer->Chbint($threearr,'cbh_carserise');
            echo $thre."<br>";
            $fourk = $dealer->Chbint($fourarr,'cbh_carbasic');
            echo $fourk."<br>";
            //不存在，插入
            
          
            //var_dump($dm,$dealername,$dealcity,$dealprovince,$dealminname);
             
            //检测城市是否存在，不存在，创建，存在获取cityid

             
        } 
        return "success";
        return $this->fetch();
    }

	//行圆全部城市列表信息导入
	public function xyallcity(){
		$file = request()->file('importexcel');
	   
        // 移动到框架应用根目录/public/uploads/ 目录下
    	$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
    	if($info){
	        // 成功上传后 获取上传信息 
	        $extname = $info->getExtension(); //获取文件扩展名  
	        $urlname = $info->getSaveName(); //获取文件存储目录及名称
	        $urlname = ROOT_PATH . 'public' . DS . 'uploads/'.$urlname;
	        $filename = $info->getFilename();  //获取文件保存名称
	    }else{
	        // 上传失败获取错误信息
	        echo $file->getError();
	    } 
	   
        if ($extname == 'xls') {
            $result = import("Excel5",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif ($extname == 'xlsx') {
            $result = import("Excel2007",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            return '路径出错';
        }

        $PHPExcel     = $PHPReader->load($urlname);
        $currentSheet = $PHPExcel->getSheet(0);
        $allColumn    = $currentSheet->getHighestColumn();
        $allRow       = $currentSheet->getHighestRow();
        $dealer = new DealerModel();
        $allColumn ='H';
        for($currentRow = 3; $currentRow <= $allRow; $currentRow++){
            for($currentColumn='A'; $currentColumn <= $allColumn; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
              
            }
           	$newcity = array();
            $newcity['cityid'] = $arr[$currentRow]['A']; //城市id
            $newcity['cityname'] = $arr[$currentRow]['B']; //城市或省份名称
            $newcity['level'] = $arr[$currentRow]['C']; //级别，层级
            $newcity['parentid'] = $arr[$currentRow]['D']; //父级id
            $newcity['initial'] = $arr[$currentRow]['E']; //字母标识
            //检测经销商是否存在，存在继续下一个，不存在检测省份，城市
            if( !$newcity['cityid'] ||!$newcity['cityname']){
	            continue;
            }
        	$endit = $dealer->ItAllcity($newcity);
            var_dump($endit);
            echo "<br>";
            
        } 
        
	}

	//行圆全部城市列表信息导入
 

	//经销商信息导入
	public function readerRW() { 
    //关闭
    	//return "closed";
	    $file = request()->file('importexcel');
	   
        // 移动到框架应用根目录/public/uploads/ 目录下
    	$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
    	if($info){
	        // 成功上传后 获取上传信息 
	        $extname = $info->getExtension(); //获取文件扩展名  
	        $urlname = $info->getSaveName(); //获取文件存储目录及名称
	        $urlname = ROOT_PATH . 'public' . DS . 'uploads/'.$urlname;
	        $filename = $info->getFilename();  //获取文件保存名称
	    }else{
	        // 上传失败获取错误信息
	        echo $file->getError();
	    } 
	   
        if ($extname == 'xls') {
            $result = import("Excel5",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif ($extname == 'xlsx') {
            $result = import("Excel2007",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            return '路径出错';
        }

        $PHPExcel     = $PHPReader->load($urlname);
        $currentSheet = $PHPExcel->getSheet(0);
        $allColumn    = $currentSheet->getHighestColumn();
        $allRow       = $currentSheet->getHighestRow();
        $dealer = new DealerModel();
        $allColumn ='N';
        for($currentRow = 2; $currentRow <= $allRow; $currentRow++){
            for($currentColumn='A'; $currentColumn <= $allColumn; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
               // if(is_object($arr[$currentRow][$currentColumn]) ){     //富文本转换字符串  
                //instanceof PHPExcel_RichText 需要检测字段类型 目前为强制转换
                //富文本转换
			       //$arr[$currentRow][$currentColumn] = $arr[$currentRow][$currentColumn]->__toString();   
            }
          
            $dealprovince = $arr[$currentRow]['J']; //经销商所在省份名称
            $dealcity = $arr[$currentRow]['K']; //经销商所在城市名称
            //检测经销商是否存在，存在继续下一个，不存在检测省份，城市
            if( !$dealcity ||!$dealprovince){
	            continue;
            }
        
            $dataarr = array();
            //检测城市是否存在
            $result =  $dealer->exDealerRw($dealcity);
            if(!$result){ //如果不存在
	            //检测省份是否存在，不存在，创建，存在获取proid
	            $endpro =  $dealer->exDealerRw($dealprovince);
	            if($endpro){ //省份存在 检测城市是否存在
		            $endcity =  $dealer->exDealerRw($dealcity);
		            if(!$endcity){ //城市不存在，先插入城市，
			           $dataarr['dlname'] = $dealcity; //城市名称
			           $dataarr['pid'] = $endpro[0]['dealer_id']; //父级省份id
			           $insertinfo = $dealer->ItDealerInfoRW($dataarr);
			           var_dump( $insertinfo);
				           echo "<br>"; 
		            }
	            }else{ //省份不存在，创建省份，创建城市，创建经销商  == 未写 因为省份已提前录入
		            $dataarr['dlname'] = $dealprovince; //城市名称
			        $dataarr['pid'] = 0; //父级省份id
			     
			        $insertinfo = $dealer->ItDealerInfoRW($dataarr);
			        var_dump( $insertinfo);
				    echo "<br>";
	            }
            }else{//存在,继续
	            continue;
            }
            
        } 
        return "success";
       // return $this->fetch();
    }

    
    private static function _getExt($file) {
        return pathinfo($file, PATHINFO_EXTENSION);
    }


    public function out(){
        $file_name   = "成绩单-".date("Y-m-d H:i:s",time());
        $file_suffix = "xlsx";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file_name.$file_suffix");

        //根据业务，自己进行模板赋值。
        return $this->fetch();
    }

    public function in(){
        $content = file_get_contents('./UploadFiles/excel/ceshi.xls');
        dump($content);exit;

    } 


    //东标竞猜 价格信息导出
    public function Dbpriceoutput(){
	    $proinfo = input('param.');
        $enewsid = input('param.enewsid/d');
       
        $header = array('id','用户姓名','手机号','竞猜价格','竞猜时间'); 
     
        $data =  Db::name('db_308_jc')->alias('a')->join('zt_db_308 b','a.userid=b.dealer_id','LEFT')->field('a.id,a.price,a.time,b.name,b.phone')->where('type',0)->order("a.price")->select();
     
        $this->Dbjcwriter($header,$data);//导出 此导出表头最长为A-Z,如果需要更长，请自行更改
      
    }


    
    /**
     * 导出数据列表
     * @param  [type]  $header [description]
     * @param  [type]  $data   [description]
     * @param  boolean $name   [description]
     * @param  integer $type   [description]
     * @return [type]          [description]
     */
    static function Dbjcwriter($header, $data,$name=false,$type = 0) {
        //导出
        $result = import("PHPExcel",EXTEND_PATH.'PHPExcel');
        if(!$name){$name=date("Y-m-d-H-i-s",time());}
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        //设置表头
        $key = ord("A");
        foreach($header as $v){
            $colum = chr($key);
            $key = $key + 1; 
            $objPHPExcel->getActiveSheet()->getColumnDimension($colum)->setWidth(15);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum.'1', $v);
            
        }
       // exit;
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getRowDimension(1)->setRowHeight(20);
        //获取购车时间
        $carserise = new CarModel();
        //获取购买时间段
		$newbuycartm = array(0=>"暂无");
		$buycartm = $carserise->BuycarTime();
		$cbhck = new ChebhModel();
		 
        foreach($data as $key => $rows){ //行写入 
            //信息对应 a.id,a.price,a.time,b.name,b.phone
            $arrs['id'] = $rows['id']; //注册id 
            $arrs['name'] = $rows['name']; //注册id 
            $arrs['phone'] = $rows['phone']; //姓名 
            $arrs['price'] = $rows['price']; //手机号  
            $arrs['time'] = date("Y-m-d H:i:s",$rows['time']); //创建时间 
            $span = ord("A");
            //end
            foreach($arrs as $keyName=>$value) {// 列写入
                $j = chr($span);
                $span++;
                $objActSheet->getRowDimension($column)->setRowHeight(20);
                $objActSheet->setCellValue($j.$column, $value);
                
            }
            $column++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('chen.data');
        $objPHPExcel->setActiveSheetIndex(0);
        $fileName = iconv("utf-8", "gb2312", './Data/excel/'.date('Y-m-d_', time()).time().'.xls');
        $saveName = iconv("utf-8", "gb2312", $name.'.xls');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if ($type == 0) {
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=\"$saveName\"");
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        } else {
            $objWriter->save($fileName);
            return $fileName;
        }
    }


    
}
