<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\rar\phpstudy\WWW\medias\public/../application/admin\view\index\menu.html";i:1488769861;}*/ ?>
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
      <dl class='bitem'>
        <dt onClick='showHide("items1_1")'><b>项目管理</b></dt>
        <dd style='display:block' class='sitem' id='items1_1'>
          <ul class='sitemu'>
            <!-- <li>
              <div class='items'>
                <div class='fllct'><a href='archives' target='main'>专题管理</a></div>
                <div class='flrct'> <a href='archives.html' target='main'><img src='javascript/skin/images/frame/gtk-sadd.png' alt='创建栏目' title='创建栏目'/></a> </div>
              </div>
            </li> -->
            <li><a href="<?php echo url('Project/show'); ?>" target='main' class="iii">项目列表</a> </li>
            <li><a href="<?php echo url('Project/add'); ?>" target='main'>项目添加</a> </li>
            <!-- <li>
              <div class='items'>
                <div class='fllct'><a href='archives.html' target='main'>回收站</a></div>
                <div class='flrct'> <a href='archives.html' target='main'><img src='javascript/skin/images/frame/gtk-del.png' alt='清空回收站' title='清空回收站'/></a> </div>
              </div>
            </li> -->
          </ul>
        </dd>
      </dl>
      <!-- Item 1 End -->
      <!-- Item 2 Strat -->
      <!-- <dl class='bitem'>
        <dt onClick='showHide("items2_1")'><b>用户管理</b></dt>
        <dd style='display:block' class='sitem' id='items2_1'>
          <ul class='sitemu'>
            <li><a href="<?php echo url('User/UserList'); ?>" target='main'>用户列表</a></li>
            <li><a href="<?php echo url('User/UserAdd'); ?>" target='main'>用户添加</a></li>
          </ul>
        </dd>
      </dl>
 -->
      <dl class='bitem'>
        <dt onClick='showHide("items3_1")'><b>注册信息管理</b></dt>
        <dd style='display:block' class='sitem' id='items3_1'>
          <ul class='sitemu'>
            <!--<li><a href="<?php echo url('Dealer/show'); ?>" target='main'>注册信息列表</a></li>-->
            <li><a href="<?php echo url('Dealer/add'); ?>" target='main'>添加注册信息</a></li>
          </ul>
        </dd>
      </dl>
      
      <dl class='bitem'>
        <dt onClick='showHide("items4_1")'><b>车系管理</b></dt>
        <dd style='display:block' class='sitem' id='items4_1'>
          <ul class='sitemu'>
            <li><a href="<?php echo url('Brand/BrandAdd'); ?>" target='main'>添加车型/品牌</a></li>
            <li><a href="<?php echo url('Brand/BrandShow'); ?>" target='main'>车系列表</a></li>
          </ul>
        </dd>
      </dl>

      <dl class='bitem'>
        <dt onClick='showHide("items5_1")'><b>权限管理</b></dt>
        <dd style='display:block' class='sitem' id='items5_1'>
          <ul class='sitemu'>
            <li><a href="<?php echo url('User/GetMenuList'); ?>" target='main'>菜单管理</a></li>
            <li><a href="<?php echo url('User/GroupList'); ?>" target='main'>角色管理</a></li>
            <li><a href="<?php echo url('User/UserList'); ?>" target='main'>用户管理</a></li>
          </ul>
        </dd>
      </dl>
      <!-- Item 2 End -->
	  </td>
  </tr>
</table>
</body>
</html>
<script type="text/javascript" src="javascript/skin/js/jquery-3.0.0.js"></script>