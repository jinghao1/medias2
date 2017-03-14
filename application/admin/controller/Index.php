<?php
namespace app\admin\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
use think\Session;
//use think\auth\Auth;
class Index extends	Base	
{				
	public	function index()				
	{								
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
