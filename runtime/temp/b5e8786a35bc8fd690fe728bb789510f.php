<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\dealer\showlot.html";i:1490581802;}*/ ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>中奖信息</title>
</head>
<style type="text/css" media="screen" id="test">
	.textcen{
		text-align:center; 
	}
</style>
<body>
	<form action="<?php echo url('editlot'); ?>" method="post">
		<table>
			<tr align="center" bgcolor="#FAFAF1" height="22">
				<td >奖项ID</td> 
				<td >奖项名称</td>
				<td >中奖概率</td>
				<td >剩余数量</td> 
			</tr>

			<?php if(is_array($data) || $data instanceof \think\Collection): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
				<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
					<td><input class="textcen"  type="number" name="lotid" value="<?php echo $v['id']; ?>" /> </td>
					 
					<td ><input  class="textcen" type="text" name="lotname" value="<?php echo $v['name']; ?>" /> </td>
				 
					<td ><input class="textcen"  type="text" name="lotchance" value="<?php echo $v['chance']; ?>%" /></td>
				    <td ><input class="textcen" type="text" name="lotnum" value="<?php echo $v['num']; ?>" /></td> 
				 
				</tr>
			<?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
	 
			<!--<p><input type="submit" value='更新'></p>-->
	 
	
	</form>
</body>
</html>