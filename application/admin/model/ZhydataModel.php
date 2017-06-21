<?php
//车百汇智慧云对接数据查询
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Cache;
use think\Session;
class ZhydataModel extends Model
{ 
	protected $basic = 'cbh_carbasic'; //车百汇
	protected $city = 'allcity'; //汽车大全，全部城市信息
	//通过车系id取基本车型 默认选择第一个
	public function Basicbyseriseid($pid){
		$ck = Db::name($this->basic)->where('pid',$pid)->order('brand_id desc')->find();
		return $ck;
	}
	//通过城市名称，获取城市id
	public function Getidbycityname($name){
		$ck = Db::name($this->city)->field('cityid')->where('cityname',$name)->find();
		return $ck;
	}


}

?>