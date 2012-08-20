<?php
/***By xiongyan -------------
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function ZHsubstr($str, $start=0, $length, $charset="utf-8")
{
    $len = strlen($str); //原字节数
    if($len > $length)
        $suffixStr = "…";
    else
        $suffixStr = "";

    if(function_exists("mb_substr"))
        return mb_substr($str, $start, $length, $charset).$suffixStr;
    elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset).$suffixStr;
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    return $slice.$suffixStr;
}
/**
 * 返回格式化文件尺寸
 * @param Int $size 文件尺寸单位（B）
 * @author 熊彦 <cnxiongyan@gmail.com>
 */
function realSize($size)
{
    if ($size < 1024){
        return $size.' Byte';
    }
    if ($size < 1048576){
        return round($size / 1024, 2).' KB';
    }
    if ($size < 1073741824){
        return round($size / 1048576, 2).' MB';
    }
    if ($size < 1099511627776){
        return round($size / 1073741824, 2).' GB';
    }
}
/**
 * @把全角数字转为半角数字
 * @参数: fnum 原始字符串
 * @author 熊彦 <cnxiongyan@gmail.com>
 */
    function getAlabNum($fnum){
        $nums = array('０','１','２','３','４','５','６','７','８','９','．','－','＋','：');
        $fnums = array('0','1',  '2','3',  '4','5',  '6', '7','8',  '9','.',  '-', '+',':');
        $fnlen = count($fnums);
        for($i=0;$i<$fnlen;$i++) $fnum = str_replace($nums[$i],$fnums[$i],$fnum);
        $slen = strlen($fnum);
        $oknum = '';
        for($i=0;$i<$slen;$i++){
            if(ord($fnum[$i]) > 0x80) $i++;
            else $oknum .= $fnum[$i];
        }
        if($oknum=="") $oknum=0;
        return $oknum;
    }
//---获取一串中文字符的拼音 ishead=0 时，输出全拼音 ishead=1时，输出拼音首字母
    function getPinyin($str,$ishead=0,$isclose=1){
    global $pinyins;
    $db_path = realpath('./Public');
    $db_path = str_replace("\\","/",$db_path);
    $db_file = $db_path.'/Data/pinyin.db';
    $restr = "";
    $str = iconv('UTF-8','GBK',$str);  //转成GBK才能使用
    $str = trim($str);
    $slen = strlen($str);
    if($slen<2) return $str;
    if(count($pinyins)==0){
        $fp = fopen($db_file,"r");
        while(!feof($fp)){
            $line = trim(fgets($fp));
            $pinyins[$line[0].$line[1]] = substr($line,3,strlen($line)-3);
        }
        fclose($fp);
    }
    for($i=0;$i<$slen;$i++){
        if(ord($str[$i])>0x80)
        {
            $c = $str[$i].$str[$i+1];
            $i++;
            if(isset($pinyins[$c])){
                if($ishead==0) $restr .= $pinyins[$c];
                else $restr .= $pinyins[$c][0];
            }else $restr .= "-";
        }else if( eregi("[a-z0-9]",$str[$i]) ){ $restr .= $str[$i]; }
        else{ $restr .= "-";  }
    }
    if($isclose==0) unset($pinyins);
    return $restr;
    }

//通过自定义类名获得类别ID
    function getClassId($classname){
        if(!isset($classname)){
            return false;
        }
        $class = D('Class');
        $where['name'] = $classname;
        if($classid = $class->where($cname)->field('id,level')->find()){
            return $classid['id'];
        }else{
            return false;
        }
    }
    
//通过类别ID返回自定义类名
    function getClassName($classid){
        if(!isset($classid)){
            return false;
        }
        if($classid==0)
        {
            return '未知类别';
        }
        $class = D('Class');
        $where['id'] = intval($classid);
        if($vo = $class->where($where)->field('id,name')->find()){
            return $vo['name'];
        }else{
            return false;
        }
    }

//获得字段创建信息
function getFieldMake($dtype,$fieldname,$dfvalue,$mxlen)
{
    $fields = array();
    if($dtype=="int"||$dtype=="datetime"){
        if($dfvalue=="" || ereg("[^0-9-]",$dfvalue)){ $dfvalue = 0; }
        $fields[0] = " `$fieldname` int(11) NOT NULL default '$dfvalue';";
        $fields[1] = "int(11)";
  }else if($dtype=="float"){
      if($dfvalue=="" || ereg("[^0-9\.-]",$dfvalue)){ $dfvalue = 0; }
      $fields[0] = " `$fieldname` float NOT NULL default '$dfvalue';";
        $fields[1] = "float";
  }else if($dtype=="img"||$dtype=="media"||$dtype=="addon"||$dtype=="imgfile"){
        if(empty($dfvalue)) $dfvalue = '';
        if($mxlen=="") $mxlen = 200;
        if($mxlen > 255) $mxlen = 100;
        $fields[0] = " `$fieldname` varchar($mxlen) NOT NULL default '$dfvalue';";
        $fields[1] = "varchar($mxlen)";
  }else if($dtype=="multitext"||$dtype=="htmltext"){
        $fields[0] = " `$fieldname` mediumtext;";
        $fields[1] = "mediumtext";
  }else if($dtype=="textdata"){
        if(empty($dfvalue)) $dfvalue = '';
        $fields[0] = " `$fieldname` varchar(100) NOT NULL default '';";
        $fields[1] = "varchar(100)";
  }else if($dtype=="textchar"){
        if(empty($dfvalue)) $dfvalue = '';
        $fields[0] = " `$fieldname` char(100) NOT NULL default '$dfvalue';";
        $fields[1] = "char(100)";
  }
  else{
        if(empty($dfvalue)) $dfvalue = '';
        if(empty($mxlen)) $mxlen = 100;
        if($mxlen > 255) $mxlen = 250;
        $fields[0] = " `$fieldname` varchar($mxlen) NOT NULL default '$dfvalue';";
        $fields[1] = "varchar($mxlen)";
  }
  return $fields;
}

//---输出管理员级别函数---
function getAdminGrp($x)  
{
      switch ($x){
        case 1:  $str="超级管理员";
          break;
        case 2:  $str="普通管理员";
          break;
        case 5:   $str="网站客服";
         break; 
    default:  $str= false ;       
      }
    return $str;  
}  

function safe_b64encode($string) {
   $data = base64_encode($string);
   $data = str_replace(array('+','/','='),array('-','_',''),$data);
   return $data;
}

function safe_b64decode($string) {
   $data = str_replace(array('-','_'),array('+','/'),$string);
   $mod4 = strlen($data) % 4;
   if ($mod4) {
       $data .= substr('====', $mod4);
   }
   return base64_decode($data);
}

//文本转HTML
    function text2html($string){
        $src = array('"', '\'', "\t", "\r", '{', '}');
        $tar = array('&quot;', '&#39;', '&nbsp;&nbsp;', '<br/>', '&#123;', '&#125;');
        $string = str_replace($src , $tar, $string);
        return $string;
    }

    //获得HTML里的文本
    function html2text($str){
        $str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU",'',$str);
        $str = str_replace(array('<br />','<br>','<br/>'), "\r\n", $str);
        $str = strip_tags($str);
        return $str;
    }

    //清除HTML标记
    function clearHtml($str){
        $str = html2text($str);
        $str = str_replace('<','&lt;',$str);
        $str = str_replace('>','&gt;',$str);
        return $str;
    }


/**
 * 转换网址
 */
function cvhttp($http){
    if ($http==''){
        return $http;
    }else {
        $link=substr($http,0,7)=="http://"?$http:'http://'.$http;
        return $link;
    }
    
}
function htmlCv($string) {
    $pattern = array('/(javascript|jscript|js|vbscript|vbs|about):/i','/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop)/i','/<script([^>]*)>/i','/<iframe([^>]*)>/i','/<frame([^>]*)>/i','/<link([^>]*)>/i','/@import/i');
    $replace = array('','','&lt;script${1}&gt;','&lt;iframe${1}&gt;','&lt;frame${1}&gt;','&lt;link${1}&gt;','');
    $string = preg_replace($pattern, $replace, $string);
    $string = str_replace(array('</script>', '</iframe>', '&#'), array('&lt;/script&gt;', '&lt;/iframe&gt;', '&amp;#'), $string);
    return stripslashes($string);
}
/**
 * 短信接口
 * BY 黄妃
 $mobilelist: 手机号 数组
 $text: 短信内容 长度小于74
 */
function MobilePost($mobilelist,$text) {
    $urlPost = 'http://yxtsms.cn/smsComputer/smsComputersend.asp?zh=wendao&mm=654321&dxlbid=8&';//hm=15870666812&nr=heihei
    if(strlen($text)>74){
        $text = mb_substr($text,0,74,'utf8');
    }
    foreach($mobilelist as $vo){
        $urlPost = $urlPost . 'hm=' . $vo . '&nr=' . $text ;
        $iRet = file_get_contents($urlPost);//发送成功返回0
    }
    return true;
}

//---获取一个区域信息 [ 参数：id ]
function getAreaName($id){
    $map['id']   =   $id;
    $vo  =  M("Area")->where($map)->field('id,name')->find();
    return $vo['name'];
}

?>