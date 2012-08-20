<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>菜单</title>
<link href="../Public/Css/menu.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base target="MainFrame" />
</head>
<script language="javascript">
<!--
function $(objectId) 
{
	 return document.getElementById(objectId);
}
function showHide(objname)
{
    var obj = $(objname);
    if(obj.style.display == "none")
    {
        obj.style.display = "block";
    }else{
        obj.style.display = "none";
    }
    return false;
}
function refreshMainFrame(url)
{
    parent.MainFrame.document.location = url;
}
-->
</script>
<body>
<div class="menu">

<?php switch($action): ?><?php case "Info":  ?><dl>
		<dt><a href="" onclick="return showHide('items1');" target="_self">商家模块</a></dt>  
        <dd id="items1" style="display:block;">
            <ul>
				<li><a href='__APP__/Shop'>查看商家</a></li>
                <li><a href='__APP__/Shop/add'>添加商家</a></li>
				<li><a href='__APP__/Series'>商家套系管理</a></li>
				<li><a href='__APP__/Attach'>附件管理</a></li>
            </ul>
        </dd>  
		<dt><a href="" onclick="return showHide('items2');" target="_self">名人堂模块</a></dt>  
        <dd id="items2" style="display:block;">
            <ul>
				<li><a href='__APP__/Personage'>名人管理</a></li>
                <li><a href='__APP__/Personage/add'>添加名人</a></li>
            </ul>
        </dd>   
    </dl>
    <dl>
        <dt><a href="" onclick="return showHide('items3');" target="_self">新闻模块</a></dt>
        <dd id="items3" style="display:block;">
            <ul>
				<li><a href='__APP__/News'>新闻管理</a></li>
                <li><a href='__APP__/News/add'>添加新闻</a></li>
				<li><a href='__APP__/News_recycler'>新闻回收站</a></li>
				<li></li>
				<li><a href='__APP__/NewsTopic'>话题PK管理</a></li>
				<li><a href='__APP__/NewsTopic/add'>添加话题</a></li>
            </ul>
        </dd>
    </dl>
	<dl>
        <dt><a href="" onclick="return showHide('items4');" target="_self">爱情故事</a></dt>
        <dd id="items4" style="display:block;">
            <ul>
				<li><a href='__APP__/Story'>故事管理</a></li>
                <li><a href='__APP__/Story/add'>添加故事</a></li>

            </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('<?php echo ($url); ?>');</script><?php break;?>
<?php case "Pics":  ?><dl>
        <dt><a href="" onclick="return showHide('items0');" target="_self">图片管理</a></dt>
        <dd id="items0" style="display:block;">
            <ul>
				<li><a href='__APP__/Pic'>相册管理</a></li>
                <li><a href='__APP__/Pic/add'>添加相册</a></li>
				<li><a href='__APP__/Attach'>附件管理</a></li>
            </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('<?php echo ($url); ?>');</script><?php break;?>
<?php case "Reviews":  ?><dl>
        <dt><a href="" onclick="return showHide('items0');" target="_self">交互管理</a></dt>
        <dd id="items0" style="display:block;">
            <ul>
				<li><a href='__APP__/Reviews'>点评管理</a></li>
				<li><a href='__APP__/Advice'>咨询管理</a></li>
				<li><a href='__APP__/Favorite'>收藏管理</a></li>
				<li><a href='__APP__/Guestbook'>留言管理</a></li>
            </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('<?php echo ($url); ?>');</script><?php break;?>

<?php case "Class":  ?><dl>
        <dt><a href="" onclick="return showHide('items0');" target="_self">栏目管理</a></dt>
        <dd id="items0" style="display:block;">
            <ul>
				<li><a href='__APP__/Class'>查看栏目</a></li>
				<li><a href='__APP__/Class/add'>添加顶级栏目</a></li>
                <!--<li><a href='__APP__/Class_model'>内容模型管理</a></li>-->
            </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('<?php echo ($url); ?>');</script><?php break;?>

<?php case "Admin":  ?><dl>
        <dt><a href="" onclick="return showHide('items0');" target="_self">用户模块</a></dt>
        <dd id="items0" style="display:block;">
            <ul>
				<li><a href='__APP__/User'>用户管理</a></li>
				<li><a href='__APP__/User/add'>添加用户</a></li>
            </ul>
        </dd>
    </dl>
	<dl>
        <dt><a href="" onclick="return showHide('items1');" target="_self">权限模块</a></dt>
        <dd id="items1" style="display:block;">
            <ul>
				<li><a href='__APP__/Role'>用户角色管理</a></li>
				<li><a href='__APP__/Role/add'>添加用户角色</a></li>
            </ul>
        </dd>
    </dl>
	<dl>
        <dt><a href="" onclick="return showHide('items1');" target="_self">管理员模块</a></dt>
        <dd id="items1" style="display:block;">
            <ul>
				<li><a href='__APP__/Admin'>管理员管理</a></li>
				<li><a href='__APP__/Admin/add'>添加管理员</a></li>
            </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('<?php echo ($url); ?>');</script><?php break;?>
<?php case "Systemconfig":  ?><dl>
        <dt><a href="" onclick="return showHide('items0');" target="_self">系统配置</a></dt>
        <dd id="items0" style="display:block;">
            <ul>
				<li><a href="__APP__/System/sys_cfg">基本设置</a></li>
                <!--<li></li>
                <li><a href='__APP__/Database'>数据表优化</a></li>-->
				<li><a href="__APP__/System/cache">清除缓存</a></li>
            </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('<?php echo ($url); ?>');</script><?php break;?>
<?php case "Other":  ?><dl>
        <dt><a href="" onclick="return showHide('items0');" target="_self">其它管理</a></dt>
        <dd id="items0" style="display:block;">
            <ul>
				<li><a href='__APP__/Pages'>单页管理</a></li>
				<li><a href='__APP__/Link'>链接管理</a></li>
				<li><a href='__APP__/Notice'>公告管理</a></li>
				<li><a href='__APP__/Flash'>首页幻灯管理</a></li>
            </ul>
        </dd>
    </dl>
    <script type="text/javascript">refreshMainFrame('<?php echo ($url); ?>');</script><?php break;?>

<?php default: ?>
    <dl>
        <dt><a href="" onclick="return showHide('items0');" target="_self">快捷方式</a></dt>
        <dd id="items0" style="display:block;">
            <ul>
				<li><a href='__APP__/System/sys_cfg'>系统配置</a></li>
				<li><a href='__APP__/Shop'>查看商家</a></li>
          		<li><a href='__APP__/Shop/add'>添加商家</a></li>
				<li><a href="__APP__/News">新闻管理</a></li>
				<li><a href='__APP__/News/add'>添加新闻</a></li>
				<li><a href='__APP__/Personage'>名人管理</a></li>
				<li><a href='__APP__/Attach'>附件管理</a></li>
				<li><a href="__APP__/Flash">首页幻灯管理</a></li>
				<li><a href='__APP__/Pages'>底部单页管理</a></li>
				<li><a href="__APP__/Link">友情链接管理</a></li>
                <li><a href="__APP__/System/cache">清除缓存</a></li>
            </ul>
        </dd>
    </dl>
<script type="text/javascript">refreshMainFrame('<?php echo ($url); ?>');</script><?php endswitch;?>

</div>
</body>
</html>