<?php
class PublicAction extends Action{
//公共检验方法
	function   Replace_str($str)
	//去掉标题HTML
	{
		$str=trim($str);  //去头尾空格
		$str=strip_tags($str);   //去HTML
		$str=str_replace(" ","",$str); //替换空格;
		return   $str;
	}
	function check_email($email){
	//检验注册邮箱
		if(!isset($email)){
			$value = $_GET['value'];
		}else{
			$value = $email;
		}
		$pattern = "/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
		$result = array();

		if(preg_match($pattern,$value)!=1){
			$result['msg']   = "邮箱格式不正确";
			$result['value'] = 0;
		}else{
			$user	=	M("User");
			$where['email'] = $value;
			if($user->where($where)->find()){
				$result['msg']  = "邮箱已经存在" ;
				$result['value']= 0;
			}else{
				$result['msg']  = "success" ;
				$result['value']= 1;
			}
		}
		if(!isset($email)){
			$this->checkout($result);
		}else{
			if($result['value']==1){
				return 'success';
			}else{
				return 'err';
			}
		}
	}

	function check_usern($u_name){
    //检验用户名
		if(!isset($u_name)){
			$value = $_GET['value'];
		}else{
			$value = $u_name;
		}
 		$text = strtolower($value);
		$value = urldecode($this->Replace_str($text));

		$word	= "/共产党|法轮功|藏独|毛泽东|江泽民|台独|胡锦涛|fuck|★/";
		$result = array();
		if(strstr($value,' ')){
			$result['msg']   = "不允许出现空格";
			$result['value'] = 0;
		}else if(preg_match($word,$value)!=0){
			$result['msg']   = "该用户名有违禁字符";
			$result['value'] = 0;

		}else if(strlen($value) < 4 || strlen($value) > 20){
			$result['msg']   = "用户名为4-20字符！";
			$result['value'] = 0;
		}else{
			$user	=	D("User");
			if($user->where("u_name='".$value."'")->find()){
				$result['msg']  = "用户名[ ".$value."]已经存在" ;
				$result['value']= 0;
			}else{
				$result['msg']  = "恭喜您，该用户名可以注册！" ;
				$result['value']= 1;
			}
		}
		if(!isset($u_name)){
			$this->checkout($result);
		}else{
			if($result['value']==1){
				return 'success';
			}else{
				return 'err';
			}
		}
	}

	function check_code($code){
		if(!isset($code)){
			$value = $_GET['value'];
		}else{
			$value = $code;
		}
		if(md5($value) != $_SESSION['verify']){
			$result['msg']  = "验证码错误";
			$result['value']= 0;
		}else{
			$result['msg']  = "success";
			$result['value']= 1;
		}
		if(!isset($code)){
			$this->checkout($result);
		}else{
			if($result['value']==1){
				return 'success';
			}else{
				return 'err';
			}
		}
	}

	function checkout($result){
    //检验输出
		if($result['value']==1){
			 $this->ajaxReturn('', $result['msg'] ,1);  //正确
		}else{
			$this->ajaxReturn('', $result['msg'] ,0);
		}
	}

//	生成文字图片
	function text2img(){
	if(isset($_GET['info']) and strlen($_GET['info'])>3){
		$info = decrypt($_GET['info']);
	}else{
		$info = "0791-*******"; 
	}
	$strlen = strlen($info); //字节数
	$width = 10 * $strlen;
	$height =20;
	$im = imagecreate($width,$height);
	$color1 = imagecolorallocatealpha($im,255,255,255,35); //int imagecolorallocatealpha ( resource $image , int $red , int $green , int $blue , int $alpha )alpha 透明度127        
	imagestring($im, 5, 5, 1, $info, '12345678'); 
	//imagettftext($im,12,0,$width,$height,$color1,'',$info);  
    import("ORG.Util.Image");
    Image::output($im,'png');
	}

//加水印
    public function watermark()
    {
		C('WATERMARK_TEXT', false);
		C('WATERMARK_TEXT_STRING', '腾房网 tengfang,Net');
		C('WATERMARK_IMG', true);
		C('WATERMARK_IMG_PATH', './Home/Tpl/default/Public/Images/logo.png');
		//配置结束---
		$path = base64_decode($_REQUEST['show']);
        $resorce = getimagesize($path);
		//dump($resorce);
		//halt($resorce);
        if ($resorce)
        {
            $type = explode('/', $resorce['mime']);
            header('Content-type: ' . $type); 
            $creator = 'imagecreatefrom' . $type['1'];
            $img = @$creator($path);
			//imagealphablending($img, true); //增加精度
            if (C('WATERMARK_IMG') == true)
            {
                $replace_res = getimagesize(C('WATERMARK_IMG_PATH'));
                $replace_type = explode('/', $replace_res['mime']);
                if ($replace_res)
                {
                    $replace_cre = 'imagecreatefrom' . $replace_type['1'];
                    $replace_img = @$replace_cre(C('WATERMARK_IMG_PATH'));
                    imagecopy($img, $replace_img, 20, 20, 0, 0, $replace_res['0'], $replace_res['1']);
                } else
                {
                    $text_color = imagecolorallocate($img, 233, 14, 91);
                    imagettftext($img, 12, 0, 20, 20, $text_color, './Public/Font/simhei.ttf', 'Replace Image Not Exist');
                }
            }
            if (C('WATERMARK_TEXT') == true)
            {
                $text_color = imagecolorallocate($img, 233, 14, 91);
                imagettftext($img, 12, 0, 20, 40, $text_color, './Public/Font/simhei.ttf', C('WATERMARK_TEXT_STRING'));
            }
            $outtype = 'image' . $type['1'];
            $outtype($img);
        } else
        {
            header("Content-type: image/png");
            $im = @imagecreatetruecolor(150, 100);
            $text_color = imagecolorallocate($im, 233, 14, 91);
            imagestring($im, 5, 5, 5, 'Image Not Exist', $text_color);
            imagepng($im);
        }
    }
	
	//普通验证码显示
	public function verify(){
		import('ORG.Util.Image');  //导入图像类
		if(isset($_REQUEST['adv'])){
			Image::showAdvVerify();
		}else{
			Image::buildImageVerify();
		}
	}
	
	//汉字验证码显示
	function zh_verify(){
		import('ORG.Util.Image');
		Image::GBVerify();
	}
	
	//用户邮箱账户验证
	public function active(){
		if(isset($_GET['code']) and $_GET['new']){
			$userid = decrypt($_GET['code']);
			$email  = $_GET['new'];
			$dao  = D("User");
			$where['id']= $userid;
			if($userVo = $dao->where($where)->find()){
				
				//$dao->setField('score','(score+1)','id='.$userid,false);
				$data['score'] += 10;    //加积分
				$data['email']   = $email;   //修改邮箱账户
				$dao->where($where)->save($data); 
				$this->assign('waitSecond',5); 	
				$this->assign('jumpUrl',__APP__.'/Account/account');
				$this->success('恭喜，您的新邮箱账户激活成功！');
			 } else {
			    $this->error('新账户激活失败。');
			}			
		}
	}

//通过info_id 获取附件路径  -- xy 添加
	public function get_attach_one($info_id, $model_name="pics", $class_id = 0, $is_defautl=0){
			$map['info_id'] = $info_id;
			$map['model_name'] = $model_name; //所属模块

			if( $is_defautl==1 ){
				//默认
				$map__['id'] = $info_id;
				$Table = ($model_name!="pics" ? "Shop" : "Pic");
				$vo = M($Table)->field('id,default_attach_id')->where($map__)->find();

				if($vo['default_attach_id'] != 0){
					$map['id'] = $vo['default_attach_id'];
				}
			}else{
				if($class_id != 0){
					$map['class_id']= $class_id;
				}
			}
			$rs = M('Attach')->where($map)->find();
			$pic['thumb'] = ( $rs ? trim($rs['savepath'],'.').'thumb_'.$rs['savename'] : "/Public/Upload/no.png" ) ;
			$pic['big']   = ( $rs ? trim($rs['savepath'],'.').$rs['savename'] : "/Public/Upload/no.png" ) ;
			return  $pic;
	}

//生成随机楼盘链接
	public function lp_rand_js(){
		header("Content-Type:text/html; charset=utf-8");
		$num = ( isset($_GET['num']) ? intval($_GET['num']) : 2 ); //数量
		$Table = M('New_loupan');
		$count = $Table->count();
		if( $count <= $num ){
			$rand_start = 0;  //少于要取的记录，则从第一条开始取
		}else{
			$min_num    = $count - $num ; 
			$rand_start = rand(0, $min_num);  //随机初始值
		}
		$list = $Table->field('lpname,info_id,range_id')->limit($rand_start.','.$num)->findAll();
		$str = '';
		foreach($list as $v){
			$str .= ' document.writeln("<a href=\"http://nc.tengfang.net/lp-'.$v['info_id'].'\">'.$v['lpname'].'</a> "); ';
			//echo ' document.writeln("<a href=\"http://nc.tengfang.net/lp-'.$v['info_id'].'\">'.$v['lpname'].'</a> "); ';
		}
		echo $str;
	}


//生成随机楼盘链接html
	public function lp_rand_html(){
		header("Content-Type:text/html; charset=utf-8");
		$num = ( isset($_GET['num']) ? intval($_GET['num']) : 5 ); //数量
		$map['lp_state'] = array('neq',3);

		$Table = M('New_loupan');
		$count = $Table->where($map)->count();
		if( $count <= $num ){
			$rand_start = 0;  //少于要取的记录，则从第一条开始取
		}else{
			$min_num    = $count - $num ; 
			$rand_start = rand(0, $min_num);  //随机初始值
		}
		
		$list = $Table->field('lpname,info_id,range_id')->where($map)->limit($rand_start.','.$num)->findAll();
		//$str = '<a href="http://jiujiang.tengfang.net"/>九江房产</a> <a href="http://wh.tengfang.net"/>武汉房产</a> <a href="http://cs.tengfang.net"/>长沙房产</a>'; //初始链接
		$str = '';
		foreach($list as $v){
			$str .= ' <a href="http://nc.tengfang.net/lp-'.$v['info_id'].'">'.$v['lpname'].'</a> ';
			//echo ' document.writeln("<a href=\"http://nc.tengfang.net/lp-'.$v['info_id'].'\">'.$v['lpname'].'</a> "); ';
		}
		echo $str;
	}

	//记当来访QQ
	public function save_hit(){
		$qq = trim($_GET['qq']);
		$qq = intval($qq);
		if(!empty($qq)){
			$Table = M('qq_log');
			$map['qq'] = $qq;
			$rs = $Table->where($map)->find();
			if(!$rs){
				$data['qq'] = $qq;
				$Table->add($data);
			}else{
				$data['count'] += 1;
				$Table->where($map)->save($data);
			}
		}
		$this->assign('waitSecond', 0); 	
		$this->assign('jumpUrl',__APP__.'/');
		$this->success('进入中...');
	}
}
?>