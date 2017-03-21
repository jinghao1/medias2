<?php
/**
 * 极客之家 高端PHP - 项目模块
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
use app\admin\model\ProjectModel;
use app\admin\model\BrandModel;
use app\admin\model\UserModel;
class Project extends	Base	
{	

	/**
	 * 项目列表
	 * @return [type] [description]
	 */
	public	function show()				
	{	
	 
		$project = new ProjectModel();	
		$user = new UserModel();	
		$data = $project->showAll();
		//检测删除操作
		//$auth = new \com\Auth();
		//$delpro = $auth->check('admin/project/pjdel',session('admin_uid'));
	 
		$delpro = $user->CkOptionuser('admin/project/pjdel');
		//echo Db::getlastsql();
		$this->assign('delopt',$delpro); //是否有删除权限，1有，2没有 true ,false
		$this->assign('data',$data);  
		return $this->fetch();
	}

	/**
	 * 项目添加
	 */
	public function add()
	{
		$brand = new BrandModel();
		$project = new ProjectModel();
		// $user = new ProjectModel($_POST);
		if(input('param.')){
			$data = input('param.');
			$data['brand'] = implode(",", $data['brand_id']);
			//$beginPeriod = str_replace("-","/",$data['beginPeriod']);
			//$endPeriod = str_replace("-","/",$data['endPeriod']);
			//$data['period'] = $beginPeriod.'-'.$endPeriod;
			$data['time'] = date("Y-m-d H:i:s");
			// p($endPeriod);
			 
			$res = $project->ProjectAdd($data);
			if($res){
				$this->success('添加成功','show');
			}
			else
			{
				$this->error('添加失败');
			}
		}
		else
		{
			$data = $brand->GetSelect();
			$this->assign('data',$data);
			return $this->fetch();
		}
	}

	//删除项目
	public function pjdel(){
		$delend = new ProjectModel();
		$paramstr =  Request::instance()->only('id');
		$id = empty($paramstr['id']) ? "" : $paramstr['id']; 
		
		
		if($id){
			//检测 如果当前项目下，有注册信息，不可删除
			$have =$delend->Ckhaveinfocur($id);
			if($have){
				$this->error('当前项目下有注册信息，请先删除注册信息');
			}
			$res = $delend->ProjectDel($id);
			if($res){
				$this->success('删除成功','show');
			}
			else
			{
				$this->error('删除失败');
			}
		}else{
			$this->error('无删除id');
		}
	}

	/**
	 * 查询品牌子级
	 */
	function GetBrandAdd()
	{
		$brand = new BrandModel();
		$brand_id = input('param.brand_id');
		$data = $brand->GetBrandAdd($brand_id);
		exit(json_encode($data));
	}

}
