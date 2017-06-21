<?php

namespace app\admin\model;
use think\Model;
use think\Db;
use think\Cache;
use think\Session;
class ChebhModel extends Model
{ 
	 
 	protected $hktable = 'ydbad'; //信息回馈
 	protected $adtable = 'ydadtt'; //广告位点击监测
 	protected $chb = 'allcbhact'; //车百汇
 	protected $allcity = 'allcity'; //车百汇所有城市列表

 	//车百汇各城市活动信息更新
 	public function Upactinfo($data,$sta){
 		//return 1;
 		//更新具体活动信息先查询，有，则更新，无，则插入
 		$ck = Db::name($this->chb)->where('cityid',$data['cityid'])->select();
 		if(!empty($ck)){ 
 			$actend = Db::name($this->chb)->where('cityid',$data['cityid'])->update($data);
 		}else{
 			$actend = Db::name($this->chb)->insert($data);
 		}
 		$stuend = Db::name($this->allcity)->where('cityid',$data['cityid'])->setField('status',$sta['status']);
 		return 1;
 		//更新城市活动状态，直接更新

 	}

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
	//获取省份信息
	public function Gtproi(){
		return Db::name('allcity')->field('cityid,cityname,initial')->where('level','0')->order('initial')->select();
	}
	//获取省份下的城市信息
	public function Gtudcity($id){
		return Db::name('allcity')->field('cityid,cityname')->where('parentid',$id)->select();
	}
	//检测 id，名称是否存在
	public function Ckextid($id,$name){
		return Db::name('brand_cbh')->where('brand_id',$id)->select();
	}
	//通过cityid  查询cityname  如果有，返回名称，没有返回原值
    public function cynamebyid($cid){
	    return Db::name('allcity')->field('cityid,cityname')->where('cityid',$cid)->select();
    }
	//车百汇车型信息插入
	public function IntCarinfo($data){
		return Db::name('brand_cbh')->insert($data);
	}

	//插入车百汇车型数据
	public function Chbint($data,$name){
		if(empty($data)|| empty($name)){
			return null;
		}
		$have = Db::name($name)->where('brand_id',$data['brand_id'])->select();
		if($have){
			return null;
		}else{
			return Db::name($name)->insertGetId($data);
		} 
	}

	//获取车百汇注册数据
	public function getregcbh($fromid=1){
			//读取分页信息 
		if($fromid==3){
			return Db::name('cbh_reg')->alias("d")->field('d.dealer_id,d.name,d.phone,d.from,m.brand_name as bbbrand,d.car_time,c.brand_name,d.time,d.buy_car_time,d.localaddr,d.changeaddr')->join('zt_cbh_carserise c','d.car_series_id=c.brand_id','LEFT')->join('zt_cbh_brand m','d.brand=m.brand_id','LEFT')->order("d.dealer_id desc ")->paginate(); 
		}else{
			return Db::name('cbh_reg')->alias("d")->field('d.dealer_id,d.name,d.phone,d.from,m.brand_name as bbbrand,d.car_time,c.brand_name,d.time,d.buy_car_time,d.localaddr,d.changeaddr')->join('zt_cbh_carserise c','d.car_series_id=c.brand_id','LEFT')->join('zt_cbh_brand m','d.brand=m.brand_id','LEFT')->where('d.whreg',$fromid)->order("d.dealer_id desc ")->paginate(); 
		}
		 
 
	}

	//删除 车百汇 注册信息
	public function delcbhreg($pjid) { 
		return Db::name('cbh_reg')->delete($pjid);
	    
	}

	//通过手机号 查询车百汇注册信息
	public function regallcbh($phone){
		return Db::name('cbh_reg')->alias("d")->field('d.dealer_id,d.name,d.phone,d.accept,c.brand_name,d.car_series_id,d.time,a.timename,d.localaddr,d.changeaddr')
		->join('zt_cbh_carserise c','d.car_series_id=c.brand_id','LEFT')
		->join('zt_buytime a','d.buy_car_time=a.id')
		->where('d.phone',$phone)
		->select();
	}

	//通过手机号更新 用户奖品领取状态
	public function uplqbyphone($phone){
		$arr = array('accept'=>1);
		return Db::name('cbh_reg')->where('phone',$phone)->update($arr);
	}
	//通过手机号更新 用户奖品领取状态
	public function uplqbyph0($phone){
		$arr = array('accept'=>'0');
		return Db::name('cbh_reg')->where('phone',$phone)->update($arr);
	}
	//检测当前用户状态
	public function ckuplqbyph($phone){
		return Db::name('cbh_reg')->field('accept')->where('phone',$phone)->select();
	}


  




}