<?php 
/**
 * 极客之家 高端PHP - 用户使用注册信息模块
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\bmpro\controller;
use	think\Controller;
use	think\Request;
use	think\Db; 
use think\Cache;
use think\Session;
use app\bmpro\model\BmdataModel; 
class Bmlot extends	Controller	{
	//概率更新
	//edit by song  
	//根据概率表p_id修改对应的上午下午概率
	public function Updprop(){
		$pid = input('param.pid');
		if($pid){
			$ck = new BmdataModel();
			//获取此概率的参数
			$pinfo = $ck->Getchancebyid($pid);
			//  上午人数概率>上午票数概率=>下午中奖概率>上午中奖概率
			//上午票数概率 = $pinfo[0]['move_prop'];
			//上午人数概率 = $pinfo[0]['number_prop'];
			if(isset($pinfo[0]) && !empty($pinfo[0])){
				//上午中奖概率 = ((票数/天)*上午票数概率)/((人数/天)*上午人数概率)
				$uppro = (($pinfo[0]['move_number']/2)*$pinfo[0]['move_prop'])/(($pinfo[0]['sum_number'])*$pinfo[0]['number_prop']);
				//下午中奖概率 = ((票数/天)*(1-上午票数概率))/((人数/天)*(1-上午人数概率))
				$dowpro = (($pinfo[0]['move_number']/2)*(1-$pinfo[0]['move_prop']))/(($pinfo[0]['sum_number'])*(1-$pinfo[0]['number_prop'])); 
				//转为百分比
				$uppro = floor($uppro*10000)/10000*100;
				$dowpro = floor($dowpro*10000)/10000*100; 
				//更新概率
				$end = $ck->Updchancebyid($pid,$uppro,$dowpro); 
				echo '上午概率：'.$uppro.'%','下午概率：'.$dowpro.'%','更新状态：'.$end;
			}
		} 
		exit; 
	}	
	//end by song
	public function Choice() 
	{	
		$ck = new BmdataModel();
		//获取中奖概率
		$ch = $ck->Getchance();
		var_dump($ch[0]);
		$curH = date("H",time());
		if($curH<=12){
			$zjnum = $ch[0]['probab'];
		}
		exit;
		//$ch[0]['probab'] = 80;
		$tolinfo[1]['id'] = 1; //中奖
		$tolinfo[1]['chance'] = $ch[0]['probab']; //中奖
		$tolinfo[1]['name'] = '中奖';
		$tolinfo[2]['id'] = 2 ; //不中奖
		$tolinfo[2]['chance'] = 100 - $ch[0]['probab']; //不中奖
		$tolinfo[2]['name'] = '不中奖' ; //不中奖

		//控制不中奖
		$self = 0; //0 走概率，1 不中奖
		$userid = 2; //用户id
		$phone = '17703186690'; //用户手机号
		if($tolinfo  && $phone){
			
			foreach($tolinfo as $vall){
				//$vall为奖项信息
				$prize[$vall['id']] = $vall['chance'];  //概率 (概率相加需要为100)
				$prizeList[$vall['id']] = $vall['name']; //奖项名称
				$endlot = $vall['id']; //获取最后一个奖项 无库存时使用
			} 
			$times = 10;
			$max = 0;
			foreach ($prize as $k => $v)
			{
				$max = $v * $times + $max;
				$row['v'] = $max;
				$row['k'] = $k;
				$prizeZone[] = $row;
			} 
			$max--; //临界值
			$rand = mt_rand(0, $max);
			//$zone = 4;
			foreach ($prizeZone as $k => $v)
			{
				if ($rand >= $v['v'])
				{
					if ($rand >= $prizeZone[$k + 1]['v'])
					{
						continue;
					}
					else
					{
						$zone = $prizeZone[$k + 1]['k'];
						break;
					}
				}
				$zone = $v['k'];
				break;
			}
			//检测当前有无正在抽奖
			if($self==1){ //自己人 不中奖
				$zone = $endlot; //最后一个奖项
			}
			$lting = Cache::get("loting"); 
			//return 1; 
			if(empty($lting)){
				Cache::set('loting','1',2); 
			}
			//通过奖项id，获取此奖项库存
			$lotnum = 100; //奖项库存
			$userinfo = array(
					'userid'=>$userid, //用户id
					'phone'=>$phone, //用户手机号
					'lotid'=>$zone //奖项id
				);
			
			if($lotnum<=0 || $lting==1){  
				$userinfo['lotid'] = $endlot; //最后一个奖项
				$userinfo['status'] = 3; //无库存情况下插入  
				$userinfo['bflotid'] = $zone; //之前中奖无库存，奖项id
			}
			
			$jxname = $prizeList[$userinfo['lotid']];//奖项名称
		    //给 用户发奖 ->发奖成功，减库存 == 需要加入事务 回滚 == 
			$adduserlot = $this->SendLotUser($userinfo);
			if($adduserlot){ // 发奖成功后 执行 减库存 
				$statu = '2004';  //减库存失败 发奖成功 
			}else{
				$statu = '2005'; //发奖失败
				$jxname = '注册成功,抽奖失败';
			} 
			Cache::set('loting',''); //抽奖已执行完成
			if($userinfo['lotid'] == $endlot){ //谢谢参与
				$statu = '2003'; 
				$jxname = $prizeList[$endlot];//谢谢参与
			} 
		} 
		var_dump($statu,$jxname);
		exit;
		$this->assign('dealer_info',$thedealer_info);
				
		return $this->fetch();					
	} 

	private function SendLotUser(){
		return 1;
	}
 
}
?>