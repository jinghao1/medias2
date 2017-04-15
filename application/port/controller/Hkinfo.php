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
use app\port\model\HkdataModel; 
 
class Hkinfo extends Controller	{	
	 
	//获取回馈信息
	public function ItHk()
	{
		$hk = new HkdataModel();
		$allinfo = input('param.');
		$newa = array();
		if($allinfo['pid']){
			//先判断此字段id是否有回馈值 
			$et =$hk->Etid($allinfo['pid']);
			if($et){
				if(!empty($allinfo['laiyuan'])){ //检测来源
					$ly = $hk->Autoadd($allinfo['pid'],'laiyuan');
					if(!empty($allinfo['lycont'])){
						$lyarr = array('lycon'=>$allinfo['lycont']);
						$lycon = $hk->EditHk($allinfo['pid'],$lyarr);
					}
				}
				if(!empty($allinfo['biaoti'])){
					$lyb = $hk->Autoadd($allinfo['pid'],'btd');
				}
				if(!empty($allinfo['zl'])){
					$lyz = $hk->Autoadd($allinfo['pid'],'quality');
				}
			}else{
				if(!empty($allinfo['laiyuan']) && !empty($allinfo['lycont'])){
					$newa['laiyuan'] = 1;
					$newa['lycon'] = $allinfo['lycont']; //来源链接地址
					
				}
				if(!empty($allinfo['biaoti'])){ //标题党
					$newa['btd'] = 1;
				}
				if(!empty($allinfo['zl'])){ //质量差
					$newa['quality'] = 1;
				}
				$newa['artid'] = $allinfo['pid']; //文章id
				//数据插入
				$bad = $hk->IntHk($newa);
			}  
		}
	 	return 1;
		//var_dump($bad,$lyb);
		exit;
		//$data = $car->DealerClass($dealer_id);
		//exit(json_encode($data));
	}

	//广告点击监测
	public function Adck(){
		$ck = new HkdataModel();
		$all = input('param.'); //接收信息 
		$newaw = array(); 
 
		if($all['tit'] && $all['pos'] && $all['link']){
			$newaw['title'] = $all['tit']; //标题
			$newaw['position'] = $all['pos']; //广告位
			$newaw['link'] = $all['link']; //链接
			//检测是否已经存在
			$et = $ck->Ethead($newaw);
			if($et){ //数据更新
				$upad = $ck->EditAd($newaw); 
			}else{ //插入数据
				$newaw['time'] = time();
				$newaw['num'] = 1;
				$addend = $ck->IntAd($newaw);
			}
		}
		return 1;
	}

	//接口信息转换列表
	public function Changelist(){
		$appid = 'a31994181db54f5395f0fe43b19cf520';
		$all = input('param.'); //接收信息    
		$callback = $all["callback"];  
		$all['newsType'] = 0; //最新
		//$zurl = 'http://172.16.0.142:13000/newsapi/getnewslist?appid='.$appid.'&newsType='.$all['newsType'].'&pageNo='.$all['pageNo'].'&pageSize='.$all['pageSize'];
		$zurl = 'http://partner.api.qichedaquan.com/newsapi/getnewslist?appid='.$appid.'&newsType='.$all['newsType'].'&pageNo='.$all['pageNo'].'&pageSize='.$all['pageSize'];
		$end = file_get_contents($zurl); 
		echo $callback.'('.json_encode($end).')';
		return;
	}

	//接口信息转换 内容详情
	public function Changecont(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"]; 
		$appid = 'a31994181db54f5395f0fe43b19cf520';
		$xurl = 'http://172.16.0.142:13000/newsapi/detail/'.$all['cid'].'?appid='.$appid;
		$xurl = 'http://partner.api.qichedaquan.com/newsapi/detail/'.$all['cid'].'?appid='.$appid;
		$end = file_get_contents($xurl);
		//$end = '00099';  
		echo $callback.'('.json_encode($end).')';
		return;
	}

 
}
?>