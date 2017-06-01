<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:93:"/Applications/XAMPP/xamppfiles/htdocs/medias/public/../application/admin/view/index/menu.html";i:1489484784;}*/ ?>
<html>
<head>
<title>menu</title>
<base href="__PUBLIC__/"/>
<link rel="stylesheet" href="javascript/skin/css/base.css" type="text/css" />
<link rel="stylesheet" href="javascript/skin/css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script language='javascript'>var curopenItem = '1';</script>
<script language="javascript" type="text/javascript" src="javascript/skin/js/frame/menu.js"></script>
</head>
<body target="main">
<table width='99%' height="100%" border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td style='padding-left:3px;padding-top:8px' valign="top">
	<!-- Item 1 Strat -->
      <!--<dl class='bitem'>
        <dt onClick='showHide("items1_1")'><b>项目管理</b></dt>
        <dd style='display:block' class='sitem' id='items1_1'>
          <ul class='sitemu'>
             
            <li><a href="<?php echo url('Project/show'); ?>" target='main' class="iii">项目列表</a> </li>
            <li><a href="<?php echo url('Project/add'); ?>" target='main'>项目添加</a> </li>
             
          </ul>
        </dd>
      </dl>-->
   		<?php if(isset($menuarr)): if(is_array($menuarr) || $menuarr instanceof \think\Collection): if( count($menuarr)==0 ) : echo "" ;else: foreach($menuarr as $fmenu=>$menuvo): ?>
   			  <dl class='bitem'>
		        <dt onClick='showHide("<?php echo $fmenu; ?>")'><b><?php echo $fmenu; ?></b></dt>
		        <dd style='display:block' class='sitem' id='<?php echo $fmenu; ?>'>
		          <ul class='sitemu'>
		            <?php if(is_array($menuvo) || $menuvo instanceof \think\Collection): if( count($menuvo)==0 ) : echo "" ;else: foreach($menuvo as $kurl=>$tname): ?> 
			            <li><a href="<?php echo url($kurl); ?>" target='main' class="iii"><?php echo $tname; ?></a> </li>
			             
		            <?php endforeach; endif; else: echo "" ;endif; ?>
		          </ul>
		        </dd>
		      </dl>
   			<?php endforeach; endif; else: echo "" ;endif; else: ?> 
			请联系管理员
		<?php endif; ?>
      <!--<dl class='bitem'>
        <dt onClick='showHide("items3_1")'><b>注册信息管理</b></dt>
        <dd style='display:block' class='sitem' id='items3_1'>
          <ul class='sitemu'>
             
            <li><a href="<?php echo url('Dealer/add'); ?>" target='main'>添加注册信息</a></li>
          </ul>
        </dd>
      </dl>-->
      <!--
      <dl class='bitem'>
        <dt onClick='showHide("items4_1")'><b>车系管理</b></dt>
        <dd style='display:block' class='sitem' id='items4_1'>
          <ul class='sitemu'>
            <li><a href="<?php echo url('Brand/BrandAdd'); ?>" target='main'>添加车型/品牌</a></li>
            <li><a href="<?php echo url('Brand/BrandShow'); ?>" target='main'>车系列表</a></li>
          </ul>
        </dd>
      </dl>-->

      <!--<dl class='bitem'>
        <dt onClick='showHide("items5_1")'><b>权限管理</b></dt>
        <dd style='display:block' class='sitem' id='items5_1'>
          <ul class='sitemu'>
            <li><a href="<?php echo url('admin/User/GetMenuList'); ?>" target='main'>菜单管理</a></li>
            <li><a href="<?php echo url('admin/User/GroupList'); ?>" target='main'>角色管理</a></li>
            <li><a href="<?php echo url('admin/User/UserList'); ?>" target='main'>用户管理</a></li>
          </ul>
        </dd>
      </dl>-->
      <!-- Item 2 End -->
	  </td>
  </tr>
</table>
</body>
</html>
<script type="text/javascript" src="javascript/skin/js/jquery-3.0.0.js"></script>