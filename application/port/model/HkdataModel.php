<?php

namespace app\port\model;
use think\Model;
use think\Db;
use think\Cache;
class HkdataModel extends Model
{ 
	 
 	protected $hktable = 'ydbad';
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
	public function IntHk($data){
		$end = Db::name($this->hktable)->insert($data);
		return $end;
	}
 
 
}