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
	 
	//获取访问信息
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
				}
				if(!empty($allinfo['biaoti'])){
					$lyb = $hk->Autoadd($allinfo['pid'],'btd');
				}
				if(!empty($allinfo['zl'])){
					$lyz = $hk->Autoadd($allinfo['pid'],'quality');
				}
			}else{
				
			}
			
		 
			 
			// 自增 score 字段
			db('user')->where('id', 1)->setInc('score');
		}
		exit(json_encode($allinfo)) ;
		return 1;
		var_dump($allinfo);
		exit;
		//$data = $car->DealerClass($dealer_id);
		//exit(json_encode($data));
	}

 
}
?>