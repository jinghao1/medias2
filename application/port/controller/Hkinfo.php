<?php 
/**
 * 极客之家 高端PHP - 用户使用注册信息模块
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\port\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
use think\Session;
use think\Cache;
use think\Loader; 
use app\port\model\HkdataModel; 
 
class Hkinfo extends Controller	{	
	 
	//获取回馈信息
	public function ItHk()
	{
		$hk = new HkdataModel();
		$allinfo = input('param.');
		$newa = array();
		if($allinfo['pid']){
			//先判断此字段id是否有回馈值 
			$et =$hk->Etid($allinfo['pid']);
			if($et){
				if(!empty($allinfo['laiyuan'])){ //检测来源
					$ly = $hk->Autoadd($allinfo['pid'],'laiyuan');
					if(!empty($allinfo['lycont'])){
						$lyarr = array('lycon'=>$allinfo['lycont']);
						$lycon = $hk->EditHk($allinfo['pid'],$lyarr);
					}
				}
				if(!empty($allinfo['biaoti'])){
					$lyb = $hk->Autoadd($allinfo['pid'],'btd');
				}
				if(!empty($allinfo['zl'])){
					$lyz = $hk->Autoadd($allinfo['pid'],'quality');
				}
			}else{
				if(!empty($allinfo['laiyuan']) && !empty($allinfo['lycont'])){
					$newa['laiyuan'] = 1;
					$newa['lycon'] = $allinfo['lycont']; //来源链接地址
					
				}
				if(!empty($allinfo['biaoti'])){ //标题党
					$newa['btd'] = 1;
				}
				if(!empty($allinfo['zl'])){ //质量差
					$newa['quality'] = 1;
				}
				$newa['artid'] = $allinfo['pid']; //文章id
				//数据插入
				$bad = $hk->IntHk($newa);
			}  
		}
	 	return 1;
		//var_dump($bad,$lyb);
		exit;
		//$data = $car->DealerClass($dealer_id);
		//exit(json_encode($data));
	}

	//广告点击监测
	public function Adck(){
		$ck = new HkdataModel();
		$all = input('param.'); //接收信息 
		$newaw = array(); 
 
		if($all['tit'] && $all['pos'] && $all['link']){
			$newaw['title'] = $all['tit']; //标题
			$newaw['position'] = $all['pos']; //广告位
			$newaw['link'] = $all['link']; //链接
			//检测是否已经存在
			$et = $ck->Ethead($newaw);
			if($et){ //数据更新
				$upad = $ck->EditAd($newaw); 
			}else{ //插入数据
				$newaw['time'] = time();
				$newaw['num'] = 1;
				$addend = $ck->IntAd($newaw);
			}
		}
		return 1;
	}

	//接口信息转换列表
	public function Changelist(){
		$appid = 'a31994181db54f5395f0fe43b19cf520';
		$all = input('param.'); //接收信息    
		$callback = $all["callback"];  
		$all['newsType'] = 0; //最新
		//$zurl = 'http://172.16.0.142:13000/newsapi/getnewslist?appid='.$appid.'&newsType='.$all['newsType'].'&pageNo='.$all['pageNo'].'&pageSize='.$all['pageSize'];
		$zurl = 'http://partner.api.qichedaquan.com/newsapi/getnewslist?appid='.$appid.'&newsType='.$all['newsType'].'&pageNo='.$all['pageNo'].'&pageSize='.$all['pageSize'];
		$end = file_get_contents($zurl); 
		echo $callback.'('.json_encode($end).')';
		return;
	}

	//接口信息转换 内容详情
	public function Changecont(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"]; 
		$appid = 'a31994181db54f5395f0fe43b19cf520';
		$xurl = 'http://172.16.0.142:13000/newsapi/detail/'.$all['cid'].'?appid='.$appid;
		$xurl = 'http://partner.api.qichedaquan.com/newsapi/detail/'.$all['cid'].'?appid='.$appid;
		$end = file_get_contents($xurl);
		//$end = '00099';  
		echo $callback.'('.json_encode($end).')';
		return;
	}

	

	//头部信息接口https 转换
	public function cgheadhttps(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"]; 
	 
		$xurl = 'http://i.qichedaquan.com/info/header?source=1&backurl='.$all['bkurl'];
	 
		$end = file_get_contents($xurl);
		//$end = '00099';  
		$end = str_replace("null('",'',$end);
		$end = str_replace("')",'',$end);
		//echo $callback.'('.json_encode($end).')';
		echo $callback.'('.json_encode($end).')';
		return;
	}

	//citychange 城市切换
	public function Changecityinfo(){
		$all = input('param.'); //接收信息 
		$callback = $all["callback"]; 
		$ck = new HkdataModel();
		//获取一级省份信息
		$proinfo = $ck->Gtproi();
		$newprocity = array();
		foreach($proinfo as $key=>$val){
			//根据cityid判断是否有城市有活动
			$uncity = $ck->Gtudcity($val['cityid']);
			//var_dump($uncity);
			if(empty($uncity)){ //跳过没有活动城市的省份
				unset($proinfo[$key]);
				continue;
			}
			$newprocity[$val['cityid']] = $uncity;
			 
		}
		//var_dump($proinfo); 
	 
	 	$str1  = '<div class="header_nav">
	    <div class="header_nav_content">
	        <ul class="header_nav_left"> 
	            <li class="city_position" id="city_position"> 
	            <span class="changeAdd" id="city_position_qie" style="color: rgb(153, 153, 153);">切换</span>
	            <span class="triangle" id="city_position_triangle" style="border-top-color: rgb(153, 153, 153);"></span>
	                <div class="city_chose" id="city_chose" style="">
	                    <div class="city_search">
	                        <div class="city_search_input">
	                                <span class="search_btn">
	                                    <img src="http://static.qcdqcdn.com/img/search-btn.png" alt="">
	                                </span>
	                            <span>
                                    <input type="text" value="请输入城市" class="input" name="cityName" oninput="indexCitySearchInputChange(this)" onpropertychange="indexCitySearchInputChange(this)" id="input_letters">
                                    <div class="input_letters_search" id="input_letters_search" style="display: none;">
                                        <ul id="input_city_search"><li class="click_to_destiny"><span><img src="http://static.qcdqcdn.com/img/city_down_select.png" alt=""></span> <span>点击直达</span> </li></ul>
                                    </div>
                                </span>
	                            <span class="city_hot_search">
                                    <a href="http://beijing.qichedaquan.com" cityid="201">北京</a>
                                    <a href="http://shanghai.qichedaquan.com" cityid="2401">上海</a>
                                    <a href="http://guangzhou.qichedaquan.com" cityid="501">广州</a>
                                    <a href="http://shenzhen.qichedaquan.com" cityid="502">深圳</a>
                                   
                                    <a href="http://hangzhou.qichedaquan.com" cityid="3001">杭州</a>
                                </span>
	                            <span class="delete_city"> <img src="http://static.qcdqcdn.com/img/delete-btn.png" alt=""> </span>
	                        </div>
	                    </div>';
	                     $str2 = '<div class="hot_city_num">';
	                      $str3 = ' <div class="city_scroll">
	                        <div class="city_chose_region" id="city_chose_region" style="margin-top: 0px;">';
	                $etnv = "";        
	                foreach($proinfo as $nk=>$nv){
		                if($etnv!=$nv['initial']){
			                 $str2 .= ' <a href="#" >'.$nv['initial'].'</a>'; //字母标签
		                }
		                $etnv = $nv['initial'];
		               
		                //省份城市
		                $str3 .= ' <dl>
									<dt>
										<span class="province_tx">'.$nv['initial'].'</span>
										<span class="province_num">'.$nv['cityname'].'</span>
									</dt>
								<dd>';
						foreach($newprocity[$nv['cityid']] as $ke=>$va){
							$str3 .= '<a cityid="'.$va['cityid'].'" href="#">'.$va['cityname'].'</a> ';
						}
						$str3 .= '</dd> </dl>'; 	
	                }
	                $str2 .= ' </div>';
	            	$str3 .= '  </div>
	                        <div class="scrollbar">
	                            <div class="scrollbtn" style="height: 0px; top: 43.3912px;"></div>
	                        </div>
	                    </div>
	                </div>
	            </li> 
	        </ul> 
	    </div>
	</div>';
	            
	$str = $str1.$str2.$str3;
	 
		echo $callback.'('.json_encode($str).')';
		return;
	} 
}
?>