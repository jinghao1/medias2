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
use app\port\model\HkdataModel;
use app\admin\model\CarModel;
 
 
class Dbjingcai extends	Controller	{	

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
	public function Comreg(){ //东标竞猜
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
	//发送短信验证码
	private function contpostcurl($url,$datapost=""){
		$curl = curl_init();
	     //设置抓取的url
	     curl_setopt($curl, CURLOPT_URL, $url);
	     //设置头文件的信息作为数据流输出
	     //curl_setopt($curl, CURLOPT_HEADER, 1);
	     //设置获取的信息以文件流的形式返回，而不是直接输出。
	     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	     //设置post方式提交
	     curl_setopt($curl, CURLOPT_POST, 1);
	     //设置post数据 
	     curl_setopt($curl, CURLOPT_POSTFIELDS, $datapost);
	     //执行命令
	     $data = curl_exec($curl);
	     //关闭URL请求
	     curl_close($curl);
	     //显示获得的数据
	    return $data;
	} 
	//创建请求
	private function contcurl($url){
		$ch = curl_init();  
		curl_setopt ($ch, CURLOPT_URL, $url);  
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10); 
		$dxycontent = curl_exec($ch);
		curl_close($ch); 
		return $dxycontent;
	}
	//点击获取验证码 == 东标竞猜
	public function Getyzmdbjc(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"];  
		$appidnum = 2; //汽车大全id
		$curtime = time(); //获取当前时间戳
		$phone1 = $all['iphone']; //收取短信手机号 
		if(!preg_match("/^1[34578]{1}\d{9}$/",$phone1)){  
		    $endyzjm = array("start"=>'1002','msg'=>'手机号格式不正确');
			echo $callback.'('.json_encode($endyzjm).')';
			return;
		}
		//检测此手机号是否已注册
		$dealer = new HkdataModel();
		$userinfo = $dealer->Rtuidbyphone($phone1,'43');
	 
		if(empty($userinfo)){ //已注册，有注册数据 
			$endyzjm = array("start"=>'1004','msg'=>'该手机未注册,请先提交注册信息');
			echo $callback.'('.json_encode($endyzjm).')';
			return; 
		}
		$randnum = rand('1000','9999');
		$cont = '【东标308竞猜】您的手机号验证码是：'.$randnum.'，30分钟内有效，如非本人操作请忽略。';
		//获取秘钥地址
		$mikeyurl = 'http://api.sys.xingyuanauto.com/sms/GetPassSecret?appid='.$appidnum.'&ticket='.$curtime; 
		//创建请求
		$endkey = $this->contcurl($mikeyurl);
		$endkey = str_replace("\"","",$endkey); //过滤 引号  
		//发送地址 
		$urlp = 'http://api.sys.xingyuanauto.com/sms/SendSMS';
		$post_data = array(
		     "appid" => $appidnum,
		     "passkey" => $endkey,
		     "notecount"=>'1',
		     'phonelist' =>$phone1,
		     't'=>$curtime,
		     'noteContent'=>$cont
		     );
		$end =$this->contpostcurl($urlp,$post_data);
		$end =(array)json_decode($end);//json 转换为数组
		//设置验证码
	 	Cache::set('db308_'.$phone1,$randnum,'1800');
		echo $callback.'('.json_encode($end).')';
		return;

	} 
	//验证手机号是否注册，填写竞猜信息
	public function Dbjingcai(){
		$arr = input('param.');  
		$callback = $arr["callback"];
		
		//检测验证码
		 
		$ck = new HkdataModel();  
		$end = array("start"=>'3005','msg'=>'数据错误');
		$phone = $arr['phones'];//手机号
		$price = $arr['prices'];//竞猜价格
		$proid = $arr['proid']; //项目id 
		//获取验证码，进行匹配
		$yzmnum = $arr['yzmnum'];
		$thesnum = Cache::get("db308_".$phone);  //系统存在的验证码
		if($thesnum != $yzmnum ){ //验证码错误
			$end = array("start"=>'3006','msg'=>'验证码错误','num'=>0);
			echo $callback.'('.json_encode($end).')';
			return;
		}
		//通过手机号项目id，获取用户注册id
		$userinfo = $ck->Rtuidbyphone($phone,$proid);
		//通过userid，更新竞猜价格，，更新用户竞猜次数
		//先查询用户竞猜次数，如果大于0，再留资
		if(!empty($userinfo)){ //已注册，有注册数据
			if($userinfo['jcnum']>0){
				//可录入竞猜信息
				$jcarr['userid'] = $userinfo['dealer_id'];
				$jcarr['price'] = $price;
				$jcarr['time'] = time();
				$jcend = $ck->Itjcinfo($jcarr);
				if($jcend){ //更新用户竞猜次数
					$lownum = $userinfo['jcnum']-1;
					$upend = $ck->Upusernum($userinfo['dealer_id']);
					$end = array("start"=>'3001','msg'=>'竞猜价格已提交','num'=>$lownum);
				}else{//提交错误
					$lownum = $userinfo['jcnum'];
					$end = array("start"=>'3002','msg'=>'提交错误','num'=>$lownum);
				} 
			}else{
				$end = array("start"=>'3003','msg'=>'竞猜次数已用完','num'=>0);
			}
		}else{
			$end = array("start"=>'3004','msg'=>'手机号未注册，请先提交注册信息','num'=>0);
		}

		echo $callback.'('.json_encode($end).')';
		return;
		
	}
	//获取竞猜各价格区间对应的投票数量
	public function Rtnumaverprice(){
		$arr = input('param.');  
		$callback = $arr["callback"];
		$ck = new HkdataModel(); 
		//获取最小价格
		$minprice = $ck->Getminpric();
		$minprice = intval($minprice/10000);
		//获取最大价格
		$maxinfo = $ck->Getmaxpric();
		$maxprice = intval($maxinfo/10000) +1;
		$all = array();
		for($minprice ; $minprice < $maxprice; $minprice++) { 
			$maxp = $minprice+1;
			$num = $ck->Getnumaver($minprice,$maxp); 
			//echo Db::name('db_308_jc')->getLastSql();
			$all[$minprice."-".$maxp] = $num;
		} 
	  
		echo $callback.'('.json_encode($all).')';
		return;
	}
 
 
 
}
?>