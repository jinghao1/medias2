<?php
namespace app\port\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
//use	think\File;
use app\port\model\LoginModel;
use app\port\model\HkdataModel;
class Songtest extends	Controller	
{	
	public function test()
	{
		 
		return $this->fetch('');
	}

	/**
	 * 用户注册
	 * @return [type] [description]
	 */
	public	function login()				
	{
		$time = time();								
		$data = input('param.');
		$data['time'] = $time;
		unset($data['key']);
		$KeyServer = MD5($data['name'].$data['password'].$time);
		if($KeyServer != $KeyServer){
			$result = array('code'=>1001,'data'=>"key值验证失败");
			exit(json_encode($result));
		}
		//注册入库
		$LoginModel = new LoginModel();
		$UserOne = $LoginModel->SelectOne($data['name']);

		if($UserOne){
			$result = array('code'=>1004,'data'=>"用户名已经存在");
			exit(json_encode($result));
		}

		$res = $LoginModel->UserAdd($data);
		if($res)
		{
			$result = array('code'=>1000,'data'=>"注册成功");
			exit(json_encode($result));
		}
		else
		{
			$result = array('code'=>1003,'data'=>"注册失败");
			exit(json_encode($result));
		}
	}

	 public	function GetQuit()				
	{								
		return $this->fetch('login');
	}

	public function Bwup(){
	  
		$all = input('param.');
		
		if(isset($all['id'])){
			//exit("song");
			$ck = new HkdataModel();
			//exit("song");
			$files = request()->file('image');
			$filesmb = request()->file('imagemb');
			$idata['statu'] = $all['statu'];
			$idata['linkurl'] = urlencode($all['linkurl']); //视频源地址
			if($all['linkurl']){
				 $this->assign('linkurl',$all['linkurl']);
			}
			//var_dump($files) ;
			//echo "<br>====<br>";
			//var_dump($filesmb) ;
		    foreach($files as $file){
		        // 移动到框架应用根目录/public/uploads/ 目录下
		        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		        if($info){
		            // 成功上传后 获取上传信息 
		            $ext = $info->getExtension();   
		         
		            //  echo $info->getFilename(); 
		            // 输出 42a79759f284b767dfcb2a0197904287.jpg
		            $img_url = $info->getSaveName();
		            $end = explode("\\", $img_url);
		         
		            if(count($end)==1){
			            $newurl = '/medias/public/uploads/'.$end[0]; 
		            }else{
			            $newurl = '/medias/public/uploads/'.$end[0]."/".$end[1]; 
		            }
		            echo "<br>";
		          
		            
		            $idata['imageurl'] = $newurl;
		            $this->assign('imageurl',$newurl);
		        }else{
		            // 上传失败获取错误信息
		            echo $file->getError();
		        }    
		    }
			 
		     foreach($filesmb as $file){
		        // 移动到框架应用根目录/public/uploads/ 目录下
		        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		        if($info){
		            // 成功上传后 获取上传信息 
		            $ext = $info->getExtension();    
		            //  echo $info->getFilename(); 
		            // 输出 42a79759f284b767dfcb2a0197904287.jpg
		            $img_url = $info->getSaveName();
		            //var_dump($img_url);
		            $end = explode("\\", $img_url);
		         
		            if(count($end)==1){
			            $newurl2 = '/medias/public/uploads/'.$end[0]; 
		            }else{
			            $newurl2 = '/medias/public/uploads/'.$end[0]."/".$end[1]; 
		            }
		           
		            $idata['imageurlmb'] = $newurl2;
		            $this->assign('imageurlmb',$newurl2);
		        }else{
		            // 上传失败获取错误信息
		            echo $file->getError();
		        }    
		    }
		    $this->assign('statu',$all['statu']);
		    $insertend = $ck->Bwupdirect($idata);
			//$image = $all->file('image');
			//$image = request()->file('image');
			//var_dump($image);
			//echo "<br>";
			//var_dump($image);
		}else{
			 $this->assign('statu',0);
		}
	
		return $this->fetch('');
	}

	//返回接口信息
	public function Bwbx7directvideo(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"]; 
		
		$ck = new HkdataModel();
		$info = $ck->Bwrtvideostat(1);
		if(!empty($info['linkurl'])){
			$info['linkurl'] = urldecode($info['linkurl']);
		} 
		//exit(json_encode($info));
		echo $callback.'('.json_encode($info).')';
		
	}


}
