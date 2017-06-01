<?php  
namespace app\port\model;
use think\Model;
use think\Db;
class ChebHModel extends Model
{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'allcity';
    protected $tableact = 'allcbhact';
	protected $carcbh = 'brand_cbh';
    
    
    //获取省份下对应的所有城市信息
    public function UnderCity($id){
	    return Db::name($this->table)->alias('a')->join('zt_allcbhact b','a.cityid=b.cityid','LEFT')->field('a.cityid,a.cityname,a.status,b.begtime,b.endtime,b.address,b.fzrname,b.waiting')->where('a.parentid',$id)->select();
    }

    //获取所有展示城市
    public function Allopencity(){
        return Db::name($this->table)->alias('a')->join('zt_allcbhact b','a.cityid=b.cityid','LEFT')->field('a.cityid,a.cityname,a.initial,b.begtime,b.endtime,b.address,b.waiting')->where('a.status',1)->order('a.initial')->select();
    }
    //获取所有城市信息 id=>cityname

    //获取 车百汇 车型信息
    public function Undercar($pid){
        return Db::name($this->carcbh)->where('pid',$pid)->select();
    }

    //获取 车百汇 各层级数据 车型数据
    public function Udcar($pid,$name){

        return Db::name($name)->where('pid',$pid)->select();
    }

    //购车时间
    public function Chbcartime(){
        return Db::name('buytime')->select();
    }

    //车百汇监测手机号的唯一性
    public function Cbhunique($phone){
        $end = Db::name('cbh_reg')->field("dealer_id")->where("phone",$phone)->select();
        if($end){
            if($end[0]['dealer_id']){
                return 1; //存在
            }
        }
        return 2; //不存在
    }
    //车百汇信息注册录入
    public function CbhRegall($data){
         return DB::name('cbh_reg')->insertGetId($data);
        
    }

    //通过城市名称查询当前城市所有信息
    public function Actcityinfo($str){
        return Db::name($this->table)->alias('a')->join('zt_allcbhact b','a.cityid=b.cityid','LEFT')->field('a.cityid,a.cityname,a.status,b.begtime,b.endtime,b.address,b.fzrname,b.waiting')->where('a.cityname',$str)->where('a.status',1)->select();
    }

   	

	//车百汇项目 ，根据城市名称返回城市id
	public function rtcityidbyname($name){
		return Db::name('allcity')->field('cityid')->where('level',1)->where('cityname',$name)->select();
	}

	//通过车型名称，查询车型id
	public function Caridbycarname($name){
		return Db::name('cbh_carserise')->where('brand_name',$name)->select();
	}
	    
	
}
?>