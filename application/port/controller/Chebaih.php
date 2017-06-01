<?php  
namespace app\port\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
use think\Cache;
use think\Session;
use app\admin\model\CityaModel;
use app\port\model\ChebHModel; 
//use think\auth\Auth;
class Chebaih extends Controller	
{
	 
	public function tests(){
		return 1;
	}

	//citychange 城市切换
	public function Changecityinfo(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"]; 
		$curtime = time();
		$ck = new ChebHModel();
		$citys = $ck->Allopencity();
		// 获取所有状态开启展示信息
		$cbarr = array(); //筹备中城市
		$endarr = array(); //活动已截止城市区域
		$acting = array(); //正在举行活动
		$actnum = array(); //正在举行活动标签
		$endnum = array(); //已结束活动标签
		$cbanum = array(); //筹备中活动标签

		$firstnew = array(); //第一个活动城市
		//将数据分类
		$str1 = '<h3 style="color: #000">正在报名</h3>
	            <div class="city-list">';
		if($citys){
			foreach($citys as $ainfo){
				if($ainfo['waiting']){ //筹备中城市信息
					//$cbarr[$ainfo['cityid']] = $ainfo;
					if(!empty($cbanum)){
						if(in_array($ainfo['initial'],$cbanum)){
							$str3 .= ' <a>'.$ainfo['cityname'].'</a>';
						}else{
							$cbanum[] = $ainfo['initial']; //赋值
							$str3 .= '</dd></dl><dl><dt>'.$ainfo['initial'].':</dt> <dd><a >'.$ainfo['cityname'].'</a>';
						}
					}else{
						$cbanum[] = $ainfo['initial']; //赋值
						$str3 = '<dl class="clearfix"><dt>'.$ainfo['initial'].':</dt> <dd><a >'.$ainfo['cityname'].'</a>';

					}
				}else if($curtime>$ainfo['endtime']){ //判断截止日期，截止后区分  已结束
					//$endarr[$ainfo['cityid']] = $ainfo;
					if(!empty($endnum)){
						if(in_array($ainfo['initial'],$endnum)){
							$str4 .= ' <a>'.$ainfo['cityname'].'</a>';
						}else{
							$endnum[] = $ainfo['initial']; //赋值
							$str4 .= '</dd></dl><dl><dt>'.$ainfo['initial'].':</dt> <dd><a >'.$ainfo['cityname'].'</a>';
						}
					}else{
						$endnum[] = $ainfo['initial']; //赋值
						$str4 = '<dl class="clearfix"><dt>'.$ainfo['initial'].':</dt> <dd><a >'.$ainfo['cityname'].'</a>';
					}
				}else{ //正在举行活动城市
					//$acting[$ainfo['cityid']] = $ainfo;
					$begt = date("Y-m-d",$ainfo['begtime']);//开始时间
					$endt = date("Y-m-d",$ainfo['endtime']); //截止时间
					$begmon = intval( date("m",$ainfo['begtime'])) ."月". intval( date("d",$ainfo['begtime']))."日"; //开始时间 月份开始
					$endmon = intval( date("m",$ainfo['endtime'])) ."月". intval( date("d",$ainfo['endtime']))."日"; //截止时间 月份开始
					if(!empty($actnum)){
						if(in_array($ainfo['initial'],$actnum)){
							$str2 .= '<a onclick="actall(\''.$begmon.'\',\''.$endmon.'\',\''.$ainfo['address'].'\','.$ainfo['cityid'].',\''.$ainfo['cityname'].'\')">'.$ainfo['cityname'].'</a>';
						}else{
							$actnum[] = $ainfo['initial']; //赋值
							$str2 .= '</dd></dl><dl><dt  >'.$ainfo['initial'].':</dt> <dd> <a onclick="actall(\''.$begmon.'\',\''.$endmon.'\',\''.$ainfo['address'].'\','.$ainfo['cityid'].',\''.$ainfo['cityname'].'\')">'.$ainfo['cityname'].'</a>';
						}
					}else{
						$actnum[] = $ainfo['initial']; //赋值
						$str2 = '<dl><dt  >'.$ainfo['initial'].':</dt> <dd><a onclick="actall(\''.$begmon.'\',\''.$endmon.'\',\''.$ainfo['address'].'\','.$ainfo['cityid'].',\''.$ainfo['cityname'].'\')">'.$ainfo['cityname'].'</a>';
						$firstnew['begt'] = $begmon;
						$firstnew['endt'] = $endmon;
						$firstnew['address'] = $ainfo['address'];
						$firstnew['cityid'] = $ainfo['cityid'];
						$firstnew['cityname'] = $ainfo['cityname'];
					}
				}
			}
			$str2 .= '</dl>
		            </div>
		            <h3>筹备中</h3>
		            <div class="city-list disabled">';
		        
		    $str3 .= '</dl>
		            </div>
		            <h3>已结束</h3>
		            <div class="city-list disabled">';
		    $str4 .= '</dl>
		            </div> '; 
		}
		$str = $str1.$str2.$str3.$str4;
		// 获取确认举行，为到期活动
		// 确认举行，过期活动
		//var_dump($proinfo);  
	    $allend = array('str'=>$str,'firstinfo'=>$firstnew);
		echo $callback.'('.json_encode($allend).')';
		return;
	} 


	//citychange 城市切换
	public function Chityinfom(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"]; 
		$curtime = time();
		$ck = new ChebHModel();
		$citys = $ck->Allopencity();
		// 获取所有状态开启展示信息
		$cbarr  = array(); //筹备中城市
		$endarr = array(); //活动已截止城市区域
		$acting = array(); //正在举行活动
		$actnum = array(); //正在举行活动标签
		$endnum = array(); //已结束活动标签
		$cbanum = array(); //筹备中活动标签
		//第一条活动信息
		$firstnew = array();
		//将数据分类
		$str1 = '<h2>正在报名</h2>
            	<ul class="uls">';
		if($citys){
			foreach($citys as $ainfo){
				if($ainfo['waiting']){ //筹备中城市信息
					//$cbarr[$ainfo['cityid']] = $ainfo;
					if(!empty($cbanum)){
						if(in_array($ainfo['initial'],$cbanum)){
							$str3 .= ' <span>'.$ainfo['cityname'].'</span>';
						}else{
							$cbanum[] = $ainfo['initial']; //赋值
							$str3 .= '</div></li><li><a>'.$ainfo['initial'].':</a> <div class="citys"><span >'.$ainfo['cityname'].'</span>';
						}
					}else{
						$cbanum[] = $ainfo['initial']; //赋值
						$str3 =   '<li><a>'.$ainfo['initial'].'：</a><div class="citys"><span>'.$ainfo['cityname'].'</span>';

					}
				}else if($curtime>$ainfo['endtime']){ //判断截止日期，截止后区分  已结束
					//$endarr[$ainfo['cityid']] = $ainfo;
					if(!empty($endnum)){
						if(in_array($ainfo['initial'],$endnum)){
							$str4 .= ' <span>'.$ainfo['cityname'].'</span>';
						}else{
							$endnum[] = $ainfo['initial']; //赋值
							$str4 .= '</div></li><li><a>'.$ainfo['initial'].':</a> <div class="citys"><span >'.$ainfo['cityname'].'</span>';
						}
					}else{
						$endnum[] = $ainfo['initial']; //赋值
						$str4 =  '<li><a>'.$ainfo['initial'].'：</a><div class="citys"><span>'.$ainfo['cityname'].'</span>';
					}
				}else{ //正在举行活动城市
					//$acting[$ainfo['cityid']] = $ainfo;
					$begt = date("Y-m-d",$ainfo['begtime']);//开始时间
					$endt = date("Y-m-d",$ainfo['endtime']); //截止时间
					$begmon = intval( date("m",$ainfo['begtime'])) ."月". intval( date("d",$ainfo['begtime']))."日"; //开始时间 月份开始
					$endmon = intval( date("m",$ainfo['endtime'])) ."月". intval( date("d",$ainfo['endtime']))."日"; //截止时间 月份开始
					if(!empty($actnum)){
						if(in_array($ainfo['initial'],$actnum)){
							$str2 .= '<span onclick="actall(\''.$begmon.'\',\''.$endmon.'\',\''.$ainfo['address'].'\','.$ainfo['cityid'].',\''.$ainfo['cityname'].'\',this)">'.$ainfo['cityname'].'</span>';
						}else{
							$actnum[] = $ainfo['initial']; //赋值
							$str2 .= '</div></li><li><a>'.$ainfo['initial'].':</a> <div class="citys">>  <span onclick="actall(\''.$begmon.'\',\''.$endmon.'\',\''.$ainfo['address'].'\','.$ainfo['cityid'].',\''.$ainfo['cityname'].'\',this)">'.$ainfo['cityname'].'</span>';
						}
					}else{
						$actnum[] = $ainfo['initial']; //赋值
						$str2 =  '<li><a>'.$ainfo['initial'].'：</a><div class="citys"><span onclick="actall(\''.$begmon.'\',\''.$endmon.'\',\''.$ainfo['address'].'\','.$ainfo['cityid'].',\''.$ainfo['cityname'].'\',this)" >'.$ainfo['cityname'].'</span> ';
						$firstnew['begt'] = $begmon;
						$firstnew['endt'] = $endmon;
						$firstnew['address'] = $ainfo['address'];
						$firstnew['cityid'] = $ainfo['cityid'];
						$firstnew['cityname'] = $ainfo['cityname'];
						
					}
				}
			}
			$str2 .= '</div></li></ul>
		             <h2>筹备中</h2>
		             <ul class="uls">';
		        
		    $str3 .= ' </div></li></ul>
		             <h2>已结束</h2>
		             <ul class="uls">';
		    $str4 .= '</div></li></ul> '; 
		}
		$str = $str1.$str2.$str3.$str4;
		// 获取确认举行，为到期活动
		// 确认举行，过期活动
		//var_dump($proinfo);  
	 	$allend = array('str'=>$str,'firstinfo'=>$firstnew);
		echo $callback.'('.json_encode($allend).')';
		return;
	} 

	//  车型信息 
	public function Carinfo(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"];  
		$ck = new ChebHModel();
		$arr = $ck->Undercar(0);
		if($arr){
			foreach($arr as $key=>$val){
				$one[$val['brand_id']] = $val['brand_name']; 
				$lin = $ck->Undercar($val['brand_id']);	
				foreach($lin as $erval){
					$two[$val['brand_id']][] = $erval['brand_id']; 
					$allcity[$erval['brand_id']] = $erval['brand_name'];
					$tt = $ck->Undercar($erval['brand_id']);
					$ti=0;
					foreach($tt as $ttval){
						$three[$erval['brand_id']][] = $ttval['brand_id'];
						//$two[$val['brand_id']][$ti][$erval['brand_id']] = $ttval['brand_id'];
						$allcity[$ttval['brand_id']] = $ttval['brand_name'];
						$ti++;
					}	
				} 
			}
		}
		$end['pinpai'] = $one;
		$end['releav'] = $two; //一级=》品牌
		$end['detail'] = $three; //品牌=》车型
		$end['allcar'] = $allcity;
	 
		echo $callback.'('.json_encode($end).')';
		return;
	}

	//  车型信息 new  分表
	public function Carinfocbh(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"];  
		$ck = new ChebHModel();
		$arr = $ck->Udcar(0,'cbh_brand');

		if($arr){
			foreach($arr as $key=>$val){
				$one[$val['brand_id']] = $val['brand_name']; 
				$lin = $ck->Udcar($val['brand_id'],'cbh_masterbrand');	
				foreach($lin as $erval){
					$two[$val['brand_id']][] = $erval['brand_id']; 
					$allcity[$erval['brand_id']] = $erval['brand_name'];
					$tt = $ck->Udcar($erval['brand_id'],'cbh_carserise'); 
					foreach($tt as $ttval){
						$three[$erval['brand_id']][] = $ttval['brand_id'];
						//$two[$val['brand_id']][$ti][$erval['brand_id']] = $ttval['brand_id'];
						$allcity[$ttval['brand_id']] = $ttval['brand_name']; 
					}	
				} 
			}
		}
		$end['pinpai'] = $one;
		$end['releav'] = $two; //一级=》品牌
		$end['detail'] = $three; //品牌=》车型
		$end['allcar'] = $allcity;
	 	$cartt = array(); //购车时间 
	 	//购车时间
	 	$cartime = $ck->Chbcartime();
	 	if($cartime){
	 		foreach($cartime as $cart){
	 			$cartt[$cart['id']] = $cart['timename'];
	 		}
	 	}
	 	$end['cartime'] =  $cartt;
		echo $callback.'('.json_encode($end).')';
		return;
	}


	private function contcurl($url){
		$ch = curl_init();  
		curl_setopt ($ch, CURLOPT_URL, $url);  
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10); 
		$dxycontent = curl_exec($ch);
		curl_close($ch); 
		return $dxycontent;
	}

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



	//点击获取验证码 == 车百汇
	public function GetyzmChb(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"];  
		$appidnum = 2; //汽车大全id
		$curtime = time(); //获取当前时间戳
		$phone1 = $all['iphone']; //收取短信手机号 
		if(!preg_match("/^1[34578]{1}\d{9}$/",$phone1)){  
		    return 3;  //不是手机号格式

		}
		//检测此手机号是否已注册
		$dealer = new ChebHModel();
		$phend = $dealer->Cbhunique($phone1);
		if($phend==1){ 
			$endyzjm = array("start"=>'1003','msg'=>'该手机已经注册');
			echo $callback.'('.json_encode($endyzjm).')';
			return; 
		}
		$randnum = rand('1000','9999');
		$cont = '【车百汇】你的手机号验证码是：'.$randnum.'，30分钟内有效，如非本人操作请忽略。';
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
	 	Cache::set('cbh_'.$phone1,$randnum,'1800');
		echo $callback.'('.json_encode($end).')';
		return;

	} 

	//注册信息 === 车百汇
	public function RegCbh(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"]; 
		$thes = Cache::get("cbh_".$all['phone']);  //系统存在的验证码
		$dealer = new ChebHModel();
		$data['phone'] = $all['phone']; //手机号
		$data['name'] = $all['name'];
		$data['sex'] = 0; //未选择
		$data['buy_car_time'] = $all['buytime']; //购车时间
		$data['car_series_id'] = $all['carseris']; //车系id
		if(isset($all['carname']) && !empty($all['carname'])){
			//通过车型名称查询车型id
			$carnamearr = explode("-",$all['carname']);
			$brandname = $carnamearr[0]; //品牌名称
			unset($carnamearr[0]);
			$carnamestr = implode("-",$carnamearr);
			//通过车型系列名称，获取车型id
			$data['car_series_id'] = 0;
			$cend = $dealer->Caridbycarname($carnamestr);
			if(isset($cend[0]) && $cend[0]['brand_id']){
				$data['car_series_id'] = $cend[0]['brand_id'];
			}
		}
		//$data['brand'] = $all['brand']; //品牌id
		//$data['masterbrand'] = $all['masterbrand']; //车系分类id。奥迪（进口）
		$data['whreg'] = $all['regw']; //1为mobile 2为pc
		$data['time'] = time(); //注册时间 
		if(isset($all['curaddr'])){
			$data['localaddr'] = $all['curaddr']; //当前定位省份-城市
		}
		if(isset($all['changeddr'])){
			$data['changeaddr'] = $all['changeddr']; //用户选择后省份-城市
		}
		
		//根据城市名称，获取活动起止时间，地址

		if(isset($all['fself'])&&!empty($all['fself']) ){ //注册来源
			$data['from'] = $all['fself'];
		} 
 
		if($thes != $all['yzm'] || empty($thes) || empty($all['yzm'])){
			$endyzjm = array("start"=>'1004','msg'=>'验证码错误');
			echo $callback.'('.json_encode($endyzjm).')';
			return;
		}else{ //信息监测  
			//检测信息

			if(!$all['phone'] ){
				//$this->error('请填写预约信息，谢谢！'); 
				 
				$endyzjm = array("start"=>'1002','msg'=>'手机号不合法');
				echo $callback.'('.json_encode($endyzjm).')';
				return;

			}else{ //检测手机号的唯一性
				//手机号格式验证  

				if(!preg_match("/^1[34578]{1}\d{9}$/",$all['phone'])){  
				    $endyzjm = array("start"=>'1002','msg'=>'手机号不合法');
					echo $callback.'('.json_encode($endyzjm).')';
					return;
				} 
		
				$phend = $dealer->Cbhunique($data['phone']);

				if($phend==1){ 
					$endyzjm = array("start"=>'1003','msg'=>'该手机已经注册');
					echo $callback.'('.json_encode($endyzjm).')';
					return; 
				}
			}   
			//信息注册
			$res = $dealer->CbhRegall($data);  
			if($res){
				$endyzjm = array("start"=>'2003','msg'=>'注册成功，感谢您的参与');

				echo $callback.'('.json_encode($endyzjm).')';
				//发送注册成功短信
				$this->SendSuCbh($all['phone']);
				return; 
			}else{
				$endyzjm = array("start"=>'2007','msg'=>'注册失败，请重试');
				echo $callback.'('.json_encode($endyzjm).')';
				return; 
			}
		} 
	 
	}
	//发送短信成功注册车百汇
	public function SendSuCbh($phone){
		$appidnum = 2; //汽车大全id
		$curtime = time(); //获取当前时间戳
		$cont = '【车百汇】恭喜您报名成功汽车大全车百汇活动！欢迎您于2017年X月X日前往XXXXX（地址）领取签到礼品，现场还有丰富的互动环节等您参与，订购车更有机会获得购车大礼！';
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
		     'phonelist' =>$phone,
		     't'=>$curtime,
		     'noteContent'=>$cont
		     );
		$end =$this->contpostcurl($urlp,$post_data);
	}

	//车百汇 pc端广告id
	private function pcadinfo(){
		//参展品牌id
			$czarr[] = '7d4c4b8e-d1f3-43ed-8323-201b94a35651';
			$czarr[] = '5d2f811f-697b-41ff-9a3f-e51d2c9e56c5';
			$czarr[] = 'caacb6cf-c1ee-4141-b63b-3415b1484c8d';
			$czarr[] = '41da6b01-73a9-42f2-a008-148ef7744152';
			$czarr[] = 'd62eea56-ffe3-4505-b360-b624922e9485';
			$czarr[] = '2de7db6a-e415-4803-a7ac-5153083cf780';
			$czarr[] = '33a3a5e3-480a-4db9-8bdc-2b441936389f';
			$czarr[] = 'c9b483d3-4f2d-4d2e-aa58-9e7a6ee6c59a';
			$czarr[] = 'a1c1719c-5923-42aa-a96e-7e39a70e2835';
			$czarr[] = '127bc10b-a29e-4ec5-bef2-0be0e14a53b4';
			$czarr[] = '010a26c5-a0dd-4f4f-8c00-e398cef2866e';
			$czarr[] = 'a863f558-2786-4bbe-9bd1-823823ffb4ca';
			$czarr[] = 'ef835ca5-d45b-4fd0-9fc0-cc8ba2fe84cd';
			$czarr[] = '0382e2b6-17c3-4448-bfb0-0bf4a4b223f5';
			$czarr[] = 'c2e83859-d999-4cf2-974d-11c1ecf00fc6';
			$czarr[] = 'e7042612-2101-4ae4-a6e7-00fdf07c720c';
			$czarr[] = '5a378b66-e40d-429d-93f1-a112bddbadd7';
			$czarr[] = '64a289c7-f74a-47e3-885e-beb179e3934c';
			$czarr[] = 'f8e8701a-79b3-499b-9679-4041c00caed5';
			$czarr[] = '5affb1c4-1529-4d75-b1fa-ee5ac33d0942';
	//	限时特惠 
			$xsarr[] = '2378cb0a-c603-4da8-848f-a995d084abbc';
			$xsarr[] = '4c8161ea-c3a4-4223-8d41-1eca788b63dd';
			$xsarr[] = '6fb2de84-6c53-430b-9a68-01c31158ff7d';
			$xsarr[] = '511a4154-c91e-4853-ba60-617fc49ab9dc';
			$xsarr[] = '33bb8c9a-a386-4f4a-a4ba-82d11f35b0fd';
			$xsarr[] = 'bafdd380-d0a9-4a45-91a6-c219d686caea';
			return array('cz'=>$czarr,'xs'=>$xsarr);
	}
	//手机端 车百汇广告id
	private function mbadinfo(){
		//参展品牌id
		$czarr[] = 'dfa62e04-58b6-4cf5-bf74-07ad7f86bd5d';
		$czarr[] = '168bc285-34c4-42e2-bef3-6e625763ff54';
		$czarr[] = '7dbcf2d2-b323-46a9-b63d-81cbcdf53553';
		$czarr[] = 'c66bceba-a1b6-4b6f-9995-b68a40e1b549';
		$czarr[] = '75839032-279f-40a5-bc7f-c1b89fb7400e';
		$czarr[] = 'd324ad12-7f6b-44c1-94c4-a2699effea51';
		$czarr[] = 'bf45b2ce-428f-42c0-8cba-a919d6a4cb8c';
		$czarr[] = 'b9ec8b11-3721-4890-81c0-8cd6fc91266e';
		$czarr[] = '9ac847aa-29f1-46ad-ac77-038411a0b881';
		$czarr[] = 'b787c3c8-7a0a-4023-af86-9b8f32ed12ba';
		$czarr[] = '49bfc8aa-87af-46ae-afc3-b6fab194c254';
		$czarr[] = '5844d551-6851-4912-abbc-a5a93e11e418';
		$czarr[] = '9baa2f16-e8c9-4f3e-bdcf-921dfc1fb46d';
		$czarr[] = '6d882669-8e1d-4f41-97fe-91109a260488';
		$czarr[] = '27289149-dae8-4abe-9388-916f1fdff55a';
		$czarr[] = '4c6cf738-2bbf-4fe0-88c4-34c8181d0013';
		$czarr[] = '589d7665-15de-41f9-ae96-638e14b8bd4e';
		$czarr[] = '7999b83c-2579-44c4-b22b-1defc2676084';
		$czarr[] = '733d9d23-14ab-45f8-a2a6-aac3727ae12f';
		$czarr[] = '9b87664d-16ee-420f-9b88-2b248ad8f329';
	 
		//	限时特惠 
		$xsarr[] = 'd97d2d3a-349c-4ce2-a47e-da77bc12ad30';
		$xsarr[] = 'dbdcf670-49b5-4179-8af4-9c16f7cc0171';
		$xsarr[] = '839abbef-2ffc-41e3-b0da-d9361bc3fc3b';
		$xsarr[] = 'd6f6f1ef-2492-4a48-9408-d2e6d0f0169b';
		$xsarr[] = '798bf09f-69a0-4c6f-ba42-f83717e0048c';
		$xsarr[] = 'eb557aad-f477-442e-91d9-d1f1245703fb';
		return array('cz'=>$czarr,'xs'=>$xsarr);
	}

	//广告信息转换
	//接口信息转换 内容详情
	public function AdcontAll(){
		$all = input('param.'); //接收信息 
		$newarr = array(); //参展品牌data
		$xstharr = array(); //限时特惠信息
		if(isset($all['regw']) && $all['regw']==1 ){ //取pc端广告数据
			$adidinfo = $this->pcadinfo();
		}else{
			$all['regw'] = 2;
			$adidinfo = $this->mbadinfo();
		}
		$callback = $all["callback"]; 
		$cityid = $this->Citynametoid($all['setCity']);
		$thes = Cache::get($all['regw']."_chebaihuiad_".$cityid); 
		//var_dump($thes);
		$thes = "";
		if(empty($thes)){ 
			
			$czarr = $adidinfo['cz']; //参展品牌id
			$xsarr = $adidinfo['xs']; //	限时特惠 
	  	//通过城市名字获取城市id 
			foreach($czarr as $key=>$vid){ 
				$xurl = 'http://g.qichedaquan.com/api/ad/GetAdData?BlockCode='.$vid; 
				$end = file_get_contents($xurl);
				$obj =get_object_vars(json_decode($end)) ; 
				if($obj['Success']){ 
					$arrinfo =json_decode($obj['HtmlCode']) ;
					 
					if($cityid>0){ 
						foreach($arrinfo as $keya=>$coval){
							
							$arri = get_object_vars($coval);   
						 
							if($arri['areaid']==$cityid){
								//字段1
								$newarr[$key]['text1'] = $arri['data'][0]->Text1;
								//字段2
								$newarr[$key]['text2'] = $arri['data'][0]->Text2;
								//图片
								$newarr[$key]['image'] = $arri['data'][0]->CarImage;
								//链接
								$newarr[$key]['link'] = $arri['data'][0]->Link; 
								break;
								 
							}else{
								continue;
							} 
					 	}
					} 
				 
					if(empty($newarr[$key])){
						$arri = get_object_vars($arrinfo[0]); 
					  
						//字段1
						$newarr[$key]['text1'] = $arri['data'][0]->Text1;
						//字段2
						$newarr[$key]['text2'] = $arri['data'][0]->Text2;
						//图片
						$newarr[$key]['image'] = $arri['data'][0]->CarImage;
						//链接
						$newarr[$key]['link'] = $arri['data'][0]->Link;   
					}
				}
			}
			 
			foreach($xsarr as $key1=>$vid){ 
				$xurl = 'http://g.qichedaquan.com/api/ad/GetAdData?BlockCode='.$vid;
				//$xurl = 'http://partner.api.qichedaquan.com/newsapi/detail/'.$all['cid'].'?appid='.$appid;
				$end = file_get_contents($xurl);
				$obj =get_object_vars(json_decode($end)) ;
				
				if($obj['Success']){ 
					$arrinfo =json_decode($obj['HtmlCode']) ;
					if($cityid>0){ 
						foreach($arrinfo as $kci=>$ckv){
							$arri = get_object_vars($ckv); 
							if($arri['areaid']==$cityid){ 
								//字段1 
								if($arri['data'][0]){
									$xstharr[$key1]['text1'] = $arri['data'][0]->Text1;
									//字段2
									$xstharr[$key1]['text2'] = $arri['data'][0]->Text2;
									//图片
									$xstharr[$key1]['image'] = $arri['data'][0]->CarImage;
									//链接
									$xstharr[$key1]['link'] = $arri['data'][0]->Link; 
								} 
								break;
							}else{
								continue;
							}
						}
					}
					if(empty($xstharr[$key1])){
						 
						$arri = get_object_vars($arrinfo[0]); 
						//字段1 
					 
						if($arri['data'][0]){
							$xstharr[$key1]['text1'] = $arri['data'][0]->Text1;
							//字段2
							$xstharr[$key1]['text2'] = $arri['data'][0]->Text2;
							//图片
							$xstharr[$key1]['image'] = $arri['data'][0]->CarImage;
							//链接
							$xstharr[$key1]['link'] = $arri['data'][0]->Link; 
						}  
					} 
				}
			}
			$cznum = count($newarr);
			$xsnum = count($xstharr);
			$allend = array('czpp'=>$newarr,'cznum'=>$cznum,'xsth'=>$xstharr,'xsnum'=>$xsnum);
			Cache::set($all['regw']."_chebaihuiad_".$cityid,$allend,'3600');
		}else{
			$allend = $thes;
		}
		//$end = '00099';  
		
		echo $callback.'('.json_encode($allend).')';
		return;  
	}
	//根据城市名称获取城市id
	public function Citynametoid($name){
		$ck = new ChebHModel();
		$id = 0;
		$end = $ck->rtcityidbyname($name);
		if(isset($end[0])){
			$id = $end[0]['cityid'];
		}
	 
		return $id;
	}

	//判断当前城市，是否正在举行活动
	public function Ckaddrssact(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"]; 
		$str = $all['cityname'];
		$ck = new ChebHModel();
		$info = $ck->Actcityinfo($str);
		$curtime = time();
		$endyzjm = array("start"=>'3003','msg'=>'已结束');
		if(isset($info[0]['cityid'])){
			//判断是否筹备中， 活动是否结束
			if($info[0]['waiting']==1){ //筹备中，返回信息
				 $endyzjm = array("start"=>'3001','msg'=>'筹备中');
				
				
			}else if($curtime<$info[0]['endtime'] || $info[0]['endtime']==0){
				$info[0]['endtime'] = date("m月d日",$info[0]['endtime']);
				$info[0]['begtime'] = date("m月d日",$info[0]['begtime']);
 				$endyzjm = array("start"=>'3002','msg'=>'活动中','actinfo'=>$info[0]);
				 
			}else if($curtime>$info[0]['endtime'] && $info[0]['endtime']>0){
				$endyzjm = array("start"=>'3003','msg'=>'已结束');
				 
			} 	 
		}
		echo $callback.'('.json_encode($endyzjm).')';
		return;
	 
	}

	//一键查询，车百汇注册信息
	//public function gtphone(){ 
	//	$all = input('param.'); //接收信息  
	//	$end = array();
	//	if(isset($all['phone']) && !empty($all['phone'])){
	//		//查询此手机号所有注册信息
	//		$ck = new ChebHModel();
			 
	//		$ph =$all['phone'];
	//		$end = $ck->regallcbh($ph);
	//		//var_dump($end[0]);
	//		$this->assign('data',$end[0]); 
	//	} 
	//	return $this->fetch(); 
	//}


}	


 


?>