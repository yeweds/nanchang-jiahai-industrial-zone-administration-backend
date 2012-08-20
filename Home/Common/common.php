<?php
	define('ShellSuffix','xiong'); 	//检验登录后缀常量
	
	//执行字符串中的变量
	function eval_str($cityname,$str){
    	eval( "\$str = \"$str\";" );
    	return $str;
	}
	//获取区域名称
	function getAreaName($cityid){
		$condition['id']    =    $cityid;
		$vo    =    M("Area")->where($condition)->field('id,name')->find();
		if($vo)   
			return $vo['name']; 
		else 
			return false; 
	}

	//UTF-8 转GB编码
	function utf82gb($utfstr){
		if(function_exists('iconv'))
		{
			return iconv('utf-8','gbk//ignore',$utfstr);
		}else 
			return '您的环境不支持转换编码';
	}
		
	//把全角数字转为半角数字
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
		$len = mb_strlen($str, $charset); //原字节数
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
		$str = str_replace(array('<br />','<br>','<br/>'), "\n", $str);
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

		//根据大类ID得到子类
	function getSmallClass($cid){
		$class = D('Class');
		$condition['pid']  =  $cid;
		$condition['ishide']  =  1;
		$vo = $class->where($condition)->field('id,pid,curr_path,name')->order('sortrank desc')->findAll();
		if($vo)
			return $vo;
		else
			return false;
	} 

//---输出用户级别函数---
function show_UserGroup($groupid)  
{
	  switch ($groupid){
		case 0:
		  $str="已锁定用户";
		  break;
		case 1:
		  $str="普通会员";
		  break;
		case 9:
		  $str="VIP用户";
		 break;
	default:
	  $str="未知用户";       
	  }
	return $str;  
}

//---检验用户是否登录---
function check_cookie()  
{
		if(Cookie::is_set('uid')){
			$map['id'] = Cookie::get('uid');
			$vo = M("User")->where($map)->find();
			return $vo;
		}else{
			return false;
		}
}

//获得分类表大类id
	function getBigclassid($class_id)
	{
		//$class_id :分类ID
         $dao  = D("Class");  //
         $vo = $dao->getById($class_id);
         if($vo){
		 	return $vo['pid']; 
         } else {
         	return false; 
         }
	}

	//获得分类表父类路径
	function getParent_path($class_id)
	{
		//$class_id :分类ID
         $dao  = D("Class");  //
         $vo = $dao->find($class_id);
         if($vo){
		 	return $vo['curr_path']; 
         } else {
         	return false; 
         }
	}

	//获得分类表类别名称
	//*By xiongyan *--------
	function getClassName($classid){          
  		if($classid==0){
  			return false;
  		}
			$condition['id']    =    $classid;
			$vo    =    M("Class")->where($condition)->field('id,name')->find();
		if($vo){
			return $vo['name'];
		}else{
  			return false;
  		}
	}

	//获取一串中文字符的拼音 ishead=0 时，输出全拼音 ishead=1时，输出拼音首字母
	function getPinyin($str,$ishead=0,$isclose=1){
	global $pinyins;
	$restr = "";
	$str=iconv('UTF-8','GBK',$str);  //转成GBK才能使用
	$str = trim($str);
	$slen = strlen($str);
	if($slen<2) return $str;
	if(count($pinyins)==0){
		$fp = fopen(__PUBLIC__."/pinyin_city.db","r");
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
		}else if( eregi("[a-z0-9]",$str[$i]) ){	$restr .= $str[$i]; }
		else{ $restr .= "-";  }
	}
	if($isclose==0) unset($pinyins);
	return $restr;
	}
	
function sendEmail ( $sendto_email,$subject, $body, $extra_hdrs=null) {
	include_once(APP_PATH."/Lib/Other/Phpmailer/class.phpmailer.php"); 
	$mail = new PHPMailer();
	$mail->IsSMTP();                // send via SMTP
	$mail->Host = C('cfg_smtp_host'); //SMTP服务器
	$mail->SMTPAuth = true;         // turn on SMTP authentication
	$mail->Username = C('cfg_mail_user');//SMTP服务器的用户帐号
	$mail->Password = C('cfg_mail_pwd');//SMTP服务器的用户密码

	$mail->From = C('cfg_email');//SMTP服务器的用户邮箱
	$mail->FromName = "Tengfang.Net";  // 发件人

	$mail->CharSet = "UTF-8";            // 这里指定字符集！
	$mail->Encoding = "base64";
	if(is_array($sendto_email)){
		foreach($sendto_email as $v){
			$mail->AddAddress($v);
		}
	}else{
		$mail->AddAddress($sendto_email);  // 收件人邮箱和姓名
	}
	$mail->AddReplyTo(C('cfg_email'),"Tengfang.Net");
	//$mail->WordWrap = 50; // set word wrap
	//$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");
	$mail->IsHTML(true);  // send as HTML
	// 邮件主题
	$mail->Subject = $subject;
	// 邮件内容
	$mail->Body = $body;
	$mail->AltBody ="text/html";
	if(!$mail->Send()){
		return false;
	}else {
		return true;
	}
}

//---显示友情链接函数---
//*By xiongyan *-------------
//参数 act：1文字，2图片,默认为文字
	function getLinks($act=1,$num=27){	
		$where['passed'] = 1;
		if($act == 2){
			$where['urllogo']= array('neq','');
		}else{
			$where['urllogo']   = array('eq','');
			//$map['urllogo']   = array('eq',"http://");
			//$map['_logic']  = 'or';
			//$where['_complex'] = $map;
		}

		$linkform = D('Friend_link');
		$links = $linkform->field('id,webname,url,urllogo')->where($where)->order("sortrank desc")->limit($num)->findall();
		if($links){
			foreach($links as  $v) {
				 if(empty($v['urllogo'])){
					$v['urllogo']= "Images/link_logo.gif" ;
				 }
			}
			return $links;
		}else
			return false;
	}
	
//得到用户信息
	function getUserVO($user,$field='*'){
		if(is_numeric($user)){
			$where['id'] = $user;
		}else{
			$where['uname'] = $user;
		}
		$form = M("User");
		$uservo = $form->where($where)->field($field)->find();
		if($uservo){
			return $uservo;
		}
		return false;
	}

/* 加密，可逆. 可接受任何字符*/
    function encrypt($txt, $key = '100rNet')
    {
       	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
		$nh = rand(0,64);
		$ch = $chars[$nh];
		$mdKey = md5($key.$ch);
		$mdKey = substr($mdKey,$nh%8, $nh%8+7);
		$txt = base64_encode($txt);
		$tmp = '';
		$i=0;$j=0;$k = 0;
		for ($i=0; $i<strlen($txt); $i++) {
			$k = $k == strlen($mdKey) ? 0 : $k;
			$j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%64;
			$tmp .= $chars[$j];
		}
		return $ch.$tmp;
    }
    
    /** 解密*/
     function decrypt($txt, $key = '100rNet')
     {
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
		$ch = $txt[0];
		$nh = strpos($chars,$ch);
		$mdKey = md5($key.$ch);
		$mdKey = substr($mdKey,$nh%8, $nh%8+7);
		$txt = substr($txt,1);
		$tmp = '';
		$i=0;$j=0; $k = 0;
		for ($i=0; $i<strlen($txt); $i++) {
			$k = $k == strlen($mdKey) ? 0 : $k;
			$j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);
			while ($j<0) $j+=64;
			$tmp .= $chars[$j];
		}
		return base64_decode($tmp);
    }
    
 //生成六位随机数 --用于邮件验证及重设密码等    
    function make_RandNum(){
    	$key = mt_rand(100000,999999);
    	return $key;
    }

//---隐藏IP后两位---
//*By xiongyan *-------------
function  hideIP($ip) {
	$arrIP = explode(".", $ip);
	array_pop($arrIP);
	array_pop($arrIP);
	echo join($arrIP, ".").".*.*";
}

//*By xiongyan *-------------
//函数：ifb_class
//功能：判断大类ID里是否有小类ID。
//参数：$s_classid ：默认已选类别,0为未选择
//返回 是：1，否: 0
function ifb_class($s_classid=NULL)
{
        $map['pid']    =    $s_classid;
		if( D("Class")->count($map)<1 )
			//(没有子类的的大类当作小类处理)
		  return false;
		else 
		  return true;
}

//---入库环境--- //编辑器用
function  add_str($str)   
{
	if(!@get_magic_quotes_gpc())   
	{
		$str=addslashes($str);   
	}
	return  $str;   
}

//--出库环境--
function out_str($str) 
{
	$str =	stripslashes($str); 
	return  $str;   
}

	//根据分类ID取得其内容模型
	function getTemplet($class_id)
	{
		//$class_id :分类ID  
         $dao  = D("Class");  //
         $vo = $dao->getById($class_id);
         $model_id = $vo['model_id'];  //取得内容模型ID
         if (!$model_id) {             //取得内容模型中发布模板及附加表名
           $model_id = 1;  //默认模型ID
         }
	         $addtemplet	=	D('Class_model','Admin_App');  //调后台Admin_App项目中的模型
	         $condition['id']    =    $model_id; 
	         $vo = $addtemplet->where($condition)->field('id,addcon,editcon,viewcon,mancon,addtable')->find();
	         if($vo){
			 	return $vo;  //addcon 发布,editcon 编辑,viewcon 内页
	         }   
         
	}

 //发送站内短消息
     function sendMsg($fromUserId,$toUserId,$title,$msg) {
     	
		$data["fromUserId"] = $fromUserId;
		$data["toUserId"] = $toUserId;
		$data["title"] = $title;
		$data["content"] = $msg;
		$data["addtime"] = time();

		return  D("Msg")->add($data);
	}
	 
//---获取楼盘特色  --熊彦
	function getLpTese($num){
		$lptese_list = C('cfg_lptese');     //用于楼盘特色列表
		if(array_key_exists($num, $lptese_list)){
			return $lptese_list[$num];
		}else
			return "未知特色";
	}

//---获取综合点评  --熊彦
	function getLpdpXml($data){
		$data['jiage']    = intval($data['jiage']);
		$data['huanjing'] = intval($data['huanjing']);
		$data['diduan']   = intval($data['diduan']);
		$data['huxing']   = intval($data['huxing']);
		$data['peitao']   = intval($data['peitao']);
		
		$str =  "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n <info jiage=\"".$data['jiage']."\" huanjing=\"".$data['huanjing']."\" diduan=\"".$data['diduan']."\" huxing=\"".$data['huxing']."\" peitao=\"".$data['peitao']."\"></info>";
		return $str;
	}
	
//---前台公共	提示函数  --熊彦
//---$status= success成功/error失败, info提示信息, data传递参数
	function ajaxMsg($info='', $status='success', $title='', $data=''){
		if(empty($info)){
			$info = '操作成功！';
		}
		$reslut['info']  = $info;
		if($status == 'success'){
			$reslut['success'] = true;
		}else{
			$reslut['success'] = false;
		}
		$reslut['title'] = $title;
		$reslut['data']  = $data;
		exit( json_encode($reslut) );
		//形如  //$str = '{"info":"'.$info.'","success":true, "title":"'.$title.'", "data":"'.$data.'"}';
	}

//---前台公共  --熊彦
//随机生成用户名 --用于随机发点评
    function getRandUser(){
    	$key = mt_rand(1,900);   //随机用户id范围
		$map['id'] = $key;
		$vo = M('User')->field('id, username')->where($map)->find();
    	return $vo;
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
	
	function my_addslashes($string){
		$magic_quote = get_magic_quotes_gpc();
		if(empty($magic_quote)) {
			 return addslashes($string);
		}else{
			return $string;
		}
		
	}
	
//换引号
	function mystr_replace($str){
		return str_replace('"',"'",$str);	
	}	
	
	//标题样式自定义
	function title_style($str, $default=""){
		switch($str){
			case 'b': $r='blue_a';break;
			case 'g': $r='green_a';break;
			case 'r': $r='red_a';break;
			default: if($default){ $r=$default;}else{$r='black_a';}
		}
		return $r;		
	}

//获取新闻文章链接(统一普通链接和转向链接)
	function get_arc_url($id, $redirecturl=""){
	    //跳转网址
        if( $redirecturl != '')
        {
			$url = $redirecturl;
		}else{
			$url = __APP__."/news-".$id;
		}
		return $url;

	}
?>