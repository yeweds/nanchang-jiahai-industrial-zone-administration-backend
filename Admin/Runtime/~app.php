<?php  function ZHsubstr($str, $start=0, $length, $charset="utf-8") { $len = strlen($str); if($len > $length) $suffixStr = "…"; else $suffixStr = ""; if(function_exists("mb_substr")) return mb_substr($str, $start, $length, $charset).$suffixStr; elseif(function_exists('iconv_substr')) { return iconv_substr($str,$start,$length,$charset).$suffixStr; } $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/"; $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/"; $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/"; $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/"; preg_match_all($re[$charset], $str, $match); $slice = join("",array_slice($match[0], $start, $length)); return $slice.$suffixStr; } function realSize($size) { if ($size < 1024){ return $size.' Byte'; } if ($size < 1048576){ return round($size / 1024, 2).' KB'; } if ($size < 1073741824){ return round($size / 1048576, 2).' MB'; } if ($size < 1099511627776){ return round($size / 1073741824, 2).' GB'; } } function getAlabNum($fnum){ $nums = array('０','１','２','３','４','５','６','７','８','９','．','－','＋','：'); $fnums = array('0','1', '2','3', '4','5', '6', '7','8', '9','.', '-', '+',':'); $fnlen = count($fnums); for($i=0;$i<$fnlen;$i++) $fnum = str_replace($nums[$i],$fnums[$i],$fnum); $slen = strlen($fnum); $oknum = ''; for($i=0;$i<$slen;$i++){ if(ord($fnum[$i]) > 0x80) $i++; else $oknum .= $fnum[$i]; } if($oknum=="") $oknum=0; return $oknum; } function getPinyin($str,$ishead=0,$isclose=1){ global $pinyins; $db_path = realpath('./Public'); $db_path = str_replace("\\","/",$db_path); $db_file = $db_path.'/Data/pinyin.db'; $restr = ""; $str = iconv('UTF-8','GBK',$str); $str = trim($str); $slen = strlen($str); if($slen<2) return $str; if(count($pinyins)==0){ $fp = fopen($db_file,"r"); while(!feof($fp)){ $line = trim(fgets($fp)); $pinyins[$line[0].$line[1]] = substr($line,3,strlen($line)-3); } fclose($fp); } for($i=0;$i<$slen;$i++){ if(ord($str[$i])>0x80) { $c = $str[$i].$str[$i+1]; $i++; if(isset($pinyins[$c])){ if($ishead==0) $restr .= $pinyins[$c]; else $restr .= $pinyins[$c][0]; }else $restr .= "-"; }else if( eregi("[a-z0-9]",$str[$i]) ){ $restr .= $str[$i]; } else{ $restr .= "-"; } } if($isclose==0) unset($pinyins); return $restr; } function getClassId($classname){ if(!isset($classname)){ return false; } $class = D('Class'); $where['name'] = $classname; if($classid = $class->where($cname)->field('id,level')->find()){ return $classid['id']; }else{ return false; } } function getClassName($classid){ if(!isset($classid)){ return false; } if($classid==0) { return '未知类别'; } $class = D('Class'); $where['id'] = intval($classid); if($vo = $class->where($where)->field('id,name')->find()){ return $vo['name']; }else{ return false; } } function getFieldMake($dtype,$fieldname,$dfvalue,$mxlen) { $fields = array(); if($dtype=="int"||$dtype=="datetime"){ if($dfvalue=="" || ereg("[^0-9-]",$dfvalue)){ $dfvalue = 0; } $fields[0] = " `$fieldname` int(11) NOT NULL default '$dfvalue';"; $fields[1] = "int(11)"; }else if($dtype=="float"){ if($dfvalue=="" || ereg("[^0-9\.-]",$dfvalue)){ $dfvalue = 0; } $fields[0] = " `$fieldname` float NOT NULL default '$dfvalue';"; $fields[1] = "float"; }else if($dtype=="img"||$dtype=="media"||$dtype=="addon"||$dtype=="imgfile"){ if(empty($dfvalue)) $dfvalue = ''; if($mxlen=="") $mxlen = 200; if($mxlen > 255) $mxlen = 100; $fields[0] = " `$fieldname` varchar($mxlen) NOT NULL default '$dfvalue';"; $fields[1] = "varchar($mxlen)"; }else if($dtype=="multitext"||$dtype=="htmltext"){ $fields[0] = " `$fieldname` mediumtext;"; $fields[1] = "mediumtext"; }else if($dtype=="textdata"){ if(empty($dfvalue)) $dfvalue = ''; $fields[0] = " `$fieldname` varchar(100) NOT NULL default '';"; $fields[1] = "varchar(100)"; }else if($dtype=="textchar"){ if(empty($dfvalue)) $dfvalue = ''; $fields[0] = " `$fieldname` char(100) NOT NULL default '$dfvalue';"; $fields[1] = "char(100)"; } else{ if(empty($dfvalue)) $dfvalue = ''; if(empty($mxlen)) $mxlen = 100; if($mxlen > 255) $mxlen = 250; $fields[0] = " `$fieldname` varchar($mxlen) NOT NULL default '$dfvalue';"; $fields[1] = "varchar($mxlen)"; } return $fields; } function getAdminGrp($x) { switch ($x){ case 1: $str="超级管理员"; break; case 2: $str="普通管理员"; break; case 5: $str="网站客服"; break; default: $str= false ; } return $str; } function safe_b64encode($string) { $data = base64_encode($string); $data = str_replace(array('+','/','='),array('-','_',''),$data); return $data; } function safe_b64decode($string) { $data = str_replace(array('-','_'),array('+','/'),$string); $mod4 = strlen($data) % 4; if ($mod4) { $data .= substr('====', $mod4); } return base64_decode($data); } function text2html($string){ $src = array('"', '\'', "\t", "\r", '{', '}'); $tar = array('&quot;', '&#39;', '&nbsp;&nbsp;', '<br/>', '&#123;', '&#125;'); $string = str_replace($src , $tar, $string); return $string; } function html2text($str){ $str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU",'',$str); $str = str_replace(array('<br />','<br>','<br/>'), "\r\n", $str); $str = strip_tags($str); return $str; } function clearHtml($str){ $str = html2text($str); $str = str_replace('<','&lt;',$str); $str = str_replace('>','&gt;',$str); return $str; } function cvhttp($http){ if ($http==''){ return $http; }else { $link=substr($http,0,7)=="http://"?$http:'http://'.$http; return $link; } } function htmlCv($string) { $pattern = array('/(javascript|jscript|js|vbscript|vbs|about):/i','/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop)/i','/<script([^>]*)>/i','/<iframe([^>]*)>/i','/<frame([^>]*)>/i','/<link([^>]*)>/i','/@import/i'); $replace = array('','','&lt;script${1}&gt;','&lt;iframe${1}&gt;','&lt;frame${1}&gt;','&lt;link${1}&gt;',''); $string = preg_replace($pattern, $replace, $string); $string = str_replace(array('</script>', '</iframe>', '&#'), array('&lt;/script&gt;', '&lt;/iframe&gt;', '&amp;#'), $string); return stripslashes($string); } function MobilePost($mobilelist,$text) { $urlPost = 'http://yxtsms.cn/smsComputer/smsComputersend.asp?zh=wendao&mm=654321&dxlbid=8&'; if(strlen($text)>74){ $text = mb_substr($text,0,74,'utf8'); } foreach($mobilelist as $vo){ $urlPost = $urlPost . 'hm=' . $vo . '&nr=' . $text ; $iRet = file_get_contents($urlPost); } return true; } function getAreaName($id){ $map['id'] = $id; $vo = M("Area")->where($map)->field('id,name')->find(); return $vo['name']; } return array ( 'app_debug' => false, 'app_domain_deploy' => false, 'app_sub_domain_deploy' => false, 'app_plugin_on' => false, 'app_file_case' => false, 'app_group_depr' => '.', 'app_group_list' => '', 'app_autoload_reg' => false, 'app_autoload_path' => 'Think.Util.', 'app_config_list' => array ( 0 => 'taglibs', 1 => 'routes', 2 => 'tags', 3 => 'htmls', 4 => 'modules', 5 => 'actions', ), 'cookie_expire' => 2592000, 'cookie_domain' => '.0791hunqing.com', 'cookie_path' => '/', 'cookie_prefix' => 'h_', 'default_app' => '@', 'default_group' => 'Home', 'default_module' => 'Index', 'default_action' => 'index', 'default_charset' => 'utf-8', 'default_timezone' => 'PRC', 'default_ajax_return' => 'JSON', 'default_theme' => 'default', 'default_lang' => 'zh-cn', 'db_type' => 'mysqli', 'db_host' => 'localhost', 'db_name' => 'kh_hunqing', 'db_user' => 'hunqing', 'db_pwd' => '129456', 'db_port' => '3306', 'db_prefix' => 'h_', 'db_suffix' => '', 'db_fieldtype_check' => false, 'db_fields_cache' => true, 'db_charset' => 'utf8', 'db_deploy_type' => 0, 'db_rw_separate' => false, 'data_cache_time' => -1, 'data_cache_compress' => false, 'data_cache_check' => false, 'data_cache_type' => 'File', 'data_cache_path' => './Admin/Runtime/Temp/', 'data_cache_subdir' => false, 'data_path_level' => 1, 'error_message' => '您浏览的页面暂时发生了错误！请稍后再试～', 'error_page' => '', 'html_cache_on' => false, 'html_cache_time' => 60, 'html_read_type' => 0, 'html_file_suffix' => '.shtml', 'lang_switch_on' => true, 'lang_auto_detect' => true, 'log_exception_record' => true, 'log_record' => false, 'log_file_size' => 2097152, 'log_record_level' => array ( 0 => 'EMERG', 1 => 'ALERT', 2 => 'CRIT', 3 => 'ERR', ), 'page_rollpage' => 5, 'page_listrows' => 20, 'session_auto_start' => true, 'show_run_time' => false, 'show_adv_time' => false, 'show_db_times' => false, 'show_cache_times' => false, 'show_use_mem' => false, 'show_page_trace' => false, 'show_error_msg' => true, 'tmpl_engine_type' => 'Think', 'tmpl_detect_theme' => false, 'tmpl_template_suffix' => '.html', 'tmpl_content_type' => 'text/html', 'tmpl_cachfile_suffix' => '.php', 'tmpl_deny_func_list' => 'echo,exit', 'tmpl_parse_string' => '', 'tmpl_l_delim' => '{', 'tmpl_r_delim' => '}', 'tmpl_var_identify' => 'array', 'tmpl_strip_space' => false, 'tmpl_cache_on' => true, 'tmpl_cache_time' => -1, 'tmpl_action_error' => 'Public:success', 'tmpl_action_success' => 'Public:success', 'tmpl_trace_file' => './ThinkPHP/Tpl/PageTrace.tpl.php', 'tmpl_exception_file' => './ThinkPHP/Tpl/ThinkException.tpl.php', 'tmpl_file_depr' => '/', 'taglib_begin' => '<', 'taglib_end' => '>', 'taglib_load' => true, 'taglib_build_in' => 'cx', 'taglib_pre_load' => '', 'tag_nested_level' => 3, 'tag_extend_parse' => '', 'token_on' => false, 'token_name' => '__hash__', 'token_type' => 'md5', 'url_case_insensitive' => false, 'url_router_on' => false, 'url_route_rules' => array ( ), 'url_model' => 1, 'url_pathinfo_model' => 2, 'url_pathinfo_depr' => '/', 'url_html_suffix' => '', 'var_group' => 'g', 'var_module' => 'm', 'var_action' => 'a', 'var_router' => 'r', 'var_page' => 'p', 'var_template' => 't', 'var_language' => 'l', 'var_ajax_submit' => 'ajax', 'var_pathinfo' => 's', 'cfg_sitename' => '南昌婚庆网', 'cfg_shortname' => '0791hunqing', 'cfg_domain' => 'http://www.0791hunqing.com', 'cfg_company' => '南昌婚庆网', 'cfg_icp' => '赣ICP备10201200号-12', 'cfg_tel' => '', 'cfg_address' => '', 'cfg_status' => 1, 'cfg_stop_msg' => '对不起,系统正在调试中...', 'cfg_logintime' => 3600, 'cfg_allow_reg' => true, 'cfg_title' => '婚庆网', 'cfg_metakeyword' => '婚庆,南昌婚庆', 'cfg_design' => 'tengfang.Net', 'cfg_version' => 1.1, 'html_url_suffix' => '.shtml', 'time_zone' => 'PRC', 'check_file_case' => true, 'web_log_record' => false, 'cfg_img_path' => '__PUBLIC__/Upload/', 'cfg_auth_key' => 'uid', 'list_tpl' => array ( 0 => array ( 0 => 'index', 1 => '标准列表页', ), 1 => array ( 0 => 'news_index', 1 => '一般用于新闻列表页', ), 2 => array ( 0 => 'pic_index', 1 => ' 一般用于相册列表页', ), ), 'view_tpl' => array ( 0 => array ( 0 => 'index', 1 => '标准列表页', ), 1 => array ( 0 => 'nv_index', 1 => '一般用于栏目详细页', ), 2 => array ( 0 => 'news_index', 1 => '一般用于新闻详细页', ), 3 => array ( 0 => 'pic_index', 1 => ' 一般用于相册详细页', ), 4 => array ( 0 => 'us_index', 1 => '一般用于联系我们详细页', ), ), ); ?>