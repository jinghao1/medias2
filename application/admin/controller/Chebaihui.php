<?php 
/**
 * 极客之家 高端PHP - 用户使用注册信息模块
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use think\Cache;
use think\Loader;
use app\port\model\DealerModel;
use app\admin\model\ChebhModel;
use app\admin\model\CarModel;
use app\admin\model\UserModel;
 
class Chebaihui extends Base  {   
  
     //更新活动地址
    public function upactaddr(){

        $info = input('param.');
        $callback = $info["callback"]; 
       
        $begt = 0;
        $endt = 0;
        if(!empty($info['begt'])){
            $begt = strtotime($info['begt']);
        }
        if(!empty($info['endt'])){
            $endt = strtotime($info['endt']);
        }
        $actstatu = 0; //活动状态
        if($info['actstatu']=="true"){
            $actstatu = 1;
        }
        $cbwaiting = 0;
        if($info['cbwaiting']=="true"){
            $cbwaiting = 1;
        }
        //活动城市信息
        $newarr = array("cityid"=>$info['cityid'],"begtime"=>$begt,"endtime"=>$endt,"address"=>$info['actaddr'],"fzrname"=>$info['fzr'],"waiting"=>$cbwaiting);
        //活动状态开启信息
        $citystatu = array("cityid"=>$info['cityid'],"status"=>$actstatu);

        $up = new ChebhModel();
        $end = $up->Upactinfo($newarr,$citystatu);
        $dataend = array('statu'=>$end);
        echo $callback.'('.json_encode($dataend).')';
        //echo $callback.'("ldldl")';
        return;
        //exit(json_encode($info));
    }

   

    public function Cbhregall(){ 
        // $dealer = new DealerModel();
         $car = new CarModel();
        // $project = new ProjectModel(); 
        $ck = new ChebhModel();
       
        $you = input('param.enews');
        $fromid = empty($you) ? 1 : $you;
        
        //查询车系车型
       
            //获取是显示全部还是，mobile，pc
            switch ($fromid){
                case 1:
                    $this->assign('yxenews',1);
                    break;  
                case 2:
                    $this->assign('llenews',1);
                    break;  
                case 3:
                    $this->assign('allout',1);
                    break;  
                default:
                    $this->assign('yxenews',1);
                    break; 
            }   
       
         $data = $ck->getregcbh($fromid);
        // var_dump($data);

        
        $this->assign('enewsid',$fromid);
        //获取购买时间段
        $newbuycartm = array(0=>"暂无");
        $buycartm = $car->BuycarTime();
        if($buycartm){
            foreach($buycartm as $bctm){
                $newbuycartm[$bctm['id']] = $bctm['timename'];
            }
        } 
        
        if(!empty($data)){
            
            $page = $data->render();
            $data = $data->all(); //解除对象保护  
            foreach ($data as $key => $val) {
                //查询车系、车型
                // if(!$val['car_series_id']){
                //     continue;
                // } 
                // $DataArrName = $car->CarSelectName($val['car_series_id']);
                // //查询经销商
                // if($val['dealer_name']){
                //     $DataDealerName = $dealer->DealerSelectName($val['dealer_name'],$val['project_id']);
                //     $data[$key]['dealer_name'] = $DataDealerName; 
                // }
                
                // $data[$key]['car_series_id'] = $DataArrName;
                // $data[$key]['time'] = date("Y-m-d H:i:s",$data[$key]['time']) ;
                
                //查询信息来源
                $regid =$ck->cynamebyid($data[$key]['from']);
                if(isset($regid[0]) && !empty($regid[0])){
	                $data[$key]['from'] = $regid[0]['cityname'];
                }
                $data[$key]['buy_car_time'] = $newbuycartm[$data[$key]['buy_car_time']];
             
                
            } 
        }else{
            $page = "";
        }
        
        $user = new UserModel();
   
     
        //检测注册信息编辑权限，
        $editopt = $user->CkOptionuser('admin/dealer/add');
        //echo Db::getlastsql();
        $this->assign('editopt',$editopt); //是否有删除权限，1有，2没有 true ,false
        //检测注册信息删除权限
        $delpro = $user->CkOptionuser('admin/dealer/PjregDel'); 
        $this->assign('proid',39);
        $this->assign('delopt',$delpro); //是否有删除权限，1有，2没有
        $this->assign('data',$data);     
        $this->assign('page',$page);                    
        return $this->fetch(); 
    }


    //删除注册信息
    public function PjregDelcbh(){
        $dealer = new ChebhModel();
        $id = input('param.dealer_id/d'); 
     
        if($id ){
            $res = $dealer->delcbhreg($id);
            if($res){
                $this->success('删除成功');
            }
            else
            {
                $this->error('删除失败');
            }
        }else{
            $this->error('无删除id');
        }
    }

    //一键查询，车百汇注册信息
	public function gtphone(){ 
		$all = input('param.'); //接收信息  
		$end = array();
		//获取登录用户id
		$sess = Session::get('admin_uid'); 
		
		
		if(isset($all['phone']) && !empty($all['phone'])){
			//查询此手机号所有注册信息
			$ck = new ChebhModel();
			 
			$ph =$all['phone'];
			$end = $ck->regallcbh($ph);
			//var_dump($end[0]);
			if(isset($end[0])){
				$this->assign('data',$end[0]);
				$this->assign('havereg','已注册');
				if($end[0]['accept']==1){
					$this->assign('havelq','已领取');
				}else{
					$this->assign('havelq','未领取');
				}
			}else{
				$this->assign('havereg','未注册');
				$this->assign('havelq','未领取');
			}
			$this->assign('thephone',$all['phone']); 
		} 
		$this->assign('uid',$sess);
		return $this->fetch(); 
	}

	//更改用户奖品领取状态
	public function upuserlq(){
		$all = input('param.'); //接收信息
		$phone = $all['curphone'];
		$ck = new ChebhModel();
		$end = 0;
		if($phone){
			//获取登录用户id
			$sess = Session::get('admin_uid'); 
			if($sess==1){ //先判断
				$curph = $ck->ckuplqbyph($phone);
				if($curph[0]['accept']==1){
					$end = $ck->uplqbyph0($phone);
					echo 3;
					return;
				}else{
					$end = $ck->uplqbyphone($phone);
				}
			}else{
				$end = $ck->uplqbyphone($phone);
			}
			
		}
		if($end){
			echo 1;
			return ;
		}else{
			echo 2;
			return 2;
		}
		
	}
                                        
     
 
 
}
?>