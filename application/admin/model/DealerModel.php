<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class DealerModel extends Model
{
	 // 设置当前模型对应的完整数据表名称
    protected $table = 'user_dealer';

    /**
     * 读取经销商列表
     */
    function SelectAll()
    {
    	return Db::name($this->table)
                        ->alias("d")
                        ->join('zt_car_series c','d.car_series_id=c.car_id')
                        ->join('zt_project p','d.project_id=p.id')
                        ->order("dealer_id desc")
                        ->paginate(); 
    }

    /**
     * 读取项目经销商列表分页
     */
    function ProjectDealerAll($id,$fromid=null)
    {
	   
	    if($fromid){
		    return Db::name($this->table)
                    ->alias("d")
                    ->join('zt_brand c','d.car_series_id=c.brand_id')
                    ->join('zt_project p','d.project_id=p.id')
                    ->where('d.project_id='.$id)
                    ->where('d.from',$fromid)
                    ->order("dealer_id desc ")
                    ->paginate(); 
	    }else{
			return Db::name($this->table)
                    ->alias("d")
                    ->join('zt_brand c','d.car_series_id=c.brand_id')
                    ->join('zt_project p','d.project_id=p.id')
                    ->where('d.project_id='.$id)
                    ->order("dealer_id desc ")
                    ->paginate();  
	    }
       
    }

	 

     /**
     * 添加用户注册信息
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function DealerAdd($data)
    {
	    //p($data);
        return DB::name("user_dealer")->insertGetId($data);
    }

    //更改用户预约注册信息
	public function DealerUPdate($data){ 
		return DB::name("user_dealer")->where("dealer_id",$data['dealer_id'])->update($data);
	}
    /**
     * 查询经销商 pid=0
     */
    public function SelectDealerPid()
    {
        return DB::name('dealer_list')->where("pid",0)->select();
    }

    /**
     * 查询经销商名称
     * @param [type] $id [description]
     */
    public function DealerSelectName($id)
    {
	    $string = "";
        $res = DB::name("dealer_list")->field("dealer_id,dealer_name")->where('dealer_id','in',$id)->select();
        foreach ($res as $key => $value) {
            $arr[] = $value['dealer_name'];;
            $string = join("-",$arr);
        }
        return $string;
    }
    //查询经销商信息 id=>name 
    public function DealerSelarr($id){
	    return DB::name("dealer_list")->field("dealer_id,dealer_name")->where('dealer_id','in',$id)->select();
    }

    //根据名称检测zt_dealer_list 经销商 表是否存在此信息，存在返回id，不存在返回null
    public function ExistDealer($name){
	    return DB::name('dealer_list')->field("dealer_id")->where('dealer_name',$name)->select();
    }

    //插入经销商信息
    public function InsertDealerInfo($info){
	    $data = ['dealer_name' => $info['dlname'], 'pid' => $info['pid'],'code'=>$info['dm'],'minname'=>$info['dlmime']];  
		return Db::name('dealer_list')->insertGetId($data); 
    }
      //插入经销商信息 dongbiao
    public function InsertDealerInfodb($info){
	    $data = ['dealer_name' => $info['dlname'], 'pid' => $info['pid']];  
		return Db::name('dealer_list')->insertGetId($data); 
    }

    /**
     * 根据项目对应的经销商,用户注册信息
     * @param [type] $id [description]
     */
    public function DealerIdList($id)
    {
        return DB::name('user_dealer')->where("dealer_id",$id)->select();
    }

     //删除某项目下用户注册信息
    public function ProUserDel($pjid){
	   
		return Db::name($this->table)->delete($pjid);
    }

    //通过dealer_id 获取子信息 接口
    
	public function childinfo($id){
		return Db::name("dealer_list")->where("pid",$id)->select();
	}
    //验证手机号唯一性
    public function checkphoneunique($phone,$proid=null){
	    if($proid){
		    $end = Db::name($this->table)->field("dealer_id")->where("phone",$phone)->where('project_id',$proid)->select();
	    }else{
		    $end = Db::name($this->table)->field("dealer_id")->where("phone",$phone)->select();
	    }
	    
	    if($end){
		    if($end[0]['dealer_id']){
			    return 1;
		    }
	    }
	    return 2;
    }

}