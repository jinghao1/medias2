<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use	think\Request;
class DbjingcaiModel extends Model
{
	 // 设置当前模型对应的完整数据表名称
    protected $table = 'db_308_jc';
    protected $tables = 'project';
    protected $tabledb = 'db_308';

    /**
     * 项目添加
     * @param [type] $data [description]
     */
    function Itjingcai($price)
    { 
    	$data = array();
    	$data['price'] = $price;
    	$data['time'] = time();
    	$data['type'] = 1;
    	return Db::name($this->table)->insert($data);
    }
//统计目前模拟竞彩条数
     function Countjingcai($in)
    { 
    	 
    	return Db::name($this->table)->where('type',$in)->count();
    }
    //删除竞猜价格
    public function deldbjcprice($id,$uid){
	    $end = Db::name($this->table)->where('id',$id)->delete();
	    if($uid && $end){
		    $end = Db::name($this->tabledb)->where('dealer_id',$uid)->setInc('jcnum');
	    }
	    return $end;
    }
    
    
	//查询当前项目下有无注册信息
	function Ckhaveinfocur($proid){
		return Db::name('user_dealer')->field('dealer_id')->where('project_id',$proid)->limit(1)->select();
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
		    $strsql = "";
		    if(!empty($_POST['keybrand'])){
			    $strsql = " b.brand_name like '%".$_POST['keybrand']."%' ";
		    }
		    if(!empty($_POST['keyxm'])){
			    if($strsql){
				     $strsql .= " and p.project_name like '%".$_POST['keyxm']."%' ";
			    }else{
				     $strsql = "  p.project_name like '%".$_POST['keyxm']."%' ";
			    }
			   
		    }
		    if(!empty($_POST['pj_key'])){
			    if($strsql){
				    $strsql .= " and p.pj_id like '%".$_POST['pj_key']."%'";
			    }else{
				    $strsql = "  p.pj_id like '%".$_POST['pj_key']."%'";
			    }
			    
		    }
		    return Db::name($this->tables)->alias("p")->join('zt_brand b','p.brand=b.brand_id')->where($strsql)->order("id desc")->paginate(config('list_rows')); 
		    
	    }else{
		    return Db::name($this->tables)->alias("p")->join('zt_brand b','p.brand=b.brand_id')->order("id desc")->paginate(config('list_rows')); 
	    }
        
    }
    
    //所有竞猜信息查询
	public function getalljingc($fromid){

	    switch ($fromid){
            case 1: //真实
                return Db::name('db_308_jc')->alias('d')->field('d.price,d.time,c.name,c.phone,d.id,d.type,d.userid')->join('zt_db_308 c','d.userid=c.dealer_id','LEFT')->where('d.type',0)->order('d.time desc')->paginate();
                break;  
            case 2: //虚假
                 return Db::name('db_308_jc')->alias('d')->field('d.price,d.time,c.name,c.phone,d.id,d.type,d.userid')->join('zt_db_308 c','d.userid=c.dealer_id','LEFT')->where('d.type',1)->order('d.time desc')->paginate();
                break;  
            
            default:  //全部
                 return Db::name('db_308_jc')->alias('d')->field('d.price,d.time,c.name,c.phone,d.id,d.type,d.userid')->join('zt_db_308 c','d.userid=c.dealer_id','LEFT')->order('d.time desc')->paginate();
                break;  
        } 
	 
	}
   
 
}