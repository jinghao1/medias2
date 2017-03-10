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
class Excel extends	Controller	
{				

	public function index(){
        $header = array('注册id','项目名称','姓名','性别','手机号','购买时间','经销商','邮箱','车系','创建时间');
        $proid = input('param.proid/d');
        if($proid){
	        $data = db("user_dealer")->where('project_id',$proid)->select();
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
            $objPHPExcel->getActiveSheet()->getColumnDimension($colum)->setWidth(15);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum.'1', $v);
            $key += 1;
        }
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
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            //信息对应
            $rows['sex'] = $rows['sex']==1?"男":"女";
            $rows['project_id'] = $newpj[$rows['project_id']]; //项目名称
            $rows['car_time'] = $newbuycartm[$rows['buy_car_time']]; //购车时间
            $rows['time'] = date("Y-m-d H:i:s",$rows['time']); //注册时间
            //根据分销商id，获取对应省市，分销商名称 
            $rows['dealer_name'] = $dealer->DealerSelectName($rows['dealer_name']);
            //根据车系id获取车系名称
            $rows['car_series_id'] = $carserise->CarSelectName($rows['car_series_id']);
            unset($rows['start']);
            unset($rows['buy_car_time']);
            //end
            foreach($rows as $keyName=>$value) {// 列写入
                $j = chr($span);
                $objActSheet->getRowDimension($column)->setRowHeight(20);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
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
    	return "closed";
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
        for($currentRow = 2; $currentRow <= $allRow; $currentRow++){
            for($currentColumn='A'; $currentColumn <= $allColumn; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
               // if(is_object($arr[$currentRow][$currentColumn]) ){     //富文本转换字符串  
                //instanceof PHPExcel_RichText 需要检测字段类型 目前为强制转换
			       $arr[$currentRow][$currentColumn] = $arr[$currentRow][$currentColumn]->__toString();   
            }
               
            $dm = $arr[$currentRow]['C']; //经销商代码
            $dealername = $arr[$currentRow]['D']; //经销商名称
            $dealcity = $arr[$currentRow]['F']; //经销商所在城市名称
            $dealprovince = $arr[$currentRow]['H']; //经销商所在省份名称
            $dealminname = $arr[$currentRow]['I']; //经销商简称 
            //检测经销商是否存在，存在继续下一个，不存在检测省份，城市
            if(!$dealername|| !$dealcity ||!$dealprovince){
	            continue;
            }
            $result =  $dealer->ExistDealer($dealername);
            if(!$result){ //如果不存在
	            //检测省份是否存在，不存在，创建，存在获取proid
	            $endpro =  $dealer->ExistDealer($dealprovince);
	            if( $endpro){ //省份存在 检测城市是否存在
		            $endcity =  $dealer->ExistDealer($dealcity);
		            if($endcity){ //城市存在，插入经销商信息
		            	$dataarr = array('dm'=>$dm); //存放传入信息 
		            	$dataarr['pid'] = $endcity[0]['dealer_id']; //父级id
		            	$dataarr['dlname'] = $dealername; //经销商名称
		            	$dataarr['dlmime'] = $dealminname; //经销商名字简称
			            $insertinfo = $dealer->InsertDealerInfo($dataarr);
			            var_dump( $insertinfo);
				           echo "<br>";
		            }else{ //城市不存在，先插入城市，获取城市id，再插入经销商
			           $dataarr = array('dm'=>'','dlmime'=>''); //存放传入信息  
			           $dataarr['dlname'] = $dealcity; //城市名称
			           $dataarr['pid'] = $endpro[0]['dealer_id']; //父级省份id
			           $insertinfo = $dealer->InsertDealerInfo($dataarr);
			           var_dump( $insertinfo);
				           echo "<br>";
			           if($insertinfo){ //城市插入成功，插入当前经销商信息
				           $datrr = array('dm'=>$dm,'pid'=>$insertinfo,'dlname'=>$dealername,'dlmime'=>$dealminname); 
				           $insertjxs = $dealer->InsertDealerInfo($datrr);
				           var_dump( $insertjxs);
				           echo "<br>";
			           }   
		            }
	            }else{ //省份不存在，创建省份，创建城市，创建经销商  == 未写 因为省份已提前录入
		            
	            }
            }else{//存在,继续
	            continue;
            }
            //var_dump($dm,$dealername,$dealcity,$dealprovince,$dealminname);
             
            //检测城市是否存在，不存在，创建，存在获取cityid

             
        } 
        return $this->fetch();
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
}
