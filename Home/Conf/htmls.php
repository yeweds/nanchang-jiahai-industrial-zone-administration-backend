<?php
//静态缓存，用于速度优化 -- xiongyan
return array(
	//'Index:index'=>array('{$_SERVER.REQUEST_URI|md5}', 50) //根据当前的URL进行缓存
	'Index:index'=>array('index', 60), //生成首页
	'News:index'=>array('news/index', 60) //生成新闻页
);
?>