<?php
/**
 * 极客之家 高端PHP - 用户模块
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
use app\admin\model\UserModel;
class User extends	Base	
{	
	/**
	 * 用户列表
	 */
	public	function UserList()				
	{	
		$user = new UserModel();
		$UserData = $user->UserAll();
		$this->assign('UserData',$UserData);								
		return $this->fetch();
	}

	/**
	 * 用户添加
	 */
	 public	function UserAdd()				
	{	
		$user = new UserModel();
		$userinfo[0] = array('groupid'=>"");  //定义空数组
		$edit = 0;
		if(input('param.user_id') && (input('param.edit')==1 || input('param.edit')==3)){ //用户信息修改 
		 	$edit = input('param.edit');
			$userinfo = $user->UserAll(input('param.user_id'));//用户id 
		}else if(input('param.edit')==2 && input('param.userid')){
			$data = input('param.');
			$data['password'] = MD5(md5($data['password']).config('auth_key'));
			$data['time'] = time();
			$res = $user->UpdateUserinfo($data);
			if($res){
				$this->success('更改成功');
			}
			else
			{
				$this->error('更改失败');
			}	
		}else if(input('param.')){
			$data = input('param.');
			$data['password'] = MD5(md5($data['password']).config('auth_key'));
			$data['time'] = time();
			$res = $user->UserAdd($data);
			if($res){
				$this->success('添加成功','UserList');
			}
			else
			{
				$this->error('添加失败');
			}
		} 
		//查询角色
		$GroupData = $user->GroupSelect();	
		$this->assign('GroupData',$GroupData);
		$this->assign('edit',$edit);
		$this->assign('userinfo',$userinfo[0]);
		return $this->fetch();
	}

	/**
	 * 角色列表
	 */
	public function GroupList()
	{
		$user = new UserModel();
		$res = $user->GroupSelect();
		// p($res);
		// $GroupData = $user->GroupSelect();
		$GroupArrayData = $user->GroupAllData();

		$GroupData = array();
		foreach ($res as $key=>$val) {
			$GroupData[$key]['id'] = $val['id'];
			$GroupData[$key]['title'] = $val['title'];
			$GroupData[$key]['status'] = $val['status'];
			$GroupData[$key]['create_time'] = date("Y-m-d H:i:s",$val['create_time']);
			$GroupData[$key]['update_time'] = date("Y-m-d H:i:s",$val['update_time']);
		}
		$this->assign('GroupData',$GroupData);								
		$this->assign('GroupArrayData',$GroupArrayData);								
		return $this->fetch();
	}

	/**
	 * 角色添加
	 */
	public function GroupAdd()
	{
		$user = new UserModel();
		if(input('param.'))
		{
			$data = input('param.');
			$data['create_time'] = time();
			$res = $user->GroupAdd($data);
			if($res){
				$this->success('添加成功','GroupList');
			}
			else
			{
				$this->error('添加失败');
			}
		}
		else
		{
			return $this->fetch();
		}
	}

	//根据用户角色，返回其所有的操作权限
	public function Haveauth(){
	 
		$groupid = input('param.groupid');
		
		$user = new UserModel();
		
		if($groupid){
			$end = $user->UserAuth($groupid);
			if($end){
				foreach($end as $val){
					 
					if(!empty($val['rules'])){
						$arr = explode(",",$val['rules']);
						return json_encode($arr);
					} 
					 
				}
			} 
			
		} 
		return json_encode(1); 
		
		
	}

	/**
	 * 查询所有权限菜单
	 *  ajax请求
	 */
	public function MenuList()
	{
		$user = new UserModel();
		$nav = new \org\Leftnav;
		$admin_rule = $user->MenuListAll();
		$arr = $nav::rule($admin_rule);	
		exit(json_encode($arr));
	}
	
	/**
	 * 查询所有权限菜单
	 */
	public function GetMenuList()
	{
		//p("song");
		//return $this->fetch();
		$user = new UserModel();
		$nav = new \org\Leftnav;
		
		$admin_rule = $user->MenuListAll();
		
		$arr = $nav::rule($admin_rule);	
		//p($arr);
		$this->assign("MenuData",$arr);
		
		return $this->fetch();
	}

	/**
	 * 添加菜单权限
	 */
	public function MenuAdd()
	{
		$user = new UserModel();
		if(input('param.'))
		{
			$data = input('param.');
			$data['create_time'] = time();
			$res = $user->MenuAdd($data);
			if($res){
				$this->success('添加成功','GetMenuList');
			}
			else
			{
				$this->error('添加失败');
			}
		}
		else
		{
			$nav = new \org\Leftnav;
			$admin_rule = $user->MenuListAll();
			$arr = $nav::rule($admin_rule);	
			$this->assign("MenuData",$arr);
			return $this->fetch();
		}
	}

	//删除用户
	public function Deluser(){
		$user = new UserModel();
		$end = null;
		if(input('param.del') && input('param.user_id')){
			$end = $user->DelUserinfo(input('param.user_id'));
			
		}
		if($end){
			$this->success('删除成功','UserList');
		}else{
			$this->error('删除失败');
		}
	}

	/**
	 * 角色分配权限
	 */
	public function GroupAllot()
	{
		// echo 111;die;
		$user = new UserModel();
		$rule_id = input('param.id/s');
		$group_id = input('param.group_id/d');
		// echo $rule_id;"<br>";
		// echo $group_id;die;
		$res = $user->GroupAllot($group_id,$rule_id);
		if($res > 0){
			$result = array('code'=>1,'data'=>'权限分配成功');
			exit(json_encode($result));
		}
		else
		{
			$result = array('code'=>0,'data'=>'权限分配失败');
			exit(json_encode($result));
		}
	}

	//角色权限更改
	public function Updategroupauth(){
		$user = new UserModel();
		$data = input('param.');
		$end = null;
		if(!empty($data['box'])&&$data['groupid']){
			$strbox = implode(",",$data['box']);
			$end = $user->GroupAllot($data['groupid'],$strbox);
		}else if(empty($data['box']) && $data['groupid']){
			$end = $user->GroupAllot($data['groupid'],'');
		}
		if($end){
			$this->success('更新成功');
		}else{
			$this->error('更新失败');
		} 
	}
	//删除角色
	public function Groupuserdel(){
		$guid = input('param.guid');
		if($guid){
			$user = new UserModel(); 
			$deluserg = $user->Delgroupuser($guid);
			$deluser = $user->Deluserbygroupid($guid);
			if($deluserg){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}
		}
	}
}
