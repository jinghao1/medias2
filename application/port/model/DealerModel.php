<?php
namespace app\port\model;
use think\Model;
use think\Db;
class DealerModel extends Model
{
	 // 设置当前模型对应的完整数据表名称
    protected $table = 'user_dealer';
    protected $tablebw = 'bwuser';
    protected $tableall = 'allpro';

    /**
     * 读取经销商列表
     */
    function GetTablepro($proid)
    {
    	return Db::name($this->tableall)->where('proid',$proid)->select();
    	 
    } 

     /**
     * 添加用户注册信息
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function DealerAddNew($data)
    {
	    //通过项目id，查询项目，追加到对应注册表单
	    $end = $this->GetTablepro($data['project_id']);
	    //p($data);
	    if(!empty($end[0]['reginfo'])){
		    return DB::name($end[0]['reginfo'])->insertGetId($data);
	    }
        
    }

    //更改用户预约注册信息
	public function DealerUPdate($data){ 
		return DB::name("user_dealer")->where("dealer_id",$data['dealer_id'])->update($data);
	}
    /**
     * 查询经销商 pid=0
     //$proid 为项目id,$pid为0，返回省份信息，大于0为省份id，根据此id，查询所有子集信息
     */
    public function StDealerPid($proid,$pid=0)
    {
	    $tab =$this->GetTablepro($proid); //获取项目对应表名
	    if(!empty($tab[0]['dealname'])){
			if($pid==0){ //只返回一级，
			    $end = DB::name($tab[0]['dealname'])->where("pid",0)->select();
			    return $end;
		    }else{ //$pid 为省份id
			    $end = DB::name($tab[0]['dealname'])->where("pid",$pid)->select();
			    //return $end;
			    $all = array();
			   // if($end){  //查询子信息
				  //  foreach($end as $key=>$val){
					 //   $arr[$key] = $val['dealer_id'];
				  //  }
				  //  if($arr){
						//$arrstr = implode(",",$arr);
					 //   $endun = DB::name($tab[0]['dealname'])->where("pid","in",$arrstr)->select();
					 //   if($endun){
						//    foreach($endun as $k=>$vm){
						//	    $all[$vm['pid']] = $vm['dealer_name'];
						//    }
					 //   }
				  //  }   
			   // }
			    return array("city"=>$end,"deal"=>$all);
			    
		    }
	    }
	   
        
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
	    //通过项目id获对应的表明
	    $end = array();
	    if($proid){
		    $tableinfo = $this->GetTablepro($proid);
		    if(!empty($tableinfo[0]['reginfo'])){
			    $end = Db::name($tableinfo[0]['reginfo'])->field("dealer_id")->where("phone",$phone)->select();
		    } 
	    }  
	    if($end){
		    if($end[0]['dealer_id']){
			    return 1;
		    }
	    }
	    return 2;
    }
   

}