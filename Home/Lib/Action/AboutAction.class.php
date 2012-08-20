<?php
/**
 +------------------------------------------------------------------------------
 * About  关于我们类
 +------------------------------------------------------------------------------
 * @author 一凡
 +------------------------------------------------------------------------------
 */
class AboutAction extends GlobalAction{
    
    public function index(){
        $this->display();
    }

//---关于我们
    public function about_us()
    {
        $this->display();
    }

//---联系我们
    public function about_lianxi()
    {
        $this->display();
    }

//---您的意见
    public function about_yijian()
    {
        $this->display();
    }

//---广告服务
    public function about_guanggao()
    {
        $this->display();
    }

//---帮助中心
    public function about_help()
    {
        $this->display();
    }

//---站点地图
    public function sitemap()
    {
        $classTable = M('Class');
        $map['pid'] = '4';
        $classNames = $classTable->where($map)->select();
        // dump($classNames);
        // exit;

        $shopTable = M('Shop');
        $shopNames = $shopTable->select();
        // dump($shopNames);
        // exit;
        
        
        $map_l['is_ershou'] = 0;
        $lp_list = M('New_loupan')->field('info_id,lpname')->where($map_l)->findall();
        $this->assign('lp_list', $lp_list);
        
        //--获取最新新闻300条
        $map_n['ispublish'] = 1;  //业已发布
        $news_list = M('News')->field('id,title,add_time')->where($map_n)->limit('0,100')->order('id desc')->findall();
        $this->assign('news_list', $news_list);
        $this->assign('class', $classNames);
        $this->assign('shopnames', $shopNames);


        $this->display();
    }
    
//--友情链接
    public function link()
    {
        $linkT=M('friend_link');
        $map['passed']=1;
        $list=$linkT->where($map)->order('sortrank DESC')->select();
        $this->assign('list', $list);
        $this->display();
    }
}
?>