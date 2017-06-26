<?php

namespace app\port\model;
use think\Model;
use think\Db;
use think\Cache;
class HkdataModel extends Model
{ 
	 
 	protected $hktable = 'ydbad'; //信息回馈
 	protected $adtable = 'ydadtt'; //广告位点击监测
 	protected $tableall = 'allpro'; //项目汇总表
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

		//宝沃bx7 直播数据  插入
	public function Bwupdirect($data){
		$end = Db::name('system')->where('id',1)->update($data);
		return $end;
	}
	//返回bx7 直播视频状态
	public function Bwrtvideostat($id=1){
		$end = Db::name("system")->where('id',$id)->find();
		return $end;
	}

	  /**
     * 通过项目id,获取项目注册信息，经销商列表读取经销商列表
     */
    function GetTablebyproid($proid)
    {
    	return Db::name($this->tableall)->where('proid',$proid)->find();
    	 
    } 

	  //通过项目id，手机号，获取用户注册id,竞猜次数
    public function Rtuidbyphone($phone,$proid=null){
	    //通过项目id获对应的表明
	    $end = array();
	    if($proid){
		    $tableinfo = $this->GetTablebyproid($proid);
		    if(!empty($tableinfo['reginfo'])){
			    $end = Db::name($tableinfo['reginfo'])->field("dealer_id,jcnum")->where("phone",$phone)->find();
		    } 
	    }   
		return $end; 
    }

    //插入竞猜信息  东标308 
    public function  Itjcinfo($arr){
	    return Db::name('db_308_jc')->insertGetId($arr);
    }
    //更新 用户竞猜次数 东标308
    public function Upusernum($userid){
	    return Db::name('db_308')->where('dealer_id',$userid)->setDec('jcnum');
    }
    //获取竞猜价格，数量
    public function Getnumaver($minpric,$maxprice){
	    $minpric = $minpric*10000;
	    $maxprice = $maxprice*10000;
	    return Db::name('db_308_jc')->where('price>='.$minpric)->where('price<='.$maxprice)->count('id');
    }
    //获取竞猜最小价格
    public function Getminpric(){
	    return Db::name('db_308_jc')->min('price');
    }
     //获取竞猜最大价格
    public function Getmaxpric(){
	    return Db::name('db_308_jc')->max('price');
    }
}