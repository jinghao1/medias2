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
use think\Session;
use think\Cache;
use think\Loader;
use app\port\model\DealerModel;
use app\port\model\LotteryModel;
use app\admin\model\CarModel;
 
 
class Changhe extends	Controller	{	
  
 

	//获取购买时间
	public function Ltbuycartime(){
		$car = new CarModel();
		//购车时间段
		$duringtime = $car->BuycarTime();
		exit(json_encode($duringtime));
	}

	//调用接口信息,页面需要
	public function listdata(){
	
		$proid = input('param.proid');
		if( $proid>0){ 
			$dealer = new DealerModel();
		  
			//查询经销商 pid=0
			$DealerData = $dealer->StDealerPid($proid,0); 
			 	
			$all = array('DealerData'=>$DealerData); 
			//原注册信息 
			exit(json_encode($all));
		}else{
			exit(json_encode(2));
		} 
		exit(json_encode(3));
	}

	//查询省份对应的城市，经销商
	public function procitydeal(){ 
		$proid = input('param.proid');
		$dealerid = input('param.dealer_id');
		if( $proid>0 && $dealerid>0){ 
			$dealer = new DealerModel(); 
			//查询经销商 pid=0
			$DealerData = $dealer->StDealerPid($proid,$dealerid);   
			//原注册信息 
			exit(json_encode($DealerData));
		}else{
			exit(json_encode(2));
		} 
		exit(json_encode(3));
	}
	
 
 
	// 手机号验证唯一 接口
	public function Checkuniquephone(){
		
		if(empty(input('param.phone')) ){
			return 5; //参数错误
		}
		$phonenumber = input('param.phone');  
		if(!preg_match("/^1[34578]{1}\d{9}$/",$phonenumber)){  
		    return 3;  //不是手机号格式
		} 

		$dealer = new DealerModel(); 
		$phinfo = input('param.');
		//return json_encode($phinfo);
		if(isset($phinfo['proid'])){ //有项目id时，在项目内检测
			//return 6;
			$phend = $dealer->checkphoneunique($phonenumber,$phinfo['proid']);
		}else{
			//return 7;
			$phend = $dealer->checkphoneunique($phonenumber);
		}
	 
		
		if($phend==1){
			return 2; 
			//$this->error('抱歉，此手机号已注册，请更换手机号，谢谢！'); 
		}else{
			return 1;
		} 
	}
 
	//用户注册信息提交 接口
	public function Comreg(){ //昌河
	 	$arr = input('param.');  
		$data = array(); 
		//$data['buy_car_time'] = $arr['buycartime']; //购车时间
		$data['phone'] = $arr['numberphone']; //手机号
		$data['name'] = $arr['username']; //用户名
		$data['dealer_name'] = $arr['dealer']; //经销商id串
		$data['car_series_id'] = $arr['model']; //车系id 66
		$data['sex'] = $arr['thesex'];  //性别
		$data['whreg'] = $arr['fromwh'];  //注册位置
		$data['project_id'] = $arr['pjid']; //项目id  36
		if(isset($arr['fself'])){
			$data['from'] = intval($arr['fself']) ;
		} 
		//return json_encode($data);
		$dealer = new DealerModel();  
		if(input('param.')){ //信息增加 
			//return json_encode(array("statu"=>1)) ;
			//检测信息
			if( !$data['phone'] ){
				//$this->error('请填写预约信息，谢谢！'); 
				return  json_encode(array("start"=>'1002','msg'=>'手机号不合法'));
			}else{ //检测手机号的唯一性
				//手机号格式验证  
				if(!preg_match("/^1[34578]{1}\d{9}$/",$data['phone'])){  
				    return  json_encode(array("start"=>'1002','msg'=>'手机号不合法'));
				} 
		
				$phend = $dealer->checkphoneunique($data['phone'],$data['project_id']);
				if($phend==1){
					return  json_encode(array("start"=>'1003','msg'=>'该手机已经注册'));
					//$this->error('抱歉，此手机号已注册，请更换手机号，谢谢！'); 
				}
			}  
			$data['time'] = time(); //注册时间 
			$res = $dealer->DealerAddNew($data);  
			if($res){
				return json_encode(array("start"=>'2003','msg'=>'注册成功，感谢您的参与'));
			}else{
				return json_encode(array("start"=>'2007','msg'=>'注册失败，请重试'));
			}
		}
		 	
	} 
 
 
 
}
?>