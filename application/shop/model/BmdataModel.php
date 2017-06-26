<?php 
namespace app\bmpro\model;
use think\Model;
use think\Db;
class BmdataModel extends Model
{

	function Getchance(){
		return Db::name('paobab')->select();
	}
	//edit by song 
	//获取某pid的概率参数
	function Getchancebyid($pid){
		return Db::name('paobab')->where('p_id',$pid)->select();
	} 
	//根据pid更新 上午下午中奖概率
	function Updchancebyid($pid,$uppro,$dowpro){
		return Db::name('paobab')->where('p_id',$pid)->update(['am_probab'=>$uppro,'pm_probab'=>$dowpro]);
	}
	//end by song
}
?>