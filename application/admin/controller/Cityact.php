<?php  
namespace app\admin\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
use think\Session;
use app\admin\model\CityaModel;
use app\port\model\HkdataModel; 
//use think\auth\Auth;
class Cityact extends Base	
{
	//展示所有的城市列表信息
	public function citylist()				
	{		
		$city = new CityaModel();
		$ck = new HkdataModel();
		//获取一级省份信息
		$proinfo = $city->Alllist();
	    $etnv = ""; 
	    $zmarr = array(); // 存储省份字母
	    $newprocity = array(); //存储省份下对应城市
		foreach($proinfo as $key=>$val){
			//根据cityid判断是否有城市有活动
			$uncity = $city->UnderCity($val['cityid']);  
		 	$newprocity[$val['cityid']] = $uncity;
		  
			if(empty($uncity)){
				unset($proinfo[$key]);
				continue;
			} 
		 
			$zmarr[] = $val['initial']; //省份 
		}
		$zmarr = array_unique($zmarr);
		
		$this->assign('zmlist',$zmarr); //省份字母列表
		$this->assign('procity',$newprocity); //城市信息
		$this->assign('proii',$proinfo); //省份信息
		 	 				
		return $this->fetch();
	} 
}	
?>