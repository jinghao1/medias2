<?php 
//抽奖页面
namespace app\port\controller;
use	think\Controller;
use	think\Request;
use	think\Db;

 
use app\port\model\LotteryModel;
use app\admin\model\DealerModel;
use app\admin\model\CarModel;
use app\admin\model\ProjectModel;
use app\admin\model\BrandModel;
 
class Lottery extends Controller{	
	public function reglot() 
	{	
	  //session_start();
		//注册信息检测
		if(input('param.')){ //信息增加
			$dealer = new DealerModel();
			$car = new CarModel();
			$project = new ProjectModel();
			$data = input('param.');
			//检测信息
			if(!$data['dealer_name']|| !$data['car_series_id'] || !$data['phone'] ){
				$this->error('请填写预约信息，谢谢！'); 
			}else{ //检测手机号的唯一性
				$phend = $dealer->checkphoneunique($data['phone']);
				if($phend==1){
					$this->error('抱歉，此手机号已注册，请更换手机号，谢谢！'); 
				}
			}
			//end
			$data['dealer_name'] = implode(",", $data['dealer_name']);
			$data['car_series_id'] = implode(",", $data['car_series_id']);
			//$data['car_time'] = strtotime($data['car_time']);
			$data['time'] = time();
			// p($data); 
			$res = $dealer->DealerAdd($data);
			 
			if($res)
			{
				//调用抽奖  传入userid
				$uchoose = new LotteryModel();
				$end = $uchoose->HavChance($res[0]['userid']);
				$this->success('添加成功'); 
			}
			else
			{ 
				//返回用户注册失败信息 
				$this->error('添加失败');
			}
		}
		$uchoose = new LotteryModel();
		$end = $uchoose->HavChance(12,'17703186690');
		var_dump($end);
		exit;		
		return $this->fetch();
		//调取抽奖信息 并返回结果
		
	}
}

 
?>