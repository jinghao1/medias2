<?php  
namespace app\admin\model;
use think\Model;
use think\Db;
class CityaModel extends Model
{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'allcity';
    protected $tableact = 'allcbhact';
	
    /*
     * 读取车系车型父级
     */
    function Alllist()
    {
    	return Db::name($this->table)->field('cityid,cityname,initial')->where('level','0')->order('initial')->select();
    }
    //获取省份下对应的所有城市信息
    public function UnderCity($id){
	    return Db::name($this->table)->alias('a')->join('zt_allcbhact b','a.cityid=b.cityid','LEFT')->field('a.cityid,a.cityname,a.status,b.begtime,b.endtime,b.address,b.fzrname,b.waiting')->where('a.parentid',$id)->select();
    }

    //获取城市活动信息
    public function Cityact($id){
        return Db::name($this->tableact)->where('cityid',$id)->select();
    }
	    
	
}
?>