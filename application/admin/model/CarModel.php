<?php
/**
 * 极客之家 高端PHP - 车系 车型模model
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\model;
use think\Model;
use think\Db;
class CarModel extends Model
{
	 // 设置当前模型对应的完整数据表名称
    protected $table = 'car_series';
    protected $tablebd = 'brand';
	
    /*
     * 读取车系车型父级
     */
    function GetSelectOne()
    {
    	return Db::name($this->tablebd)->where('pid',0)->select();
    }

    /**
     * 调用 车系车型 子级
     * @param [type] $id [description]
     */
    function CarClass($id)
    {
    	return Db::name($this->tablebd)->where('pid',$id)->select();
    }

    /**
     * 调用 经销商 子级
     * @param [type] $id [description]
     */
    function DealerClass($id,$proid=0)
    {
	    $tabinfo = Db::name("allpro")->where('proid',$proid)->select();
	    if($tabinfo[0]['dealname']){
		    return Db::name($tabinfo[0]['dealname'])->where('pid',$id)->select();
	    }
        
    }

    /**
     * 查询车系车型 名称
     * @param [type] $id [description]
     */
    function CarSelectName($id)
    {
	    $string = "";
        $res = DB::name($this->tablebd)->field("brand_id,brand_name")->where('brand_id','in',$id)->select();
        //echo DB::getLastSql(); //打印上一条sql语句
        foreach ($res as $key => $value) {
            $arr[] = $value['brand_name'];;
            $string = join(",",$arr);
        }
        return $string;
    }

    //根据车系id信息，查询车系id=>name数组
    function CarSelectnmobj($id){
	    return DB::name($this->tablebd)->field("brand_id,brand_name")->where('brand_id','in',$id)->select();
    }

    //返回购车时间
    public function BuycarTime($id=null){
	    if($id){
		    return DB::name("buytime")->field("id,timename")->where('id',$id)->select();
	    }else{
		    return  DB::name("buytime")->field("id,timename")->select();
	    }
	    
    }


}