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
use app\admin\model\DbjingcaiModel;
class Dbjingcai extends	Base	
{	

	//竞猜信息列表
	public function Showjc(){ 
	
       
        $ck = new DbjingcaiModel(); 
        $you = input('param.enews');
        $fromid = empty($you) ? 1 : $you;  
        //获取是显示全部还是，真实，虚假
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
        $data = $ck->getalljingc($fromid); 
        $this->assign('enewsid',$fromid);
        //获取购买时间段 
        if(!empty($data)){ 
            $page = $data->render();
            $data = $data->all(); //解除对象保护  
            //foreach ($data as $key => $val) {
                 
            //} 
        }else{
            $page = "";
        } 
        $this->assign('data',$data);     
        $this->assign('proid','44');  //暂时无用    
        $this->assign('page',$page);                    
        return $this->fetch();  
	}
	//灌水
	public function Guanshui(){
		$all = input("param.");
		$ck = new DbjingcaiModel();
		if(isset($all['id'])){
		 
			for($i=0;$i<$all['totalnum'];$i++){
				$price = rand($all['minprice'],$all['maxprice']);
				$end = $ck->Itjingcai($price);
				
			}
			echo '已成功写入'.$i."条"."-时间:".date("H:i:s");
			
			echo "<br>";
			$allmoni = $ck->Countjingcai(1);
			echo '已模拟条数'.$allmoni;
			echo '<br>';
		}
		return $this->fetch();
	}
 

  

}
