<?php
	if (!defined('THINK_PATH')) exit();
	$config	=	require './config.php';  //导入共享配置
	$config['URL_ROUTER_ON'] = true;
	return $config;
?>