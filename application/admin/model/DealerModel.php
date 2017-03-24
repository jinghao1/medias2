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
    function ProjectDealerAll($id,$fromid=1)
    {
	    //通过项目id，查询项目对应的注册信息表
	    $tbinfo = Db::name("allpro")->where('proid',$id)->select();
	    $arrwh = array('d.project_id'=>$id);
		if($fromid && $fromid!=3){
			$arrwh['d.whreg'] = $fromid;
		}
		if(!empty($tbinfo[0]['reginfo'])){ 
			 
	    	return Db::name($tbinfo[0]['reginfo'])
                    ->alias("d")->field('d.dealer_id,d.project_id,d.name,d.sex,d.phone,d.car_time,d.dealer_name,d.car_series_id,d.time,d.buy_car_time,n.name as lotname')
                    ->join('zt_brand c','d.car_series_id=c.brand_id','LEFT')
                    ->join('zt_project p','d.project_id=p.id','LEFT')
                    ->join('zt_lotuser m','m.userid=d.dealer_id','LEFT')
                    ->join('zt_lottery n','m.lotid=n.id','LEFT')
                    ->where($arrwh) 
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
	    $tbinfo = Db::name("allpro")->where('proid',$data['project_id'])->select(); 
        return DB::name($tbinfo[0]['reginfo'])->insertGetId($data);
    }

    //更改用户预约注册信息
	public function DealerUPdate($data){ 
		// 根据project_id 选择数据表
		$tbinfo = Db::name("allpro")->where('proid',$data['project_id'])->select(); 
		return DB::name($tbinfo[0]['reginfo'])->where("dealer_id",$data['dealer_id'])->update($data);
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
    public function DealerSelectName($id,$proid=0)
    {
	    $string = "";
	    $tbinfo = Db::name('allpro')->where('proid',$proid)->select();
	    if(!empty($tbinfo[0]['dealname'])){
			$res = DB::name($tbinfo[0]['dealname'])->field("dealer_id,dealer_name")->where('dealer_id','in',$id)->select();
	        foreach ($res as $key => $value) {
	            $arr[] = $value['dealer_name'];;
	            $string = join("-",$arr);
	        }
	    }
       
        return $string;
    }
    //查询经销商信息 id=>name 
    public function DealerSelarr($proid,$id){
	    $tbinfo = Db::name('allpro')->where('proid',$proid)->select();
	    if(!empty($tbinfo[0]['dealname'])){
		    return DB::name($tbinfo[0]['dealname'])->field("dealer_id,dealer_name")->where('dealer_id','in',$id)->select();
	    }
	    
    }

    //根据名称检测zt_dealer_list 经销商 表是否存在此信息，存在返回id，不存在返回null
    public function ExistDealer($name){
	    return DB::name('dealer_list')->field("dealer_id")->where('dealer_name',$name)->select();
    }

    //检测宝沃经销商是否存在
    public function exDealerBw($name){
	    return DB::name('bw_dealer')->field("dealer_id")->where('dealer_name',$name)->select();
    }
	//插入经销商信息 宝沃
    public function ItDealerInfoBW($info){
	    $data = ['dealer_name' => $info['dlname'], 'pid' => $info['pid'],'minname'=>$info['minname'],'addr'=>$info['addr'],'number'=>$info['number']];  		
		$end= Db::name('bw_dealer')->insertGetId($data); 
		//$last = Db::name('bw_dealer')->getLastSql();
		//var_dump($last);
		return $end;
		//exit;
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
    public function DealerIdList($proid,$id)
    {
	    $end = Db::name('allpro')->where('proid',$proid)->select();
	    if($end[0]['reginfo']){
		    return DB::name($end[0]['reginfo'])->where("dealer_id",$id)->select();
	    }
        
    }

     //删除某项目下用户注册信息
    public function ProUserDel($pjid,$proid=0){
	   	$end = Db::name('allpro')->where('proid',$proid)->select();
	    if($end[0]['reginfo']){
		    return Db::name($end[0]['reginfo'])->delete($pjid);
	    }
		
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
    //获取所有奖项信息
    function LotteryAll(){
	    return Db::name("lottery")->select();
    }

}