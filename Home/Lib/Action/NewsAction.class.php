<?php
/**
 +------------------------------------------------------------------------------
 * News  新闻类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class NewsAction extends GlobalAction{
    
//每个广告以模板方式存放，按编号列顺序
    public $AD=array(
        'news_index_top'=>'Ad:news_index_top',               //新闻首页顶部广告
        'news_index_center'=>'Ad:news_index_center',        //新闻首页中间广告
        'news_com_top'=>'Ad:news_com_top',                  //新闻共用头部广告
        'news_list_right'=>'Ad:news_list_right',                    //新闻列表页面右边栏广告
        'news_xi_right'=>'Ad:news_xi_right',                        //新闻详细页面右边栏广告
        'news_xi_center'=>'Ad:news_xi_center',                      //新闻详细页面中间广告->放置在内容里面
        'index_search_end' =>'Ad:index_search_end',   //首页搜索下方广告 AD_search_end
    );

//栏目id配置
    public $COL = array(
        'jryw'    =>'jryw',  //今日要闻
        'rdtj'    =>'rdtj',  //热点推荐         
        'tdcr'    =>'tdcr',  //土地出让
        'tdcj'    =>'tdcj',  //土地成交
        'htpk'    =>'htpk',//话题pk
    );
    
//新闻首页
    public function index(){
        $this->index_pub(0);

         //广告
        $this->assign('AD_top',$this->AD['news_index_top']);        //新闻首页头部广告
        $this->assign('AD_center',$this->AD['news_index_center']);  //新闻首页中间广告
                     
        $this->display('News:index');
    }

//新闻首页
    public function index_pub($index_inc)
    {
        //是否嵌入首页 
        if($index_inc){
            $index_inc=1;   
        }else{
            $index_inc=0;
        }
        $this->assign('hide',$index_inc);
        /*栏目变量说明
            1.$dump['bssc']表示别墅市场栏目的数据内容(内部以数组形式存放)
                a.    $dump['bssc']['h']表示头条内容
                b.    $dump['bssc']['title']表示纯标题的内容数组
                c.    $dump['bssc']['id']表示栏目的id
                d.   $dump['bssc']['name']表示栏目的名字
        */
        $Brand = M("News");
        $Shop  = M('Shop');
        $class_pid = 5; //所有新闻栏目的父ID都是5

        //--幻灯片--> 幻灯片栏目ID：32
        $map['ispublish'] = 1 ;
        $map['flag'] = array('like','%f%'); 
        $map['pic_url'] = array('like','%.%');//由于图像中有的值为空，有的值为null,不好判断
        $dump['lp_hdp'] =$this->get_news_query_list($map,0,6,$field="id,title,remark,pic_url,redirecturl"); 
        $dump['lp_hdp']['h']=$dump['lp_hdp']['titles'][0];//头条
    
        //---------1. 今日要闻--------

        $dump['jryw'] = $this->jryw();  
        //dump($dump['jryw']);

        //---------奇闻轶事-->栏目ID:
        $dump['qwys'] =$this->get_news_list($class_id=30,0,11); 

        //----------爱情故事-->栏目ID:
        $dump['love'] =$this->get_news_list($class_id=20,0,11); //爱情故事
        
        //----------婚礼百货-->栏目ID:
        $dump['hlbh'] =$this->get_news_list($class_id=13,0,12); //婚礼百货
        
        //----------0791独家-->栏目ID:
        $dump['tfdj'] =$this->get_news_list($class_id=12,0,12); //0791独家
        
        //----------商家活动-->栏目ID:
        $dump['sjhd'] =$this->get_news_list($class_id=19,0,11,'add_time desc'); //商家活动

        //---------婚庆专题-->栏目ID:
        $dump['hqzt'] =$this->get_news_hp_list($class_id=14, 0,7);  //婚庆专题              
        
        //dump($dump);
        //---------7.热点推荐-->栏目ID:
        unset($map);
        //7.1先查头条       
        $map['ispublish'] = 1 ;
        $map['flag'] = array('like','%c%'); 
        //$map['flag'] = array('like','%p%'); 
        $map['pic_url'] = array('like','%.%');//由于图像中有的值为空，有的值为null,不好判断
        $dump['rdtj'] =$this->get_news_hp_list($map,0,9);   
        $dump['rdtj']['id']='rdtj'; 
        
        //---------婚礼资讯-->栏目ID:
        $dump['hlzx'] =$this->get_news_hp_list($class_id=26, 0,7);   //婚礼资讯

        //---------时尚婚礼-->栏目ID:
        $dump['sshl']=$this->get_news_hp_list($class_id=27, 0,7);   
        
        //---------婚礼秘书-->栏目ID:
        $dump['hlms'] =$this->get_news_hp_list($class_id=32, 0,7);  
        
        //---------婚俗礼仪-->栏目ID:
        $dump['hsly'] =$this->get_news_hp_list($class_id=28, 0,7);  

        //---------蜜月旅行 -->栏目ID:
        $dump['mylx'] =$this->get_news_hp_list($class_id=29, 0,7);  

        //---------婆媳关系-->栏目ID:
        $dump['pxgx'] =$this->get_news_hp_list($class_id=31, 0,7);  

        //---------婚房装修 -->栏目ID:
        $dump['hfzx'] =$this->get_news_hp_list($class_id=33, 0,7);  
        
        //---------育儿宝典 -->栏目ID:
        $dump['yebd'] =$this->get_news_hp_list($class_id=34, 0,7);  
        

        //---------最新套系-->自定义栏目
        if(S('NEWS_LP3D')){
            $dump['lp3d']=S('NEWS_LP3D');
        }else{
            unset($map);
            $lp3d = M("Series")->field("id,title,pic_url,price,add_time")->order("sortrank DESC")->limit("0,10")->select();
            S('NEWS_LP3D', $lp3d , 3600*24);
            $dump['lp3d'] = $lp3d;
            // dump($lp3d);
       }


        //---------商家点评(随机楼盘)   -->栏目ID:        
        if(S('NEWS_SJDP')){                 
            $dump['sjdp']=S('NEWS_SJDP');
        }else{
            $dump['sjdp']=A('Index')->index_review_list($ord = 'RAND', $num = 56); //随机 楼盘点评
            S('NEWS_SJDP', $dump['sjdp'] , 3600*24);
        }
        $dump['lpdp_ad']=A('Index')->index_review_list($ord = 'pr', $num = 7); //楼盘点评(广告)
        //dump($dump['lpdp_ad']);
        
        $dump['rqdp']=A('Index')->index_review_list($ord = 'hits', $num = 4); //人气点评 
        //dump($dump['rqdp']);
        
        $dump['prdp']=A('Index')->index_review_list($ord = 'count', $num = 4); //按评论数    
        //dump($dump['prdp']);
        $dump['zxdp']=A('Index')->index_review_list($ord = 'content', $num = 19); //按最新评论展示内容
        //dump($dump['zxdp']);
        
        
        //--图片展示南昌热点商家推荐 商家表中字段is_tuijian为1的标示推荐 按优先级进行排列
        unset($map);
        //$map['is_tuijian'] = 1 ;
        //$map['lp_state'] = 1 ;
        $shop_rdtj = $Shop->field('id,name,sub_title as lpname,tel,class_id,default_attach_id,logo_url')->where($map)->order('sortrank desc')->limit('0,10')->findAll(); //考虑随机调6条?
        foreach($shop_rdtj as $k=>$vo){
            
            //$picmap['id'] = $vo['default_attach_id'] ; 
            //$rspic = M('Attach')->field('savepath,savename')->where($picmap)->find();
            //$shop_rdtj[$k]['pic_url'] = trim($rspic['savepath'],'.').'thumb_'.$rspic['savename'] ;
            $att_rs = $vo['logo_url']; 
            if($att_rs != 'no.png'){
                $att_rs = $att_rs;  #不用缩略图 "thumb_".
            }
            $shop_rdtj[$k]['pic_url'] = C('cfg_img_path')."Shop/".$att_rs;
            if(empty($vo['tel'])){
                $shop_rdtj[$k]['tel'] = "待定" ;
            }   
        }
        $dump['shop_rdtj'] = $shop_rdtj ;   
        

        //-- 获取热点专题
        $rdzt_field = 'id,title,pic_url,sub_title,isshow_sub_title,title_color,add_time,redirecturl';
        $rdzt_map['class_id'] = 14; //20
        $rdzt_list = $this->get_news_query_list($rdzt_map,0,20,$rdzt_field); 
        //dump($rdzt_list);
        $this->assign('rdzt_list',$rdzt_list);
        $this->assign('title',$title);
        $this->assign('title_xchjsp',$title_xchjsp);

        //--话题PK-->
        unset($map);
        $Topic = M('Topic_pk');
        $map['isnew'] = 1 ;
        $rs = $Topic->where($map)->order('id desc')->find();
        $new_topic['id'] = $rs['id']; 
        if(mb_strlen($rs['title'],'utf8')>16){
            $new_topic['title'] = mb_substr($rs['title'],0,16,'utf8').'..';//内容只显示25个
        }else{
            $new_topic['title'] = $rs['title'];
        }
        $content = html2text($rs['content']);
        $content = str_replace('&nbsp;', '', $content);
        if(mb_strlen($content,'utf8')>34){
            $new_topic['content'] = mb_substr($content,0,34,'utf8').'...';//内容只显示25个
        }else{
            $new_topic['content'] =  $content ;
        }
        //$new_topic['r_content'] = mb_substr(html2text($rs['r_content']),0,23,'utf8').'...';//红方观点只显示14个
        //$new_topic['b_content'] = mb_substr(html2text($rs['l_content']),0,23,'utf8').'...';//蓝方观点只显示14个
        $new_topic['r_num'] = $rs['r_num'];
        $new_topic['b_num'] = $rs['l_num'];
        $new_topic['r_num_per'] = intval($rs['r_num']*100/($rs['r_num']+$rs['l_num']))*2; //红方百分比
        $new_topic['b_num_per'] =(100- intval($rs['r_num']*100/($rs['r_num']+$rs['l_num'])))*2; //蓝方百分比
        $dump['htpk'] = $new_topic;         
        

        unset($map);
        
        //----法律咨询 栏目id--
        $lawT = M('Lawyer_ask');
        $dump['law']=$lawT->query("SELECT t1.id as id,t1.title as ask,t2.content as reply from __TABLE__ t1 INNER JOIN h_lawyer_reply t2 ON(t1.id=t2.ask_id and t1.is_hide=0 and t2.content regexp '[^[:space:]]+') GROUP BY t2.ask_id ORDER BY t1.paixu desc,t1.time desc limit 0,20");
        //ask：提问的标题  content: 回复的内容 由于存在多条回复采用group by只显示一条
        $this->assign('news',$dump);

    }
    
//+++++++++++++++++start------新闻页面共用函数体+++++++++++++++++++++//
    //____正副标题替换---
    public function title_replace($t){
        foreach($t as $k=>$vo){
            if($vo['isshow_sub_title']){
                $t[$k]['title'] = $vo['sub_title'];
            }
        }
        return $t;  
    }
    
    //a.1获取新闻纯标题栏目数据
    public function get_news_list($class_id,$start,$length,$order='sortrank desc,add_time desc'){
        $table=M('News');
        $map['class_id'] =$class_id;    
        $map['ispublish'] = 1 ;
        $r['titles']=$table->field('id,title,sub_title,isshow_sub_title,title_color,redirecturl')->where($map)->order($order)->limit("$start,$length")->findAll();  

        //____正副标题替换---
        $r['titles']=$this->title_replace($r['titles']);            
            
        //查栏目名称
        $c_map['id'] = $class_id;
        $class = M('Class')->field('name')->where($c_map)->find();
        $r['name'] = $class['name'];        
        $r['id']=   $class_id;
                
        return $r;

    }
    
    //a.2获取新闻首标题带摘要的栏目数据
    public function get_news_h_list($class_id,$start,$length){
        $table=M('News');
        $map['class_id'] =$class_id;    
        $map['ispublish'] = 1 ;
        $r['h']= $table->field('id,title,remark,sub_title,isshow_sub_title,title_color,redirecturl')->where($map)->order('sortrank desc,add_time desc')->limit("$start,1")->find();
            
        //____正副标题替换---
        if($r['h']['isshow_sub_title']){
            $r['h']['title']=$r['h']['sub_title'];
        }           
        
        //再查其它的数据
        if($r['h']['id']!=NULL){  
            $map['id'] = array('neq',$r['h']['id']); //后面查的不包含头条
        }

        $r['titles']=$table->field('id,title,sub_title,isshow_sub_title,title_color,redirecturl')->where($map)->order('sortrank desc,add_time desc')->limit($start.','.($length-1))->findAll(); 
        
        //____正副标题替换---
        $r['titles']=$this->title_replace($r['titles']);            
        
        //查栏目名称
        $c_map['id'] = $class_id;
        $class = M('Class')->field('name')->where($c_map)->find();
        $r['name'] = $class['name'];        
        $r['id']=   $class_id;
                
        return $r;

    }
        
    //a.3获取新闻首标题带图片的栏目数据
    public function get_news_hp_list($class_id,$start,$length){
        $table=M('News');       
        if(is_array($class_id)){
            $map=$class_id;     
        }else{
            //先查头条      
            $map['class_id'] =$class_id;    
            $map['ispublish'] = 1 ;
            $map['pic_url'] = array('like','%.%');//由于图像中有的值为空，有的值为null,不好判断
        }
        $r['h']= $table->field('id,title,pic_url,remark,sub_title,isshow_sub_title,title_color,redirecturl')->where($map)->order('sortrank desc,add_time desc')->limit("$start,1")->find(); 
        
        //____正副标题替换---
        if($r['h']['isshow_sub_title']){
            $r['h']['title']=$r['h']['sub_title'];
        }           
        
        //再查其它的数据
        if($r['h']['id']!=NULL){  
            $map['id'] = array('neq',$r['h']['id']); //后面查的不包含头条
        }
        
        unset($map['pic_url']);
        $r['titles']=$table->field('id,title,sub_title,isshow_sub_title,title_color,redirecturl')->where($map)->order('sortrank desc,add_time desc')->limit($start.','.($length-1))->findAll(); 
        
        //____正副标题替换---
        $r['titles']=$this->title_replace($r['titles']);                
                
        //查栏目名称
        $c_map['id'] = $class_id;
        $class = M('Class')->field('name')->where($c_map)->find();
        $r['name'] = $class['name'];        
        $r['id']=   $class_id;
                
        return $r;

    }   
    
    //a.3获取栏目数据,可将条件与查询字段传入
    public function get_news_query_list($where,$start,$length,$field){
        
        $table=M('News');   
        if(isset($field)){
            $fields=$field; 
        }else{
           $fields='id,title,pic_url,sub_title,isshow_sub_title,title_color,redirecturl';
        }
            
        if(is_array($where)){
            $map=$where;        
        }else{
            //先查头条      
            $map['class_id'] =$class_id;    
            $map['ispublish'] = 1 ;
            $map['pic_url'] = array('like','%.%');//由于图像中有的值为空，有的值为null,不好判断
        }
        
        $r['titles']=$table->field($fields)->where($map)->order('sortrank desc,add_time desc')->limit("$start,$length")->findAll(); 
    
        //____正副标题替换---
        $r['titles']=$this->title_replace($r['titles']);        
            
        //查栏目名称
        $c_map['id'] = $class_id;
        $class = M('Class')->field('name')->where($c_map)->find();
        $r['name'] = $class['name'];        
        $r['id']=   $class_id;
                
        return $r;

    }       

    //今日要闻
    public function jryw(){
        
        $Brand = M("News"); 
        //---------1. 今日要闻--------
        //条件: 先查出最近一天的所有新闻,按权重,添加时间排序
        //1.1先查头条
        unset($map);
        $map['ispublish'] = 1 ;
        //如果是星期一的话就将时间延长1天，因为星期天休息是没有添加新闻的。
        if(date('w')=='1'){
            $time=strtotime(date('Y-m-d'))-17*24*60*60;
        }else{
            $time=strtotime(date('Y-m-d'))-15*24*60*60; 
        }
        
        $map['add_time'] = array('gt',$time);//显示今天和最近一天的新闻
        $map['flag'] = array(array('like','%h%'), array('like','%a%'));  //今日焦点
        $h= $Brand->field('id,title,sub_title,isshow_sub_title,title_color,redirecturl')->where($map)->order('sortrank desc,add_time desc')->limit('0,1')->find();  
        
        //____正副标题替换---
        $h['title']= ( $h['isshow_sub_title'] ? $h['sub_title'] : $h['title'] );    
        
        if(!$h['id']){
            $h['id']=0;
        }
        $map['id'] = array('neq',$h['id']); //后面查的不包含头条
        $map['flag'] = array(array('like','%h%'), array('like','%a%')); 
        $t=$Brand->field('id,title,sub_title,isshow_sub_title,title_color,redirecturl')->where($map)->order('sortrank desc,add_time desc')->limit('0,2')->findAll();    
        
        //____正副标题替换---
        $t=$this->title_replace($t);
            
        $tv=array($h['id']);
        //如果存在数据,放入一数组
        if($t){ 
            foreach($t as $v){
                array_push($tv,$v['id']);           
            }   
        }
        //1.2再查其它的数据
        $map['id'] =array('not in',$tv); //后面查的不包含头条
        $map['flag'] = array('like','%a%'); 
        $titles=$Brand->field('id,title,sub_title,isshow_sub_title,title_color,tag,redirecturl')->where($map)->order('sortrank desc,add_time desc')->limit('0,9')->findAll();       
        //____正副标题替换---
        $titles=$this->title_replace($titles);
        
        //传值
        $news['id'] = $this->COL['jryw'];
        $news['h'] = $h;
        $news['t'] = $t;        
        $news['titles'] = $titles;      
        return $news;   
        
    }
//+++++++++++++++++end------新闻页面共用函数体+++++++++++++++++++++//    


//-------------------------------------首页ajax--------------------------------------------------
    //---今日要闻与全国楼市栏目-------
    public function ajax_jryw(){
        $key=$_GET['name'];
        $newsTable=M('News')    ;   
        
        if($key=='jryw'){
            //---------1. 今日要闻--------
                    $dump['jryw'] = $this->jryw();  
        }else{
                    
            //---------2.全国楼市-->栏目ID:
                    //2.1先查头条
                    $map['class_id'] =$this->COL['qgls'];
                        
                    $map['ispublish'] = 1 ;
                    //$map['add_time'] = array('gt',strtotime(date('Y-m-d'))-18*60*60);//显示今天和最近一天的新闻
                    $map['flag'] = array('like','%h%'); 
                    $h= $newsTable->field('id,title,sub_title,isshow_sub_title,title_color')->where($map)->order('sortrank desc,add_time desc')->limit('0,1')->find();          
                    //____正副标题替换---
                    if($h['isshow_sub_title']){
                        $h['title']=$h['sub_title'];
                    }                       
                    
                    //2.2再查其它的数据
                      if(!$h['id']){
                          $h['id']=0;
                      }
                    $map['id'] = array('neq',$h['id']); //后面查的不包含头条
                    $map['flag'] = array('like','%a%'); 
                    $t=$newsTable->field('id,title,sub_title,isshow_sub_title,title_color')->where($map)->order('sortrank desc,add_time desc')->limit('0,2')->findAll();    
                    //____正副标题替换---
                    $t=$this->title_replace($t);                    
                    
                    $tv=array($h['id']);
                    //如果存在数据,放入一数组
                    if($t){ 
                        foreach($t as $v){
                            array_push($tv,$v['id']);           
                        }   
                    }
                    
                    $map['id'] =array('not in',$tv); //后面查的不包含头条  
                    unset($map['flag']);
                    $titles=$newsTable->field('id,title,sub_title,isshow_sub_title,title_color,tag')->where($map)->order('sortrank desc,add_time desc')->limit('0,9')->findAll();
                    
                    //____正副标题替换---
                    $titles=$this->title_replace($titles);                  
                                
                    //传值
                    $dump['jryw']['id'] = $this->COL['qgls'];                   
                    $dump['jryw']['h'] = $h;
                    $dump['jryw']['t'] = $t;                        
                    $dump['jryw']['titles'] = $titles;  
        }
        
        $this->assign('news',$dump);
        $this->display();
        
        
    }
    
    //---热点推荐与房产专题栏目-------
    public function ajax_rdtj(){

        $key=$_GET['name'];
        if($key=='rdtj'){
                //---------7.热点推荐-->栏目ID:
                    //7.1先查头条       
                    $map['ispublish'] = 1 ;
                    $map['flag'] = array('like','%c%'); 
                    //$map['flag'] = array('like','%p%'); 
                    $map['pic_url'] = array('like','%.%');//由于图像中有的值为空，有的值为null,不好判断
                    $dump['rdtj'] =$this->get_news_hp_list($map,0,9);   
                    $dump['rdtj']['id']='rdtj';

        }else{
                    //---------8.房产专题-->栏目ID:
                    $dump['rdtj'] =$this->get_news_hp_list($this->COL['fczt'],0,9); 

        }
        
        $this->assign('news',$dump);
        $this->display();       
        
    }
    
    //---腾房快讯/大眼睛看世界栏目-------
    public function ajax_tfkx(){
                        
        $key=$_GET['name']; 
        $dump['tfkx'] =$this->get_news_list($this->COL[$key],0,12); 
                
        $this->assign('news',$dump);
        $this->display();       
        
    }       
    
   //---大嘴谈房/小编踩盘栏目-------
    public function ajax_dztf(){
                        
        $key=$_GET['name']; 
        $dump['dyj'] =$this->get_news_list($this->COL[$key],0,11);  
                
        $this->assign('news',$dump);
        $this->display();       
        
    }   

    //---行业观察/房企要闻/商业地产/别墅市场/政策法规/土地市场栏目-------
    public function ajax_hygc(){
                        
        $key=$_GET['name']; 
        $dump['hygc'] =$this->get_news_hp_list($this->COL[$key],0,7);
        $this->assign('news',$dump);
        $this->display();       
        
    }   
    
    //.土地出让/土地成交/楼盘规划栏目
    public function ajax_tdcr(){
        
        $key=$_GET['name']; 
        
        if($key=='tdcr'){       

            //------土地出让--- 
                    //土地出让
                    $Churang = M('Gtj_churang');
                    $titles= $Churang->field('id,title,sub_title,isshow_sub_title,title_color,add_time')->order('add_time desc')->limit('0,9')->findAll();  
                    //____正副标题替换---
                    $titles=$this->title_replace($titles);
                    $dump['tdcr'] ['titles'] =$titles;  
                    $dump['tdcr']['id']=$key;
                    
        }else if($key=='tdcj'){
                
            //------土地成交---
                 //土地交易
                $Gtj= D('Gtj');
                $list = $Gtj->field('id,bianhao,weizhi,jiaoyishijian,range_id')->order('id desc')->limit('0,8')->findAll();
                foreach($list as $k=>$vo){
                    $range_id=$vo['range_id'];
                    $range_name=D('area')->field('name')->where("id =$range_id")->find();
                    $list[$k]['range_name']=$range_name['name'];
    
                }   
                $dump['tdcr'] ['titles']=$list; 
                $dump['tdcr']['id']=$key;
                
        }else{//楼盘规划
            
                $dump['tdcr'] =$this->get_news_list($this->COL[$key],0,9);      
                        
        }
        $this->assign('name',$key);                         
        $this->assign('news',$dump);
        $this->display();                               

    }   
        
//---------------------------end----------首页ajax-----------------------------------------------------------
    
/*  ===============================================================    */
/*  ===================     公共函数                    ============================   */
/*  ===============================================================   */

/*
    前台JS传参返回新房价格查询
    (in)  $b: 起始价格
    (in)  $e: 结束价格
*/
    public function getrange_price($b,$e)
    {
        $b = trim($_GET['b']);
        $e = trim($_GET['e']);  
        $Phang = new PhangAction(); 
        if(!empty($b) && empty($e)){
            $list = $Phang->getlpbyprice($b,null);
            //dump($list);
        }
        if(!empty($b) && !empty($e)){
            $list = $Phang->getlpbyprice($b,$e);
            //dump($list);
        }
        if(empty($b) && !empty($e)){
            $list = $Phang->getlpbyprice(null,$e);
            //dump($list);
        }   
        $this->assign('list',$list);
        $this->display('getrange_price');
    }
/*
    根据新闻ID获取该新闻所有评论内容
    (in)  $id: 传入的新闻ID
    (out) $arr: 返回的评论内容
*/
    public function getnews_reply($id)
    {
        if(empty($id)){
            return null;
        }
        $map['newsid'] = $id ;
        $rs = D('news_reply')->where($map)->order('add_time desc')->findAll();
        foreach($rs as $vo){
            $arr[$index]['id'] = $vo['id'];
            $arr[$index]['newsid'] = $vo['newsid'];
            if(mb_strlen($vo['content'],'utf8')>100){
                $arr[$index]['content'] = mb_substr($vo['content'],0,100,'utf8').'..';
            }else{
                $arr[$index]['content'] = $vo['content'];
            }
            $arr[$index]['username'] = $vo['username'];
            $arr[$index]['zhichi'] = $vo['zhichi'];
            $arr[$index]['fandui'] = $vo['fandui'];
            $arr[$index]['add_time'] = date('Y年m月d日',$vo['add_time']);
            $index++;
        }
        return $arr ;
    }
/*
    一周新闻排行
    (in)  $len: 返回的数量
    (out) $arr: 返回的新闻
*/
    public function getnews_ph($len=10)
    {
        $News = M('News');
        $timee = strtotime(date('Y-m-d')) ;
        $timeb = strtotime(date('Y-m-d',strtotime("-7 day"))) ;
        $map['add_time']  = array('between',"$timeb,$timee");//大于
        $map['ispublish'] = 1 ;
        $rs = $News->where($map)->order("click desc")->limit("0,$len")->findAll();
        //echo $News->getLastSql();
        $index = 0 ;
        foreach($rs as $vo){
            $arr[$index]['id'] = $vo['id'];
            $arr[$index]['count'] = $index+1 ;
            if(mb_strlen($vo['title'],'utf8')>21){
                $arr[$index]['title'] = mb_substr($vo['title'],0,19,'utf8').'..';   
            }else{
                $arr[$index]['title'] = $vo['title'] ;   
            }
            $index++;
        }
        return $arr;
    }
/*
    获取今日最受关注的新闻
    (in)  $len: 返回的数量
    (out) $arr: 返回的新闻
*/
    public function getnews_zsgz($len=10)
    {
        $News = M('News');
        $map['ispublish'] = 1 ;
        $unixtime = mktime(0,0,0,date('m'),date('d'),date('Y'));
        //$map['flag'] = array('like','%h%'); 
        $map['add_time']  = array('egt',$unixtime); //时间大于今天的
        $rs = $News->where($map)->order('click desc')->limit("0,$len")->findAll();
        $index = 0 ;
        foreach($rs as $vo){
            $arr[$index]['id'] = $vo['id'];
            $arr[$index]['count'] = $index+1 ;
            if(mb_strlen($vo['title'],'utf8')>21){
                $arr[$index]['title'] = mb_substr($vo['title'],0,20,'utf8').'..';   
            }else{
                $arr[$index]['title'] = $vo['title'] ;   
            }
            $index++;
        }
        return $arr;
    }

/*
    根据新闻ID获取相关新闻阅读
    (in)  $id: 传入的新闻ID
    (in)  $len: 返回的数量
    (out) $arr: 返回和该新闻同样关键字的新闻
*/
    public function getnews_xgxw($id, $len=10)
    {
        if(empty($id)){
            return null;
        }
        $News = M('News');
        $sql = $contition= "";
        //先取该新闻的关键词     
        $map['id'] = $id ;
        $rs_keyword = $News->field('keywords')->where($map)->find();
        $keyword = $rs_keyword['keywords'];
        if(strpos($keyword,',')){//多个关键词
            $arr_contition = explode(',',$keyword);
            foreach($arr_contition as $val){
                $contition .= " keywords like '%$val%' or" ;
            }
            $contition = rtrim($contition,"or"); //去掉最后一个or
            $sql = "select id,title,add_time from __TABLE__ where ispublish=1 and id<>".$id." and (".$contition.") order by add_time desc limit 0,$len;" ;
        }else{//单关键词
            $contition .= " keywords like '%$keyword%' " ;
            $sql = "select id,title,add_time from __TABLE__ where ispublish=1 and id<>".$id." and (".$contition.") order by add_time desc limit 0,$len;" ;
        }
        //echo $sql;
        //读取类似关键词的新闻
        $list = $News->query($sql);
        foreach($list as $k=>$vo){
            if($vo['title'] == ''){
                unset($list[$k]);
            }
            $list[$k]['add_time'] = date('Y-m-d',$vo["add_time"]);
            if(mb_strlen($vo['title'],'utf8')>20){
                $list[$k]['title'] = mb_substr($vo['title'],0,20,'utf8').'..';   
            }
        }
        return $list;
    }
/*======================================================================================*/
/*========================      新闻相关函数         ===================================*/
/*======================================================================================*/
//显示某个栏目列表
    public function news_list()
    {
        $cid = $_GET['cid'];
        if( !is_numeric($cid) ){
            /*可能存一在些不是按id区分的栏目而是相对独立的栏目(以首拼音连接)
                'jryw'    =>'jryw',  //今日要闻
                'ldtj'    =>'ldtj',  //热点推荐         
                'love'    =>'love',  //爱情故事
            */  
            $classid = $cid; //非数字id区分的栏目
            switch($classid){
                case 'series': 
                    $this->series_list(); //套系
                    return;        
                case 'htpk':   
                    $this->htpk_list();
                    return;                                         
                case 'jryw':  //如果是星期一的话就将时间延长1天，因为星期天休息是没有添加新闻的。
                    if(date('w')=='1'){
                        $time=strtotime(date('Y-m-d'))-(18+24)*60*60;
                    }else{
                        $time=strtotime(date('Y-m-d'))-18*60*60;    
                    }
                    $wmap['add_time'] = array('gt',$time);//显示今天和最近一天的新闻    
                    $now_title  ='今日焦点';    
                    break;
                case 'rdtj':            
                    $wmap['flag'] = array('like','%c%'); 
                    $now_title  ='热点推荐';
                    break;
            }
                

        }else{
            $classid = intval($cid);
        }
        
        if(empty($classid)){
            $this->error('参数错误');   
        }       

        if($_GET['rows']){
            $listRows = intval($_GET['rows']);;
        }else{
            $rows=20;
        }

        $News = M('News');
        //今日最受关注
        $dump['news_gz'] = $this->getnews_zsgz() ;
        //--热点专题-->热点专题栏目ID：20
        $dump['rdzt'] = $this->get_news_hp_list($class_id=14 ,0,10);           
        if(!$wmap){ 
            //所属栏目名称
            $map['id'] = $classid;
            $partname = M('Class')->field('name,title_content,meta_keyword,description')->where($map)->find();
            $dump['partname'] = $partname['name'] ;
            $dump['partid'] = $classid ;
             //查出标题----------------------------
            unset($map);
            $wmap['class_id']=$classid;
        }else{
            $dump['partname'] = $now_title;
            $dump['partid']   = $classid;
        }
        $wmap['ispublish'] = 1; 
        $map=$wmap; 

        //分页开始  
        //=====显示分页=======
        import("ORG.Util.Page");
        $totalRows = $News->where($map)->count(); //1.总的记录数
        $listRows= $rows;                         //2.每页显示的条数
        $p  = new Page( $totalRows, $listRows );
        $page= $p->show(); 
        //=====end 分页=====

        //要据当前页面显示相应条数标签
        $list =  $News->field('id,title,redirecturl,add_time')->where($map)->order('add_time desc')->limit($p->firstRow.','.$p->listRows)->findall();  //默认按照时间倒序   

        $this->assign('list',$list); //新闻标题列表
        $this->assign('page',$page);    
            

         //广告
        $this->assign('AD_top',$this->AD['news_com_top']);           //新闻首页头部广告
        $this->assign('AD_right',$this->AD['news_list_right']);         //新闻列表页面右边栏广告

        //获取推荐商家列表
        if(S('NEWS_SHOP')){
            $dump['tj_shop']=S('NEWS_SHOP');
        }else{
            $dump['tj_shop']=A('Index')->get_shop_list( $num = 12, $class_id=0, $is_pic=False);     
            S('NEWS_SHOP', $dump['tj_shop'] , 3600*24);
        }

        $this->assign('news',$dump);
        $this->display('news_list');
    }

    public function news_pai_ajax()
    {
        $type = trim($_GET['type']);
        if($type==1){
            $arr = $this->getnews_zsgz();
        }
        if($type==0){
            $arr = $this->getnews_ph();
        }
        $this->assign('news_gz',$arr);
        $this->display('news_pai_ajax');
    }
    
//显示某条新闻
    public function view()
    {
        $newsid = intval($_GET['id']);

        if(empty($newsid)){
            $this->error('参数错误');   
        }
        //新闻点击量加1
        //该新闻详细信息
        $News = D('News');
        $rs = $News->setInc('click','id='.$newsid,'1');  //更新人气
        //记录点击历史,5代表新闻

        $map['id'] = $newsid ;
        $map['ispublish'] = 1 ;
        $list = $News->where($map)->find();
        if(!$list) $this->error('您请求的页面不存在或已被删除！');

        $username = M('User')->where('id='.$list["user_id"])->find();
        $list["username"] = $username['username'];
        if($list['source']==''){
            $list['source'] = '南昌婚庆网';
        }
        if($list['author']==''){
            $list['author'] = '小编';
        }
        if(empty($list['pic_url'])){
            $list['pic_url'] = '';
        }
        $list["remark"] = trim($list["remark"]);
        $list["add_time"] = date('Y-m-d H:i:s',$list["add_time"]);
        //正文分页start
        $text_arr = explode("<div>###newpage###</div>", $list['content']);
        $page_sum = count($text_arr);
        
        if($page_sum>1){
            $curr_cp = intval($_GET['cp']);
            if($curr_cp<1 || $curr_cp>$page_sum){
                $curr_cp = 1;
            }
            $list['content'] = $text_arr[$curr_cp-1];
            foreach($text_arr as $k=>$v){
                if($k+1 == $curr_cp){
                    $page_tmp[] = "<font color=red>[<a href=\"__APP__/news-".$newsid."?&cp=".($k+1)."\">".($k+1)."</a>]</font>";
                }else{
                    $page_tmp[] = "[<a href=\"__APP__/news-".$newsid."?&cp=".($k+1)."\">".($k+1)."</a>]";
                }
            }
            $text_page = implode("&nbsp;", $page_tmp);
            $this->assign('text_page',$text_page);
        }
        //正文分页end
        $dump['news'] = $list;
        //所属栏目名称
        $cid = $list['class_id'];
        $partname = getClassName($cid);
        $dump['cname'] = $partname;
        $dump['cid']   = $cid;
        //相关新闻阅读
        $dump['nextnew'] = $this->getnews_xgxw($newsid) ;
        //今日最受关注
        $dump['news_gz'] = $this->getnews_zsgz() ;
        //--热点专题-->热点专题栏目ID：20
        $dump['rdzt'] = $this->get_news_hp_list(14, 0,10); //婚庆专题
         
        //评论数
        unset($map);
		
		$reviews_list = A("Reviews")->getReviewsList($model_name = 'news', $num = 10, $info_id= $newsid ); //获取新闻点评
		if($reviews_list){
			$dump['news_reply'] = $reviews_list;
		}
		//dump($reviews_list);
		$dump['replynum'] = count($reviews_list) ;
        //评论内容
        $lp_reviews = A("Index")->index_review_list($ord = 'content', $num = 10); //商家点评内容
        $dump['lp_reviews'] = $lp_reviews;
        //dump($lp_reviews);    

         //广告
        //$this->assign('AD_top',$this->AD['news_com_top']);              //新闻公共头部广告
        $this->assign('AD_right',$this->AD['news_xi_right']);              //新闻详细页面右边栏广告
        //$this->assign('AD_center',$this->AD['news_xi_center']);         //新闻详细页面中间广告      

        //获取推荐商家列表
        if(S('NEWS_SHOP')){
            $dump['tj_shop']=S('NEWS_SHOP');
        }else{
            $dump['tj_shop']=A('Index')->get_shop_list( $num = 12, $class_id=0, $is_pic=False);     
            S('NEWS_SHOP', $dump['tj_shop'] , 3600*24);
        }

        $this->assign('news',$dump);
        // dump($dump);
        // halt();
        $this->display();
    }
    
//---------------------------------------------------土地出让相关函数------------------------------------------------//
    //最新套系栏目
    public function series_list()
    {
        $rows = 20;
        //所属栏目名称
        $dump['partname'] = '最新套系';
        $dump['partid'] = "series" ;    

        //今日最受关注
        $dump['news_gz'] = $this->getnews_zsgz() ;
        //--热点专题-->热点专题栏目ID：20
         $dump['rdzt'] = $this->get_news_hp_list($class_id=14 ,0,10);      
         

        $Churang= M('Series');
        //=====显示分页=======
        import("ORG.Util.Page");
        $totalRows = $Churang->where($map)->count(); //1.总的记录数
        $listRows= $rows;                         //2.每页显示的条数
        $p  = new Page( $totalRows, $listRows );
        $page= $p->show(); 
        //=====end 分页=====

        //要据当前页面显示相应条数标签
        $list =  $Churang->field('id,title,add_time')->order('add_time desc')->limit($p->firstRow.','.$p->listRows)->findall();  //默认按照时间倒序 
    
        $this->assign('list',$list); //新闻标题列表
        $this->assign('page',$page);        
        
        // 标题/关键字/描述
        $page_info['title']='南昌婚纱最新套系、婚礼、婚庆'.' - '.C('cfg_sitename'); 
        $page_info['keywords']='南昌婚纱最新套系、婚礼、婚庆'.' '.C('cfg_metakeyword');
        $page_info['description']='南昌婚庆网为您提供南昌婚纱最新套系、婚礼、婚庆优惠信息'.' '.C('cfg_metakeyword');
        $this->assign('page_info',$page_info);      

         //广告
        $this->assign('AD_top',$this->AD['news_com_top']);           //新闻首页头部广告
        $this->assign('AD_right',$this->AD['news_list_right']);         //新闻列表页面右边栏广告

        $this->assign('news',$dump);
        $this->display('series_list');
        
    }
    

   //显示某条土地出让
    public function series_view()
    {
        $newsid = intval($_GET['id']);
        if(empty($newsid)){
            $this->error('参数错误');   
        }
    
        //该土地也让详细信息
        $Churang = D('Series');
        $map['id'] = $newsid ;
        $list = $Churang->where($map)->find();

        $dump['news'] = $list;
        //所属栏目名称
        $dump['partname'] = '最新套系';
        $dump['partid'] = "series" ;
        //相关新闻阅读
        $dump['nextnew'] = $this->getnews_xgxw($newsid) ;
        //今日最受关注        
        $dump['news_gz'] = $this->getnews_zsgz() ;

        //--热点专题-->热点专题栏目ID：20
         $dump['rdzt'] = $this->get_news_hp_list($class_id=14 ,0,10)   ;
        
        
        // 标题/关键字/描述
        $page_info['title']=$list['title'].' - 资讯详情 - '.C('cfg_sitename'); 
        $page_info['keywords']= $list['title'].' '.C('cfg_metakeyword');
        $page_info['description']= mb_substr(strip_tags($list['content']),0,200,'utf-8').' '.C('cfg_metakeyword');
        $this->assign('page_info',$page_info);

         //广告
        $this->assign('AD_top',$this->AD['news_com_top']);              //新闻公共头部广告
        $this->assign('AD_right',$this->AD['news_xi_right']);              //新闻详细页面右边栏广告
        $this->assign('AD_center',$this->AD['news_xi_center']);         //新闻详细页面中间广告        

        $this->assign('news',$dump);
        $this->display();
        
    }       

    //---------------------------------------------end------土地出让相关函数------------------------------------------------//
    

//---------------------------------------------------土地成交相关函数------------------------------------------------//
    //显示土地成交栏目
    public function tdcj_list()
    {
        
        if($_GET['rows']){
            $listRows = intval($_GET['rows']);;
        }else{
            $rows=20;
        }

        $News = D('News');
        
        //所属栏目名称
        $dump['partname'] = '土地成交';
        $dump['partid'] = $this->COL['tdcj'] ;      

        //今日最受关注
        $dump['news_gz'] = $this->getnews_zsgz() ;
        //--热点专题-->热点专题栏目ID：20
         $dump['rdzt'] = $this->get_news_hp_list($class_id=14 ,0,10);  
            
         //查出标题----------------------------
        $Gtj= D('Gtj');
        //分页开始  
        //=====显示分页=======
        import("ORG.Util.Page");
        $totalRows = $Gtj->count(); //1.总的记录数
        $listRows= $rows;                         //2.每页显示的条数
        $p  = new Page( $totalRows, $listRows );
        $page= $p->show(); 
        //=====end 分页=====

        //要据当前页面显示相应条数标签
        $list =  $Gtj->field('id,bianhao,weizhi,jiaoyishijian')->order('id desc')->limit($p->firstRow.','.$p->listRows)->findall();  //默认按照时间倒序 
        $Area = D('Area');
        foreach($list as $k=>$vo){
            $range_id=$vo['range_id'];
            $range_name=$Area->field('name')->where("id = ".$range_id)->find();
            $list[$k]['range_name']=$range_name['name'];

        }   
        
        // 标题/关键字/描述
        $page_info['title']='南昌土地成交公告'.' - '.C('cfg_sitename'); 
        $page_info['keywords']='南昌土地成交'.' '.C('cfg_metakeyword');
        $page_info['description']='腾房网为您提供最新南昌房产土地成交'.' '.C('cfg_metakeyword');
        $this->assign('page_info',$page_info);
         
        $this->assign('list',$list); //新闻标题列表
        $this->assign('page',$page);        

         //广告
        $this->assign('AD_top',$this->AD['news_com_top']);           //新闻首页头部广告
        $this->assign('AD_right',$this->AD['news_list_right']);         //新闻列表页面右边栏广告

        $this->assign('news',$dump);
        $this->display('tdcj_list');
        
    }
    //---------------------------------------------end------土地成交相关函数------------------------------------------------//  
        
        
//---------------------------------------------------话题pk相关函数------------------------------------------------//
    //显示话题pk栏目
    public function htpk_list()
    {
        if($_GET['rows']){
            $listRows = intval($_GET['rows']);;
        }else{
            $rows=20;
        }

        $News = D('News');
        
        //所属栏目名称
        $dump['partname'] = '话题pk';
        $dump['partid'] = $this->COL['htpk'] ;      

        //今日最受关注
        $dump['news_gz'] = $this->getnews_zsgz() ;
        //--热点专题-->热点专题栏目ID：20
         $dump['rdzt'] = $this->get_news_hp_list($class_id=14 ,0,10);      
         
         //查出标题----------------------------
         $Topic=M('Topic_pk');
        //分页开始  
        //=====显示分页=======
        import("ORG.Util.Page");
        $totalRows = $Topic->count(); //1.总的记录数
        $listRows= $rows;                         //2.每页显示的条数
        $p  = new Page( $totalRows, $listRows );
        $page= $p->show(); 
        //=====end 分页=====

        //要据当前页面显示相应条数标签
        $list =  $Topic->field('id,title,add_time')->order('add_time desc')->limit($p->firstRow.','.$p->listRows)->findall();  //默认按照时间倒序   
    
        $this->assign('list',$list); //新闻标题列表
        $this->assign('page',$page);        
        
        // 标题/关键字/描述
        $page_info['title']='话题pk'.' - '.C('cfg_sitename').' - '.C('cfg_design'); 
        $page_info['keywords']='话题pk列表'.' - '.C('cfg_metakeyword');
        $page_info['description']='话题pk列表'.' - '.C('cfg_metakeyword');
        $this->assign('page_info',$page_info);      

         //广告
        $this->assign('AD_top',$this->AD['news_com_top']);           //新闻首页头部广告
        $this->assign('AD_right',$this->AD['news_list_right']);         //新闻列表页面右边栏广告


        //获取推荐商家列表
        if(S('NEWS_SHOP')){
            $dump['tj_shop']=S('NEWS_SHOP');
        }else{
            $dump['tj_shop']=A('Index')->get_shop_list( $num = 12, $class_id=0, $is_pic=False);     
            S('NEWS_SHOP', $dump['tj_shop'] , 3600*24);
        }
        $this->assign('news',$dump);
        // dump($dump);
        // exit;
        $this->display('htpk_list');
        
    }
    

   //显示某条话题pk
    public function htpk_view()
    {
        $topicid = intval($_GET['id']);
        if(empty($topicid)){
            $this->error('参数错误');   
        }
    
        //记录点击历史,5代表新闻  
            
        //该话题详细信息
        $Topic = D('Topic_pk');
        $map['id'] = $topicid ;
        $rs = $Topic->where($map)->find();
        $new_topic['id'] = $rs['id']; 
        $new_topic['title'] = $rs['title'];
        $new_topic['add_time'] = date('Y-m-d H:i:s',$rs['add_time']);
        $new_topic['content'] = $rs['content'];//mb_substr($rs['content'],0,20,'utf8');//内容只显示20个
        $new_topic['r_content'] = $rs['r_content'];//mb_substr($rs['r_content'],0,14,'utf8');//红方观点只显示14个
        $new_topic['l_content'] = $rs['l_content'];//mb_substr($rs['l_content'],0,14,'utf8');//红方观点只显示14个
        $new_topic['r_num'] = $rs['r_num'];
        $new_topic['l_num'] = $rs['l_num'];
        $new_topic['r_num_per'] = intval($rs['r_num']*100/($rs['r_num']+$rs['l_num']))."%"; //红方百分比
        $new_topic['l_num_per'] = intval($rs['l_num']*100/($rs['r_num']+$rs['l_num']))."%"; //红方百分比
        $dump['new_topic'] = $new_topic;
        
        //所属栏目名称
        $dump['partname'] = "话题PK"  ;
        $dump['partid'] = $this->COL['htpk'] ;

        //今日最受关注
        $dump['news_gz'] = $this->getnews_zsgz() ;
        //--热点专题-->热点专题栏目ID：20
         $dump['rdzt'] = $this->get_news_hp_list($class_id=14 ,0,10);
        
        // 标题/关键字/描述
        $page_info['title']=$new_topic['title'] .' - 资讯详情 - '.C('cfg_sitename').' - '.C('cfg_design'); 
        $page_info['keywords']= $new_topic['title'] .' - '.C('cfg_metakeyword');
        $page_info['description']= mb_substr(strip_tags($new_topic['title']),0,200,'utf-8').' - '.C('cfg_metakeyword');
        $this->assign('page_info',$page_info);               


         //广告
        $this->assign('AD_top',$this->AD['news_com_top']);              //新闻公共头部广告
        $this->assign('AD_right',$this->AD['news_xi_right']);              //新闻详细页面右边栏广告
        $this->assign('AD_center',$this->AD['news_xi_center']);         //新闻详细页面中间广告        


        //获取推荐商家列表
        if(S('NEWS_SHOP')){
            $dump['tj_shop']=S('NEWS_SHOP');
        }else{
            $dump['tj_shop']=A('Index')->get_shop_list( $num = 12, $class_id=0, $is_pic=False);     
            S('NEWS_SHOP', $dump['tj_shop'] , 3600*24);
        }
        $this->assign('news',$dump);
        $this->display('htpk_detail');
        
    }       

    //---------------------------------------------end------话题pk相关函数------------------------------------------------//      

//投票支持某个话题
    public function votetopic()
    {
        $topicid = intval($_GET['id']);
        $type = intval($_GET['tid']);
        if(empty($topicid)){
            $this->error('参数错误');   
        }
        $Model = new Model('Topic_pk');
        if($type=="1"){//红方          
           $rs = $Model->execute(" update __TABLE__ set r_num=r_num+1 where id=".$topicid);
           if($rs){
                $this->success("支持红方成功!");
           }
        }
        if($type=="0"){//蓝方
           $rs = $Model->execute(" update __TABLE__ set l_num=l_num+1 where id=".$topicid);
           if($rs){
                $this->success("支持蓝方成功!");
           }
        }
    }

//支持或者反对某条评论
    public function replyvote()
    {
        $replyid = intval(trim($_GET['id']));
        $tid = trim($_GET['tid']);
        //$username = trim($_POST['username']);
        if(empty($replyid)){
            $this->error('非法提交');
        }
        $Model = new Model("Topic_pk");
        if($tid==1){
            $rs = $Model->execute(" update __TABLE__ set zhichi=zhichi+1 where id=".$replyid);
            if($rs){
                $this->success("支持成功!");
            }else{
                $this->error("支持失败!");
            }
        }
        if($tid==0){
            $rs = $Model->execute(" update __TABLE__ set fandui=fandui+1 where id=".$replyid);
            if($rs){
                $this->success("反对成功!");
            }else{
                $this->error("反对失败!");
            }
        }
    }


    //---根据条件获取新闻列表 -- xiongyan
    private function getNewsList($class_id, $num, $is_classname=false, $title_len=20 ){
        $Table = M('News');
        $map['class_id'] = $class_id ; 
        $map['ispublish'] = 1 ;
        $list = $Table->field('id,title,class_id,remark,pic_url,add_time')->where($map)->order('pr desc, add_time desc')
            ->limit('0,'.$num )->findAll();
        if($is_classname){
            //显示类名
            foreach($list as $k=>$v){
                $c_map['id'] = $v['class_id'];
                $rs = M('Class')->field('name')->where($c_map)->find();
                $list[$k]['classname'] = $rs['name'];
            }
        }

        return $list;
    }
   
    
}
?>