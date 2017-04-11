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
use app\admin\model\DealerModel;
use app\port\model\LotcirModel;
use app\admin\model\CarModel;
use app\admin\model\ProjectModel;
use app\admin\model\BrandModel;
 
class Cjcir extends	Controller	{	
	  
  

	
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

	//调用接口信息,页面需要
	public function listdata(){
		$han = input('param.han');
		if($han=="list"){ 
			$dealer = new DealerModel();
			$car = new CarModel();
			$project = new ProjectModel();
			//$testphp = phpinfo();
			// $user = new ProjectModel($_POST);
			$thedealer_info = array(); // 定义空数组
			$thedealer_info[0]['sex'] = 1;   
			$thedealer_info[0]['project_id'] = 0; //定义变量  
			$thedealer_info[0]['buy_car_time'] = 0; //定义变量  
			 
			  //信息展示
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
			
			 	
			$all = array('data'=>$data,'buytime'=>$duringtime,'DealerData'=>$DealerData,'ProjectName'=>$ProjectName); 
			//原注册信息 
			exit(json_encode($all));
		}else{
			exit(json_encode(2));
		} 
		exit(json_encode(3));
	}
	
	//经销商信息点击操作 接口
	public function undercitybydealerid(){
		$han = input('param.han');
		$id = input('param.dealer_id');
		if($han=="dealist" && $id){
			$dealer = new DealerModel();
			$dealend = $dealer->childinfo($id);
			exit(json_encode($dealend));
		}else{
			exit(json_encode("参数错误"));
		}
	}

	//选择项目返回项目下对应的车系信息 接口
	public function proundercar(){ 
		 
		$han = input('param.han');
		$id = input('param.dealer_id');
		if($han=="prounder" && $id){
			$dealer = new ProjectModel(); //获取项目下的车系id=>name
			$dealend = $dealer->ProjectUnderCar($id);
			exit(json_encode($dealend));
		}else if($han == "carunser" && $id){//选择车系对应的子系
			$car = new CarModel();
			$thecar = $car->CarClass($id);
			exit(json_encode($thecar));
		}else{
			exit(json_encode("参数错误"));
		}
	}
	// 手机号验证唯一 接口
	public function Checkuniquephone(){
		
		if(input('param.han')!="ckphone" || empty(input('param.phone')) ){
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
	//信息检测	//无效信息，返回1,默认返回0
	public function Ckencstr($str){ 
	
		//首先解密，->匹配 son,qin,不存在，无效 ->拼接，检测session 是否存在，存在为无效信息
		//引用解密插件
		//Loader::import('aes\aes', EXTEND_PATH); 
		$key = "o0o0o0o0o7ujm*IK<9o.00ew00O0O";// 密钥
		$aes = new \aes\Aes($key);
		
		//$sess = Session::get(''); 获取所有session
	 	//return $sess ;
		$bfarr = $aes->decrypt($str);
		 
		$keybfarr = str_replace(".",'',$bfarr);
		$keybfarr = str_replace("-",'',$keybfarr);
		if($bfarr){
			//检测有无此缓存  
			$endarr = explode("-",$bfarr); 
			if($endarr){ //查询有无 'son' , 'qin'
				$son = in_array('son',$endarr);
				$qin = in_array('qin',$endarr);
				if(!$son || !$qin){  // 密钥错误
					return 1;
				} 
			}  	 
			
			$thes = Cache::get($keybfarr); 
		  	
			if(!empty($thes)){ //密钥错误
				return 1;
			}else{
				Cache::set($keybfarr,'3',5); 
			}   
		} 
		return 0;
	}
	//用户注册信息提交 接口
	public function Comreg(){ //东风标致
	 	$arr = input('param.');
	 	  
		$enc = $arr['key'];  //加密串 检测 
		//return json_encode($arr);
		$data = array();
		$data['phone'] = $arr['numberphone']; //手机号
		$data['name'] = $arr['username']; //用户名
		$data['dealer_name'] = $arr['dealer'];
		$data['car_series_id'] = $arr['model'];
		$data['sex'] = $arr['thesex'];
		$data['whreg'] = $arr['fromwh'];  //注册位置
		if(isset($arr['fself'])){
			$self = $arr['fself'];
		}else{
			$self = 0;
		}
		if($arr['han']!="dealreg" && $arr['han']!="qinreg"){
			//return 2;
			return json_encode(array("start"=>'1001','msg'=>'数据传入有误'));   //参数错误
		} 
		if($arr['han']=="qinreg"){
			$data['from'] = 2; //标记为下游注册
		}
		
		$dealer = new DealerModel(); 
		$encckend = $this->Ckencstr($enc);
		//return $encckend;  
		if($encckend==1){
			return json_encode(array("start"=>'1006','msg'=>'请刷新页面,请勿重复提交,密钥失效'));   //请刷新页面,请勿重复提交
		}
		$data['project_id'] = 32; //项目id
	 
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
			 //	return json_encode($data); 
			$res = $dealer->DealerAdd($data); 
			//return "skkdkf";
			if($res){
				$uchoose = new LotteryModel();
				$endcj = $uchoose->HavChance($res,$self);
				//return $endcj; 
				return json_encode($endcj); //抽奖返回信息
			}else{
				return  json_encode(array("start"=>'2007','msg'=>'注册失败，请重试'));
			}
		}
		 	
	}
	 
	//抽奖接口 cir
	function Cirlot(){
		$arr = input('param.');
		phpinfo();
		$arr['key'] = 7;
		if(!isset($arr['phone']) || empty($arr['phone']) || !isset($arr['key'])){
			return json_encode(array("start"=>'1001','msg'=>'数据传入有误'));   //请刷新页面,请勿重复提交
		}
	 	$phone = $arr['phone'];
	 	//检测手机号可抽奖次数
	 	$phact = new LotcirModel();
	 	$phoinfo = $phact->Phonum($phone);
		$enc = $arr['key'];  //加密串 检测 
		if($enc!=7){ //检测秘钥
			$dealer = new DealerModel(); 
			$encckend = $this->Ckencstr($enc);
			//return $encckend;  
			if($encckend==1){
				return json_encode(array("start"=>'1006','msg'=>'请刷新页面,请勿重复提交,密钥失效'));   //请刷新页面,请勿重复提交
			}
		} 
		//检测是否有抽奖次数
		if(isset($phoinfo[0]['cjnum']) && $phoinfo[0]['cjnum']>0){
			//减次数
			//抽奖 
			$endcj = $phact->HavChance($phone);
			return json_encode($endcj);
		}else{
			return json_encode(array("start"=>'2008','msg'=>'抽奖次数已用完'));   //请刷新页面,请勿重复提交
		}
		
		
	}
	

	//检测本项目 此手机号是否已注册 已注册返回1，为注册返回2，密钥错误返回3
	function CKphoneHave(){
		$arr = input('param.');
		if(!isset($arr) || empty($arr['numberphone'])){
			return json_encode(array("start"=>'1001','msg'=>'数据传入有误'));   //请刷新页面,请勿重复提交
		}else{
			if(!preg_match("/1[34578]{1}\d{9}$/",$arr['numberphone'])){
		      return json_encode(array("start"=>'1002',"msg"=>"手机号不合法"));
		    }
		}
		$phone = $arr['numberphone']; //手机号
		if(empty($arr['key'])){
			return json_encode(array("start"=>'1006','msg'=>'请刷新页面,请勿重复提交,密钥失效'));   //请刷新页面,请勿重复提交
		}else{
			$enc = $arr['key']; //密钥	 
			$encckend = $this->Ckencstr($enc); 
			if($encckend==1){
				return json_encode(array("start"=>'1006','msg'=>'请刷新页面,请勿重复提交,密钥失效'));   //请刷新页面,请勿重复提交
			}
		}
		//检测手机号是否存在
		$proid = 32; //东标项目
		$dealer = new DealerModel(); 
		$phend = $dealer->checkphoneunique($phone,$proid);
		if($phend==1){
			return  json_encode(array("start"=>'1003','msg'=>'该手机已经注册'));
			//$this->error('抱歉，此手机号已注册，请更换手机号，谢谢！'); 
		}else{
			return  json_encode(array("start"=>'2008','msg'=>'验证通过'));
		}
	}
 
}
?>