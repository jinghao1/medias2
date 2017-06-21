<?php
/**
 * 极客之家 高端PHP - Excel导出导入
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\controller;
use	think\Controller;
use	think\Request;
use	think\Db;
use think\PHPExcel\PHPExcel;
use app\admin\model\ZhydataModel; //获取项目信息 
class Cbhtozhy extends	Controller	
{
	//车百汇注册信息读取
	 /**
     * 导入数据
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function Tozhydata(){ 
    //关闭
    	//return "closed";
	    $file = request()->file('importexcel');
	   
        // 移动到框架应用根目录/public/uploads/ 目录下
    	$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
    	if($info){
	        // 成功上传后 获取上传信息 
	        $extname = $info->getExtension(); //获取文件扩展名  
	        $urlname = $info->getSaveName(); //获取文件存储目录及名称
	        $urlname = ROOT_PATH . 'public' . DS . 'uploads/'.$urlname;
	        $filename = $info->getFilename();  //获取文件保存名称
	    }else{
	        // 上传失败获取错误信息
	        echo $file->getError();
	    } 
	   
        if ($extname == 'xls') {
            $result = import("Excel5",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif ($extname == 'xlsx') {
            $result = import("Excel2007",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            return '路径出错';
        }

        $PHPExcel     = $PHPReader->load($urlname);
        $currentSheet = $PHPExcel->getSheet(0);
        $allColumn    = $currentSheet->getHighestColumn();
        $allRow       = $currentSheet->getHighestRow();
        $ck = new ZhydataModel();
        $allColumn ='Z';
        for($currentRow = 2; $currentRow <= $allRow; $currentRow++){
            for($currentColumn='A'; $currentColumn <= $allColumn; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue(); 
            }
        
            $regid = $arr[$currentRow]['A']; //用户id   
            $name = $arr[$currentRow]['B']; //用户名称 
            $mobile = $arr[$currentRow]['C']; //用户手机号
            //通过车系id，获取具体基本车型  
            $carId = $ck->Basicbyseriseid($arr[$currentRow]['D']); 
           	$carId = $carId['brand_id']; //具体车型id 
           	$dealerId = '666'; //待定 经销商id ，需要匹配 
			$ipAddress = $arr[$currentRow]['M']; //注册ip
			$url = 'http://xy.qichedaquan.com/chebaihui'; //注册地址
			$url .= $arr[$currentRow]['N']==1 ? '_m':'';
			$url = urlencode($url);
			
			$CreateTime = $arr[$currentRow]['G']; //注册时间
			$LocationID = $this->Switchcityid($arr[$currentRow]['I'],$arr[$currentRow]['J'],$arr[$currentRow]['K']) ; //根据用户选择获取城市id，
			//数据传输，组合
			$tdata = array('ChannelId'=>'chebaihui','type'=>'0','sourceID'=>'11','remark'=>'UTF-8','name'=>$name,'mobile'=>$mobile,'carId'=>$carId,'dealerId'=>$dealerId,'ipAddress'=>$ipAddress,'url'=>$url,'CreateTime'=>$CreateTime,'LocationID'=>$LocationID);
			//$tdata = array('ChannelId'=>'chebaihui','type'=>'0','sourceID'=>'11','remark'=>'UTF8','name'=>$name,'mobile'=>$mobile,'carId'=>$carId,'dealerId'=>$dealerId,'ipAddress'=>'1923123','url'=>'httpbaidu','CreateTime'=>'88766','LocationID'=>$LocationID);
			$this->Translationctz($tdata); 
           	unset($arr[$currentRow]);
          
        } 
        exit; 
    }
    //通过选择城市，定位城市，入口城市，优先级，返回城市id
    public function Switchcityid($fname,$dname,$cname){
	    $ck = new ZhydataModel();
	    $end = 0;
	    if($cname){ //查询城市id
		    $end = $ck->Getidbycityname($cname);
	    }
	    if(!$end){
		    $end = $ck->Getidbycityname($dname);
	    }
	    if(!$end){
		    $end = $ck->Getidbycityname($fname);
	    }
	    if(isset($end['cityid'])){
		    return $end['cityid'];
	    }else{
		    return 0;
	    }
	    
    }

    
    //车百汇=》智慧云 数据传输
	public function Translationctz($data){
		$curt = time();
		$appsecret = '3C8C719C-2F20-476D-A953-88F612D89664';
		$appkey = 'chebaihui'; 
		$singstr = $appkey.$appsecret.$data['carId'].$data['ChannelId'].$data['CreateTime'].$data['dealerId'].$data['ipAddress'].$data['LocationID'].$data['mobile'].$data['name'].$data['remark'].$data['sourceID'].$curt.$data['type'].$data['url'];
		//var_dump($singstr);
		echo "<br>*********<br>";
		$singstr = sha1($singstr,TRUE); //2进制
		$singstr = base64_encode($singstr);
		var_dump($data['mobile']);
		echo "<br>===<br>";
		$singstr = urlencode($singstr);
	 
		$theurl ='http://api.xingyuanauto.com/BusinessOrder/Orders?AppKey='.$appkey.'&Signature='.$singstr.'&Timestamp='.$curt;  
		$data = http_build_query($data); 
		$opts = array (
			'http' => array (
				'method' => 'POST',  
				'header'=>"Content-type: application/x-www-form-urlencoded\r\n"."Content-length:".strlen($data)."\r\n" . "Cookie: foo=bar\r\n" . "\r\n",  
				'content' => $data
			)
		);
		 
		$context = stream_context_create($opts);
		//阻断，不执行
		//return ;
		$html = file_get_contents($theurl,false,$context);
		 
		var_dump($html);
		echo "<br>";
	}
	
}	


?>