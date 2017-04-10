<?php

namespace app\port\model;
use think\Model;
use think\Db;
use think\Cache;
class LotcirModel extends Model
{ 
	protected $circletable = "cjcircle";
	protected $circleu = "cjcirregu"; 
	protected $circlecj = "cjciruser"; 
	//抽奖方法 
	function HavChance($phone,$self=null){ 
		$statu = '1001';
		$jxname = '数据传入有误';
		//获取奖项
		
		$tolinfo = $this->GetLottery();  
		if($tolinfo && $phone){ 
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
			$curtime = time();
			//通过手机号获取用户id
			$userid = $this->Ckgetphoneid($phone);
			$userinfo = array(
					'userid'=>$userid, //用户id 
					'lotid'=>$zone, //奖项id
					'mktime'=>$curtime //抽奖时间
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
				//减少用户抽奖次数
				
				//减库存
				$uplot = $this->UpdLotGoods($zone);
				if($zone!=4){ //认为是 再来一次奖项  不减库存
					$minend = $this->Subnumofphone($phone);
				} 
			 
				$statu = '2004'; //减库存失败 发奖成功 
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
	//获取当前手机号可抽奖次数
	public function Phonum($phone){
		$end = Db::name($this->circleu)->field('cjnum')->where('phone',$phone)->select();
		return $end;
	}
	//减少当前抽奖用户 抽奖次数
	public function Subnumofphone($phone){
		$end = Db::name($this->circleu)->where('phone',$phone)->setDec('cjnum');
		return $end; 
	}
	//获取奖项信息
	public function GetLottery(){
		return Db::name($this->circletable)->select();
	}
	//获取奖项库存
	public function LotGoods($id){
		$end = Db::name($this->circletable)->field('num')->where('id',$id)->select();
		if(isset($end[0]['num'])){
			return $end[0]['num'];
		}else{
			return 0;
		}
	}
	//通过手机号，查询并获取用户id
	public function Ckgetphoneid($phone){
		$end = Db::name('user_dealer')->field('dealer_id')->where('phone',$phone)->select();
		if($end){
			return $end[0]['dealer_id'];
		}else{
			return null;
		}
	}
	//更新奖项库存 
	public function UpdLotGoods($id){
		return Db::name($this->circletable)->where('id',$id)->setDec('num');
	}
	//给用户发奖 存储记录
	public function SendLotUser($data){
		return Db::name($this->circlecj)->insert($data);
	}
	//检测此用户有无抽过奖
	public function CkHavecj($userid){
		return Db::name('lotuser')->alias('a')->join('zt_lottery b','a.lotid=b.id')->field('a.id,a.lotid,b.name')->where('userid',$userid)->select();
	}
}