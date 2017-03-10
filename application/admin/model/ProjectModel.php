<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use	think\Request;
class ProjectModel extends Model
{
	 // 设置当前模型对应的完整数据表名称
    protected $table = 'zt_project';
    protected $tables = 'project';

    /**
     * 项目添加
     * @param [type] $data [description]
     */
    function ProjectAdd($data)
    {
	     
    	//过滤字段添加
    	$user  = new ProjectModel;
    	return $user->data($data)->allowField(true)->save();
    }

    /**
     * 查询项目列表
     * @return [type] [description]
     */
    function showAll()
    {
	    $request = Request::instance(); 
		$param=$request->param(); 
	    if(!empty($_POST['keybrand']) || !empty($_POST['keyxm']) || !empty($_POST['pj_key']) ){
		    if(!empty($_POST['keybrand'])){
			    $strsql = " b.brand_name like '%".$_POST['keybrand']."%' ";
		    }
		    if(!empty($_POST['keyxm'])){
			    $strsql .= " and p.project_name like '%".$_POST['keyxm']."%' ";
		    }
		    if(!empty($_POST['pj_key'])){
			    $strsql = " p.pj_id like '%".$_POST['pj_key']."%'";
		    }
		    return Db::name($this->tables)->alias("p")->join('zt_brand b','p.brand=b.brand_id')->where($strsql)->order("id desc")->paginate(config('list_rows')); 
		    
	    }else{
		   return Db::name($this->tables)->alias("p")->join('zt_brand b','p.brand=b.brand_id')->order("id desc")->paginate(config('list_rows')); 
	    }
        
    }

    //项目删除
    public function ProjectDel($pjid=null){
	    if($pjid){
		    return Db::name($this->tables)->delete($pjid);
	    }
	    
    }

    /**
     * 查询项目名称
     */
    public function ProjectSelectName($id=null)
    {
	   	if($id){
		   	return DB::name($this->tables)->field("id,project_name")->where('id',$id)->select();
	   	}else{
		   	return DB::name($this->tables)->field("id,project_name")->select();
	   	}
        
    }

    //查询项目对应的车系信息
    public function ProjectUnderCar($proid){
	    $brand = DB::name($this->tables)->field("id,brand")->where('id',$proid)->select(); 
	    if($brand){
		    if($brand[0]['brand']){
			    $end = DB::name("brand")->field("brand_id,brand_name")->where('brand_id','in',$brand[0]['brand'])->select();
			    return array(0=>end($end)) ;
		    }
		    
	    }
    }
}