<?php

namespace app\port\model;
use think\Model;
use think\Db;
use think\Cache;
class LotteryModel extends Model
{
	
	//抽奖方法 
	function HavChance($userid,$self=null){ 
		//检测确认是否插入成功，并获取user phone
		$phone = $this->Ckgetphone($userid);
		if(!$phone){
			$statu = '2007';
			$jxname = "注册失败，请重试";
		}else{
			$statu = '2008';
			$jxname = "此次活动已结束";
		}
		$tolinfo = $this->GetLottery(); 
		//检测此用户有无抽取过奖项
		$havecj = $this->CkHavecj($userid);
		 
		
		if($havecj){
			$statu='2002';
			$jxname = "您已抽过奖-".$havecj[0]['name'];
		}
		if($tolinfo && empty($havecj) && $phone){
			
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
			$zone = 4;
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
			$lotnum = $this->LotGoods($zone);
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
				$uplot = $this->UpdLotGoods($zone);
				if($uplot){ //发奖，减库存，执行成功
					$statu = '2004'; 
				}else{
					$statu = '2004';  //减库存失败 发奖成功
				}  
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
		return array('start'=>$statu,'msg'=>$jxname);
 
		//return Db::table('porttest')->where('name',$name)->find();
	}
	//获取奖项信息
	public function GetLottery(){
		return Db::name('lottery')->select();
	}
	//获取奖项库存
	public function LotGoods($id){
		$end = Db::name('lottery')->field('num')->where('id',$id)->select();
		if(isset($end[0]['num'])){
			return $end[0]['num'];
		}else{
			return 0;
		}
	}
	//通过userid，查询并获取手机号
	public function Ckgetphone($userid){
		$end = Db::name('user_dealer')->field('phone')->where('dealer_id',$userid)->select();
		if($end){
			return $end[0]['phone'];
		}else{
			return null;
		}
	}
	//更新奖项库存 
	public function UpdLotGoods($id){
		return Db::name('lottery')->where('id',$id)->setDec('num');
	}
	//给用户发奖 存储记录
	public function SendLotUser($data){
		return Db::name('lotuser')->insert($data);
	}
	//检测此用户有无抽过奖
	public function CkHavecj($userid){
		return Db::name('lotuser')->alias('a')->join('zt_lottery b','a.lotid=b.id')->field('a.id,a.lotid,b.name')->where('userid',$userid)->select();
	}
}