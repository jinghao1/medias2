<?php
/**
 * 极客之家 高端PHP - 经销商模块
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
use app\admin\model\DealerModel;
use app\admin\model\CarModel;
use app\admin\model\ProjectModel;
use app\admin\model\BrandModel;
use app\admin\model\UserModel;
class Dealer extends	Base	
{	
	/**
	 * 经销商添加
	 */
	public	function add()				
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
		if(input('param.edit') && input('param.dealer_id')){ //信息编辑
		    //获取原有信息
		    $thedealer_id = input('param.dealer_id'); 
		    $proid = input('param.proid'); 
		    $thedealer_info = $dealer->DealerIdList($proid,$thedealer_id); //获取当前用户注册信息 
		   // $thedealer_info[0]['car_time'] = date("Y-m-d",$thedealer_info[0]['car_time']); //购车时间转换 
		    //获取车系信息
		    $carselect = $car->CarSelectnmobj($thedealer_info[0]['car_series_id']);
		    $carstr = "";
		    if($carselect){ 
			    foreach($carselect as $alv){
				    $carstr .= "<select class='GetCar' name='car_series_id[]'>";
				    $carstr .="<option value=".$alv['brand_id'].">".$alv['brand_name']."</option>";
				    $carstr .= "</select>";
			    }
		    }
		    $thedealer_info[0]['car_series_id'] = $carstr;
		    
		    //获取经销商信息
		    $dealselect = $dealer->DealerSelarr($proid,$thedealer_info[0]['dealer_name']);
		    $dealstr = "";
		    if($dealselect){
			    foreach($dealselect as $dlv){
				    $dealstr .= "<select class='GetDealer' name='dealer_name[]'>";
				    $dealstr .="<option value=".$dlv['dealer_id'].">".$dlv['dealer_name']."</option>";
				    $dealstr .= "</select>";
			    }
		    }
		    $thedealer_info[0]['dealer_name'] = $dealstr;
		    //end
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
			 
		}else if(input('param.')){ //信息增加
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
				$this->success('添加成功','add');
			}else if($resup){
				$this->success('更改成功','add');
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
		$proid = input('param.proid');
		$data = $car->DealerClass($dealer_id,$proid);
		exit(json_encode($data));
	}

	/**
	 * 经销商列表
	 * @return [type] [description]
	 */
	public function show()
	{

		$dealer = new DealerModel();
		$car = new CarModel();
		$project = new ProjectModel(); 
        
		$paramstr = input('param.id/d');
		$id = empty($paramstr) ? "" : $paramstr;
		$you = input('param.enews');
		$fromid = empty($you) ? 1 : $you;
		 
		//查询车系车型
		if(!empty($id)){
			//获取是显示全部还是，中游，下游
			switch ($fromid){
				case 1:
			 		$this->assign('yxenews',1);
				 	break;  
			 	case 2:
			 		$this->assign('llenews',1);
				 	break;  
				case 3:
			 		$this->assign('allout',1);
				 	break;  
				default:
				 	$this->assign('yxenews',1);
				 	break; 
			}
		  
			$data = $dealer->ProjectDealerAll($id,$fromid); 
			$proinfo = $project->ProjectSelectName($id); //获取当前项目名称
			$this->assign('proname',$proinfo[0]['project_name']);
			$this->assign('proid',$id);
			 
		}else{
			
			$data = $dealer->SelectAll();
		} 
		 
		$this->assign('enewsid',$fromid);
		//获取购买时间段
		$newbuycartm = array(0=>"暂无");
		$buycartm = $car->BuycarTime();
		if($buycartm){
			foreach($buycartm as $bctm){
				$newbuycartm[$bctm['id']] = $bctm['timename'];
			}
		} 
		$page = $data->render();
		$data = $data->all(); //解除对象保护 
		
		foreach ($data as $key => $val) {
			//查询车系、车型
			if(!$val['car_series_id']){
				continue;
			} 
			$DataArrName = $car->CarSelectName($val['car_series_id']);
			//查询经销商
			if($val['dealer_name']){
				$DataDealerName = $dealer->DealerSelectName($val['dealer_name'],$val['project_id']);
				$data[$key]['dealer_name'] = $DataDealerName; 
			}
			
			$data[$key]['car_series_id'] = $DataArrName;
			$data[$key]['buy_car_time'] = $newbuycartm[$data[$key]['buy_car_time']];
		} 
		$user = new UserModel();
		//检测删除操作
		//$auth = new \com\Auth();
		//检测注册信息编辑权限，  
		//$editopt = $auth->check('admin/dealer/add',session('admin_uid'));
		//检测注册信息删除权限
		//$delpro = $auth->check('admin/dealer/PjregDel',session('admin_uid'));

	 
		//检测注册信息编辑权限，
		$editopt = $user->CkOptionuser('admin/dealer/add');
		//echo Db::getlastsql();
		$this->assign('editopt',$editopt); //是否有删除权限，1有，2没有 true ,false
		//检测注册信息删除权限
		$delpro = $user->CkOptionuser('admin/dealer/PjregDel');
		//echo Db::getlastsql();
		$this->assign('delopt',$delpro); //是否有删除权限，1有，2没有
		$this->assign('data',$data);	 
		$this->assign('page',$page); 					
		return $this->fetch();
	}

	/**
	 * 根据项目id查询经销商列表
	 */
	public function DealerIdList()
	{
		$dealer = new DealerModel();
		$id = input('param.id/d');
		$data = $dealer->DealerIdList($id);
		//p($data);
	}

	//删除用户注册信息
	public function PjregDel(){
		$dealer = new DealerModel();
		$id = input('param.dealer_id/d');
		$pjid = input('param.pjid/d'); //项目id
	 
	    if($id && $pjid){
			$res = $dealer->ProUserDel($id,$pjid);
			if($res){
				$this->success('删除成功');
			}
			else
			{
				$this->error('删除失败');
			}
		}else{
			$this->error('无删除id');
		}
	}

	//查询当前项目 获奖信息
	function showlot(){
		$proid = input('param.id/d');
		if($proid==32){ //查询东标项目奖项情况
			$dealer = new DealerModel();
			$end = $dealer->LotteryAll();
			$this->assign('data',$end); 	 
		}else{
			$this->assign('data',array());
		}
		return $this->fetch();
	}
	//更新奖项信息
	function editlot(){
		$news = input('param.');
		var_dump($news);
		echo "<br>";
		echo "songkk";
		return;
	}

}
