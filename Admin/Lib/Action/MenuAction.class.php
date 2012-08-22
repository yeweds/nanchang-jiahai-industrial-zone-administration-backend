<?php
/**
 * Menu  菜单管理类
 * @author 熊彦 <cnxiongyan@gmail.com>
 */
class MenuAction extends GlobalAction{
    function _initialize()
    {
        parent::_initialize();
        $VERSION = C('cfg_version');
        $uid = $this->getUid();
        $username=$this->getName();
        $groupId=$this->getGid();
        if (!$groupId ||!$uid) $this->redirect('login','Public');
        //获取用户组ID权限
        // $security = getAdminGrp($groupId);  //返回管理员组级别
        // if (!$security) $this->error('未知用户组ID');//未获取用户组ID权限，请联系系统管理员
        //dump($security);
        $this->assign('charset',C('TEMPLATE_CHARSET'));
        $this->assign('version',$VERSION);
        $this->assign('uid',$uid);
        $this->assign('admin',$username);
        $this->assign('security',$security);
        
        //Session::destroy('admin');
    }

    //左侧菜单
    public function leftmenu(){
        $action = $_REQUEST['action'];
        switch ($action){
            case 'Systemconfig':$url=__APP__.'/System/sys_cfg';break;
            case 'Info':$url=__APP__.'/News';break;
            case 'Class':$url=__APP__.'/Class';break;
            case 'Statistics':$url=__APP__.'/Statistics';break;
            case 'Pics':$url=__APP__.'/Pic';break;
            case 'Tousu':$url=__APP__.'/Tousu';break;
            case 'Reviews':$url=__APP__.'/Reviews';break;   //跳向点评
            case 'Order':$url=__APP__.'/Order';break;
            case 'Admin':$url=__APP__.'/Admin';break;
            case 'Other':$url=__APP__.'/Pages';break;
            default:$url=__APP__.'/Shop';               
        }
        $this->assign('url',$url);
        $this->assign('action',$action);
        $this->display("Menu:leftmenu");
    }

    //头部菜单
    public function topmenu(){
        $this->assign('http',C('cfg_domain'));
        $this->display("Menu:topmenu");
    }       
} 
?>