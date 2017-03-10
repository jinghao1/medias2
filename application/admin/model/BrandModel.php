<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class BrandModel extends Model
{
	 // 设置当前模型对应的完整数据表名称
    protected $table = 'brand';

    /**
     * 读取全部数据
     */
    function GetSelectAll()
    {
        $data_list = Db::name($this->table)->select(); 
        return $this->RecursionAll($data_list);
    }
    
    /**
     * 读取品牌父级 pid=0
     */
    function GetSelect()
    {
    	return Db::name($this->table)->where('pid',0)->select();
    }

    /**
     * 调用子级品牌
     * @param [type] $id [description]
     */
    function GetBrandAdd($id)
    {
    	return Db::name($this->table)->where('pid',$id)->select();
    }

    /**
     * 递归查询所有品牌
     * @param  [type]  $node_list [description]
     * @param  integer $parent_id [description]
     * @param  integer $leave     [description]
     * @return [type]             [description]
     */
     function RecursionAll($data_list,$pid=0,$leave=0){
        static $result;
        foreach ($data_list as $key => $val) {
            if($val['pid']==$pid){
                $val['leave']=$leave;
                $result[]=$val;
                $this->RecursionAll($data_list,$val['brand_id'],$leave+1);
            }
        }
        //print_r($result);die;
        return $result;
     }

     

     /**
      * 添加品牌
      */
     public function BrandAdd($data)
     {
        return Db::name($this->table)->insert($data);
     }
     //删除车系品牌
     public function BrandCarDel($brand_id){ 
		return Db::name($this->table)->delete($brand_id);  
     }

     

     /**
      * 查询品牌名称
      * @param [type] $id [description]
      */
     public function BrandSelectName($id)
     {
        $res = DB::name($this->table)->field("brand_id,brand_name")->where('brand_id','in',$id)->select();
        foreach ($res as $key => $value) {
            $arr[] = $value['brand_name'];;
            $string = join(",",$arr);
        }
        return $string;
     }

     //递归删除车系子集
	public function DelUnderAll($id){
	 
		//判断此车系下有无其他子系，如果有，查出并返回
	    $hav = $this->BrandUnder($id);
	    $end[$id] = $this->BrandCarDel($id); //先把此级信息删除
	    if(!empty($hav)){ 
		    foreach($hav as $hv){ //循环子集并删除 
				$end[$hv] = $this->DelUnderAll($hv); 
		    }
	    }
	    if($end[$id]){
		    return 1;
	    }else{
		    return 0;
	    } 
	    
	}

     //查询此品牌车系下有无其他子信息，如果有返回
     public function BrandUnder($id){
	    $arr = null;
	    $res = DB::name($this->table)->field("brand_id")->where('pid',$id)->select();
	    if($res){
			foreach ($res as $key => $value) {
	            $arr[] = $value['brand_id'];;
	            
	        }
	    }
        
        return $arr;
     }

}