<?php
//路由，用于URL优化 -- xiongyan
return array(

	array('/^lp$/','General/ct_list','',''),  //楼盘列表
	array('/^lp\/str\/(.+)/','General/ct_list','str',''),  //楼盘列表搜索1
	array('/^lp\/key\/(.+)/','General/ct_list','key',''),  //楼盘列表搜索2
	array('/^lp\-(\d+)$/','General/ct_view','info_id',''),  //楼盘详细页

	array('/^series\-(\d+)$/','News/series_view','id',''),   //套系详细
	array('/^pics\-(\d+)$/','Pic/view','id',''),   //相册图片详细
	
	array('/^news\-(\d+)$/','News/view','id',''),     //新闻详细页
	array('/^shop\-(\d+)$/','Shop/view','id',''),     //商家详细页
	array('/^story\-(\d+)$/','Story/view','id',''),   //故事详细页
	array('/^star\-(\d+)$/','Star/view','id',''),     //名人详细页
	array('/^blog\-(\d+)$/','Showlove/view','id',''),     //博客(婚礼晒台)详细页

	array('/^news\-list\-(\d+)$/','News/news_list','cid',''),       //普通新闻列表页
	array('/^news\-list\-([a-z]+)$/','News/news_list','cid',''),    //爱情故事等特殊列表
	array('/^shop\-list\-(\d+)$/','Shop/index','cid',''),     //商家列表页

);
?>