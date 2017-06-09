<?php
namespace app\admin\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
use think\Session;
use app\admin\model\UserModel;
//use think\auth\Auth;
class Index extends	Base	
{				
	public	function index()				
	{			
		$UserId = session::get("admin_uid");
		//通过userid,查询所在用户组，判断跳转
		$ck = new UserModel();
		$userinfo = $ck->UserAll($UserId);
		if($userinfo[0]['groupid']==14){
			$this->redirect('Chebaihui/gtphone');
		} 
		return $this->fetch();
	} 

	public	function top()				
	{	
		$UserName = session::get("admin_username");
		$UserId = session::get("admin_uid");
		 
		$this->assign("UserName",$UserName);
		$this->assign("UserId",$UserId);
		return $this->fetch();
	}

	public	function menu()				
	{		
		$UserName = session::get("admin_username");
		$UserId = session::get("admin_uid");
		//获取用户组，对应的权限
		if($UserId){
			$user = new UserModel();
			$rules = $user->HisRule($UserId);	
			//p($rules);
			if(isset($rules[0]['rules'])){
				$menus = new UserModel();
				if(empty($rules[0]['rules'])){
					//超级管理员，权限都有
					$allmenu = $menus->showmenu($rules[0]['rules']); 
				}else{ //根据权限查询菜单项
					$allmenu = $menus->showmenu($rules[0]['rules']); 
					
				} 
				$this->assign("menuarr",$allmenu);
			}else{
				//没有此用户角色
			}
		}
			
		// 获取auth实例
		//$auth = Auth::instance();	
		//获取用户id			
		return $this->fetch();
	}

	public	function main()				
	{								
		return $this->fetch();
	}

}
