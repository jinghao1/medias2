<?php
/**
 * 极客之家 高端PHP - 用户模块
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\model;
use think\Model;
use think\DB;
use think\Session;
class UserModel extends Model
{
	 // 设置当前模型对应的完整数据表名称
    protected $table = 'table';
	//查询用户信息  有id为单个信息查询，无id查询所有的信息
    public function UserAll($userid=null)
    {
		if($userid){
			return Db::name("admin")->alias("a")->join('zt_auth_group g','a.groupid=g.id')->where('a.user_id',$userid)->select();
		}else{
			return Db::name("admin")->alias("a")->join('zt_auth_group g','a.groupid=g.id')->order("user_id desc")->select();
		}
    	
    }

    /**
     * 查询所有角色
     */
    function GroupSelect()
    {
    	return Db::name("auth_group")->select();
    }

    /**
     * 添加用户
     * @param [type] $data [description]
     */
    public function UserAdd($data)
    {
    	Db::name('admin')->insert($data);
        $userId = Db::name('admin')->getLastInsID();
        if($userId > 0){ 
            return Db::name('auth_user_group')->insert(array("uid"=>$userId,"group_id"=>$data['groupid']));
        }
    }

    //更改用户
    public function UpdateUserinfo($data){
	    if($data['userid']){ 
	    	$userid = $data['userid'];
	    	unset($data['userid']);
	    	unset($data['edit']);
			$end = DB::name("admin")->where("user_id",$userid)->update($data);
			return $end;
	        if($end){//目前认为 auth_user_group 无用
		        return Db::name('auth_user_group')->where('uid',$data['groupid'])->update(array("uid"=>$data['userid'],"group_id"=>$data['groupid'])); 
	        } 
	    }
	   
    }
        
    /**
     * 查询所有菜单功能
     */
    public function MenuListAll($id=null)
    {
	    if($id){
		    return DB::name("auth_rule")->select($id);
	    }else{
		    return DB::name("auth_rule")->select();
	    }
       
        // return $this->RecursionAll($data_list);
    }

    /**
     * 添加菜单 节点
     */
    public function MenuAdd($data)
    {
        return DB::name("auth_rule")->insert($data);
    }
    //更改菜单节点
    public function Menuedit($data){
	    $id = $data['edit'];
	    unset($data['edit']);
	    return Db::name('auth_rule')->where('id',$id)->update($data);
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
                $this->RecursionAll($data_list,$val['id'],$leave+1);
            }
        }
        //print_r($result);die;
        return $result;
     }

     /**
      * 修改角色权限
      * @param [type] $group_id [description]
      * @param [type] $rule_id  [description]
      */
     public function GroupAllot($group_id,$rule_id)
     {
        return DB::name("auth_group")->where("id",$group_id)->update(['rules'=>$rule_id,'update_time'=>time()]);
     }

     /**
      * 角色添加
      */
     public function GroupAdd($data)
     {
        return Db::name('auth_group')->insert($data);
     }

     //角色删除
     public function Delgroupuser($id){
	    return Db::name('auth_group')->delete($id);
     }

     //

     /**
      * 查询所有权限
      */
     function GroupAllData()
     {
        $data = Db::name("auth_rule")->where("pid",0)->select();
        foreach ($data as $key => $val) {
            $data[$key]['data_list'] = DB::name("auth_rule")->where("pid",$val['id'])->select();
            foreach ($data[$key]['data_list'] as $kk => $vv) {
                $data[$key]['data_list'][$kk]['data_lists'] = DB::name("auth_rule")->where("pid",$vv['id'])->select();
                // if($data[$key]['data_list'][$kk]['data_lists'])
            }
        }
        return $data;
     }

     //删除用户信息
     function DelUserinfo($userid){
	     return Db::name('admin')->delete($userid);
     }

     //根据角色删除用户信息
     public function Deluserbygroupid($pid){
	    return Db::name('admin')->where("groupid",$pid)->delete();
     }
     //根据用户角色，返回角色所拥有权限
     public function UserAuth($pid){ 
	     return Db::name('auth_group')->where('id',$pid)->select();
     }
	//根据用户id，返回用户角色所拥有的权限
	public function HisRule($uid){
		return Db::name('admin')->alias('a')->join('zt_auth_group b','a.groupid=b.id','LEFT')->field('a.user_id,b.rules')->where('a.user_id',$uid)->select();
	}

	//根据规则查询所有菜单项
	public function showmenu($ruleid=null){
		$menuarr = array(); //顶级下对应次级菜单
		$firmenu = array(); //顶级菜单
		if($ruleid){
			//p($ruleid);
			//获取顶级菜单
			$first = Db::name('auth_rule')->field('id,name,title')->where('id','in',$ruleid)->where('pid',0)->order("sort")->select();
			if($first){  
				foreach($first as $allv){ //根据顶级获取二级菜单 
					//$firmenu[$allv['title']] = $allv['name'];
					$second = Db::name('auth_rule')->field('id,name,title')->where('id','in',$ruleid)->where('pid',$allv['id'])->order("sort")->select();
					if($second){
						foreach($second as $seval){  //赋值
							$menuarr[$allv['title']][$seval['name']] = $seval['title'];
						}
					} 
				}
			}
		}else{
			 
			//获取顶级菜单
			$first = Db::name('auth_rule')->field('id,name,title')->where('pid',0)->order("sort")->select();
			if($first){  
				foreach($first as $allv){ //根据顶级获取二级菜单 
					//$firmenu[$allv['title']] = $allv['name'];
					$second = Db::name('auth_rule')->field('id,name,title')->where('pid',$allv['id'])->order("sort")->select();
					if($second){
						foreach($second as $seval){  //赋值
							$menuarr[$allv['title']][$seval['name']] = $seval['title'];
						}
					} 
				}
			}
		}
		return $menuarr;
		//return array('1'=>$firmenu,'2'=>$menuarr);
		
	}

	//单独检测用户对某个操作有无权限 此检测， 暂时未调用
	public function CkOptionuser($option=null){
		$userid = session::get("admin_uid");
		if($option && $userid){ //联表查询 规则信息，是否存在
			//首先判断规则是否存在，不存在为超级管理员，拥有所有权限
			$therule = Db::name('admin')->alias('a')->join('zt_auth_group b','b.id=a.groupid')->where('a.user_id',$userid)->field('rules')->select();
			
			if(isset($therule[0]['rules']) && !empty($therule[0]['rules'])){
				$end = Db::name('auth_rule')->field('id')->where('id','in',$therule[0]['rules'])->where('name',$option)->select(); 
				 
				
				if($end){
					return true; //有权限
				} 
			}else{
				return true;
			} 
		}
		return false;
	}

	public function ckuniquename($username){
		return Db::name('admin')->where('username',$username)->select();
	}
     
     
}