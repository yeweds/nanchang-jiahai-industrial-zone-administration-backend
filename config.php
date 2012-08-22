<?php 
if (!defined('THINK_PATH')) exit();
//载入自定义网站配置
$web_config =   require 'web_config.php';

//设定项目配置
$array  =   array(
    'DB_TYPE'           =>'mysqli',
    'DB_HOST'           =>'localhost',
    'DB_NAME'           =>'gyc',
    'DB_USER'           =>'root',
    'DB_PWD'            =>'yuyouleon',
    'DB_PORT'           =>'3306',    
    'DB_PREFIX'         =>'h_',    //表前缀
    //标准URL
    'URL_MODEL'         =>2,       //2 去index
    'APP_DEBUG'         => false, //调试开关
    'HTML_CACHE_ON'     =>  false,       // 默认关闭静态缓存
    'HTML_URL_SUFFIX'   =>'.shtml', 
    'TIME_ZONE'         =>'PRC',
    'APP_DOMAIN_DEPLOY' => false,  //是否布署在根目录下
    'CHECK_FILE_CASE'   => true,  //大小写检查
    'WEB_LOG_RECORD'    => false, //关闭日志
    
    /* 语言设置 */
    'DEFAULT_LANG'      => 'zh-cn', // 默认语言 
    'LANG_SWITCH_ON'    => true,   // 默认关闭多语言包功能
    'LANG_AUTO_DETECT'  => true,   // 自动侦测语言 开启多语言功能后有效
    
    'COOKIE_EXPIRE'     =>  3600,     // Cookie有效期之前一个月，现在改成1个小时
    // 'COOKIE_DOMAIN'     =>  '', // Cookie有效域名
    'COOKIE_PATH'       =>  '/',            // Cookie路径
    'COOKIE_PREFIX'     =>  'h_', // Cookie前缀 避免冲突
    
    'cfg_img_path'      =>  '__PUBLIC__/Upload/',   //上传目录
    'cfg_auth_key'      =>  'uid'   //用户权限控制Key 
);

//合并输出配置
return array_merge($web_config,$array);
?>