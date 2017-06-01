<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:97:"/Applications/XAMPP/xamppfiles/htdocs/medias/public/../application/admin/view/user/grouplist.html";i:1489546998;}*/ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>文档管理</title>
<base href="__PUBLIC__/">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/base.css">
<link rel="stylesheet" type="text/css" href="javascript/skin/css/Popup.css">

</head>


<body leftmargin="8" topmargin="8" background='skin/images/allbg.gif'>
<div style="margin-left:10px;">
	<a href="<?php echo url('GroupAdd'); ?>"><input type="button" value="添加角色"></a>
</div>
<!--  内容列表   -->
<form name="form2">

<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
	<tr bgcolor="#E7E7E7">
		<td height="24" colspan="10" background="javascript/skin/images/tbg.gif">&nbsp;角色列表&nbsp;</td>
	</tr>
	<tr align="center" bgcolor="#FAFAF1" height="22">
		<td >ID</td>
		<td >角色名称</td>
		<td >状态</td>
		<td >添加时间</td>
		<td >更新时间</td>
		<td >操作</td>
	</tr>

	<?php if(is_array($GroupData) || $GroupData instanceof \think\Collection): if( count($GroupData)==0 ) : echo "" ;else: foreach($GroupData as $key=>$v): ?>
		<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
			<td><?php echo $v['id']; ?></td>
			<td align="left"><?php echo $v['title']; ?></td>
			<td align="left">
				<?php if($v['status'] == 1): ?>关闭<?php else: ?>开启<?php endif; ?>
			</td>
			<td align="left"><?php echo $v['create_time']; ?></td>
			<td align="left"><?php echo $v['update_time']; ?></td>
			<td><a href="javascript:;" class="GetRule" ids="<?php echo $v['id']; ?>" onclick="authrule(<?php echo $v['id']; ?>)">分配权限</a> | <a href="<?php echo url('Groupuserdel',['guid'=>$v['id']]); ?>" onclick="return confirm('项目删除后，此用户组下所有用户均会被删除，确认删除？');">删除</a><!--<a href="101">编辑</a> | <a href="101">删除</a>--> </td>
		</tr> 
	<?php endforeach; endif; else: echo "" ;endif; ?>

	<!-- <tr bgcolor="#FAFAF1">
		<td height="28" colspan="10">
			&nbsp;
			<a href="javascript:selAll()" class="coolbg">全选</a>
			<a href="javascript:noSelAll()" class="coolbg">取消</a>
			<a href="javascript:updateArc(0)" class="coolbg">&nbsp;更新&nbsp;</a>
			<a href="javascript:checkArc(0)" class="coolbg">&nbsp;审核&nbsp;</a>
			<a href="javascript:adArc(0)" class="coolbg">&nbsp;推荐&nbsp;</a>
			<a href="javascript:moveArc(0)" class="coolbg">&nbsp;移动&nbsp;</a>
			<a href="javascript:delArc(0)" class="coolbg">&nbsp;删除&nbsp;</a>
		</td>
	</tr> -->
	<tr align="right" bgcolor="#EEF4EA">
		<td height="36" colspan="10" align="center"><!--翻页代码 --></td>
	</tr>
</table>
</form>

<div id="choose-box-wrapper">
 <div id="choose-box">
	 <form method="post" action="<?php echo url('Updategroupauth'); ?>"   name="form3"> 
		<div id="choose-box-title">
			<span>权限分配</span>
			<span style="float:right;curosr:pointer;" id="hide">关闭</span>
		</div>  
		<div class="tree_content">
	        <div class="tree_node">
				<?php if(is_array($GroupArrayData) || $GroupArrayData instanceof \think\Collection): if( count($GroupArrayData)==0 ) : echo "" ;else: foreach($GroupArrayData as $key=>$v): ?>
		            <div class="div_inline"><input type="button" value="" class="tree_node_toggle_button"></div>
		            <div class="div_inline tree_node_parent"> 
		                <input type="checkbox" name="box[]" class="tree_node_parent_checkbox" value="<?php echo $v['id']; ?>"><?php echo $v['title']; ?> <br>
		                <?php if(is_array($v['data_list']) || $v['data_list'] instanceof \think\Collection): if( count($v['data_list'])==0 ) : echo "" ;else: foreach($v['data_list'] as $key=>$vv): ?>
		                <div class="tree_node_child">
		                    
		                    <!-- 三级 -->
								<?php if($vv['data_lists'] != array()){?>
		                    		<div class="div_inline">
		                    			<input type="button" value="" class="tree_node_toggle_button">
			                    		 
		                    		</div>
		                    	<?php
		                    	}
		                    	?>
		                    <div class="div_inline tree_node_parent">
                            <input type="checkbox" name="box[]" value="<?php echo $vv['id']; ?>" class="tree_node_child_checkbox"><?php echo $vv['title']; ?><br>
		                        <?php if(is_array($vv['data_lists']) || $vv['data_lists'] instanceof \think\Collection): if( count($vv['data_lists'])==0 ) : echo "" ;else: foreach($vv['data_lists'] as $key=>$vvv): ?>
			                        <div class="tree_node_child">
			                            <input type="checkbox" name="box[]" value="<?php echo $vvv['id']; ?>" class="tree_node_child_checkboxs"><?php echo $vvv['title']; ?><br>
			                        </div> 
		                        <?php endforeach; endif; else: echo "" ;endif; ?>	
		                    </div>

		                </div>
		                <?php endforeach; endif; else: echo "" ;endif; ?>
		            </div>
				<?php endforeach; endif; else: echo "" ;endif; ?>
	        </div>
	    </div>


		<div id="choose-box-bottom">
			<input type="number" value="" name="groupid" style="display: none;">
			<input type="submit" id="GroupAllot" value="分配权限" />
		</div>
	 </form>
  </div>
</div>

</body>
</html>
<script type="text/javascript" src="javascript/skin/js/jquery-3.0.0.js"></script>
<script type="text/javascript" src="javascript/skin/js/checkbox.js"></script>
<script type="text/javascript" src="javascript/skin/js/basesong.js"></script>

<script>
	$(function(){
		
		//开始分配权限

		
	})
	//点击分配权限，分配当前用户组id 到body
function authrule(id){
	$("input[name='groupid']").val(id);
	//获取此用户组下的权限
	 $.ajax({
         type : 'post',
         url  : "<?php echo url('admin/User/Haveauth'); ?>",
         data : {groupid : id},
         dataType : "json",
         success : function(msg){
	        $("input[name='box[]']").prop("checked",false);
	        msg = $.parseJSON(msg);
	        if(msg.length){ 
		        $(msg).each(function(key,val){ 
			       $("input[name='box[]'][value='"+val+"']").prop("checked","true");
		        });
	        } 
         },
         error : function(err){
	         console.log("error");
	         console.log(err);
         }
     });
}
</script>