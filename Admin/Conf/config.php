<?php
    if (!defined('THINK_PATH')) exit();
    $config = require './config.php';  //导入共享配置
    $config['TOKEN_ON'] = false;
    $config['URL_MODEL'] = 1;   //2 去index
    
    //数据模板
    $config['LIST_TPL']=array(
        array('index','标准列表页'),                         //标准列表页
        array('news_index','一般用于新闻列表页'),     //一般用于新闻列表页
        array('pic_index',' 一般用于相册列表页')     //一般用于相册列表页
    );
    
    $config['VIEW_TPL']=array(
        array('index','标准列表页'),                         //标准列表页
        array('nv_index','一般用于栏目详细页'),          //一般用于栏目详细页
        array('news_index','一般用于新闻详细页'),     //一般用于新闻列表页
        array('pic_index',' 一般用于相册详细页'),            //一般用于相册列表页
        array('us_index','一般用于联系我们详细页')   //一般用于联系我们详细页
    );                                          
    
    return $config;
?>