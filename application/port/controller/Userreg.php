<?php 
/**
 * 极客之家 高端PHP - 用户使用注册信息模块
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\port\controller;
use	think\Controller;
use	think\Request;
use	think\Db;

use app\admin\model\DealerModel;
use app\admin\model\CarModel;
use app\admin\model\ProjectModel;
use app\admin\model\BrandModel;
 
class Userreg extends	Controller	{	
	public function reg() 
	{	
		$dealer = new DealerModel();
		$car = new CarModel();
		$project = new ProjectModel();
		//$testphp = phpinfo();
		// $user = new ProjectModel($_POST);
		$thedealer_info = array(); // 定义空数组
		$thedealer_info[0]['sex'] = 1;   
		$thedealer_info[0]['project_id'] = 0; //定义变量  
		$thedealer_info[0]['buy_car_time'] = 0; //定义变量  
		if(input('param.')){ //信息增加
			$data = input('param.');
			$data['dealer_name'] = implode(",", $data['dealer_name']);
			$data['car_series_id'] = implode(",", $data['car_series_id']);
			//$data['car_time'] = strtotime($data['car_time']);
			$data['time'] = time();
			// p($data);
			$res = null;
			if(!empty($data['dealer_id'])){
				$resup = $dealer->DealerUPdate($data);
			}else{
				$res = $dealer->DealerAdd($data);
			} 
			if($res)
			{
				$this->success('添加成功'); 
			}
			else
			{
				$this->error('添加失败');
			}
		}
		else
		{ //信息展示
			//车型 车系
			$data = $car->GetSelectOne();
			//查询经销商 pid=0
			$DealerData = $dealer->SelectDealerPid();
			//项目
			$ProjectName = $project->ProjectSelectName();
			
			//购车时间段
			$duringtime = $car->BuycarTime();
			$this->assign('data',$data);
			$this->assign('buytime',$duringtime);
			$this->assign('DealerData',$DealerData);
			$this->assign('ProjectName',$ProjectName);
			
		}	
		//var_dump($thedealer_info[0]['project_id']);
		//$this->assign('phpinfo',$testphp);
		//原注册信息
		$this->assign('dealer_info',$thedealer_info);
				
		return $this->fetch();					
	} 
	//查询项目下对应的车系
	public function ProjectCar(){
		$car = new ProjectModel();
		$brand = input('param.project_id');
		$data = $car->ProjectUnderCar($brand);
		exit(json_encode($data));
	}
	/**
	 * 查询车系子级
	 */
	public function CarClass()
	{
		$car = new CarModel();
		$car_id = input('param.brand_id');
		$data = $car->CarClass($car_id);
		exit(json_encode($data));
	}

	
	/**
	 * 查询经销商子级
	 */
	public function GetDealer()
	{
		$car = new CarModel();
		$dealer_id = input('param.dealer_id');
		$data = $car->DealerClass($dealer_id);
		exit(json_encode($data));
	}
}
?>