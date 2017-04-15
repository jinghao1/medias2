<?php

namespace app\port\model;
use think\Model;
use think\Db;
use think\Cache;
class HkdataModel extends Model
{ 
	 
 	protected $hktable = 'ydbad'; //信息回馈
 	protected $adtable = 'ydadtt'; //广告位点击监测
	//将数据插入
	public function IntHk($data){
		$end = Db::name($this->hktable)->insert($data);
		return $end;
	}
	//检测值是否存在
	public function Etid($vid){
		return Db::name($this->hktable)->where('artid',$vid)->count();
	}
	//自增1
	public function Autoadd($vid,$pan){
		return Db::name($this->hktable)->where('artid',$vid)->setInc($pan);
	}
 	//数据更新
	public function EditHk($tid,$con){
		$end = Db::name($this->hktable)->where('artid',$tid)->update($con);
		return $end;
	} 

 	//广告位点击检测
 	public function Ethead($data){
	 	return Db::name($this->adtable)->where($data)->count();
 	}
 		//数据更新
	public function EditAd($wharr){
		$curtime = time(); //当前时间
		$con = array('time'=>$curtime);
		$end = Db::name($this->adtable)->where($wharr)->setInc('num');
		if($end){
			$end = Db::name($this->adtable)->where($wharr)->update($con);
		}
		
		return $end;
	}

	//数据首次插入
	public function IntAd($data){
		$end = Db::name($this->adtable)->insert($data);
		return $end;
	}
}