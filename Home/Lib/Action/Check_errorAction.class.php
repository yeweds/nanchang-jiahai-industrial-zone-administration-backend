<?php
// 纠错类
// --董平文--
class Check_errorAction extends Action
{
    public function index()
    {  
        $this->display('index');
    }

//添加
    public function add_corection(){
       //date_default_timezone_set('PRC');
       $content = trim($_POST['content']);
       $suggest = trim($_POST['suggest']);
       $error_url = trim($_POST['error_url']);
	   if($_FILES['photo']['size'] > 0 ){
		   import("ORG.Net.UploadFile");
		   $upload = new UploadFile();  // 实例化上传类
		   $upload->maxSize  = 3145728; // 设置附件上传大小
		   $upload->allowExts  = array('jpg','gif','png','jpeg'); // 设置附件上传类型
		   $upload->savePath = '/opt/www/tf_img/upload/ck_error/';   //绝对路径  /纠错图片目录
		   $upload->uploadReplace=true; //为true可以重复上传
		   $upload->saveRule = 'uniqid';
		   //设置需要生成缩略图，仅对图像文件有效
		   $upload->thumb =  true; 
		   //设置需要生成缩略图的文件后缀
		   $upload->thumbPrefix =  'thumb_'; 
		   $upload->thumbSuffix =  '';     //不要后缀
		   //设置缩略图最大宽度
		   $upload->thumbMaxWidth = 100; 
		   //设置缩略图最大高度
		   $upload->thumbMaxHeight = 100; 
		   //$upload->ATTACH=true;
		   $upload->thumbPath = $upload->savePath;
			if($upload->upload()) {     // 上传错误提示错误信息     //上传成功 获取上传文件信息
				$info = $upload->getUploadFileInfo();
			}else{
				$this->error($upload->getErrorMsg());      	
			}
	    }
        if(empty($content)){
        	$this->error('请输入有误内容');
        }
        if(empty($suggest)){
        	$this->error('请填写修正意见');
        }
        if(empty($error_url)){
        	$this->error('请输入可能有误的网页地址');
        }
        if($_SESSION['verify'] != md5($_POST['testimg'])){
			$this->error('验证码有误');
        }
        $table=M('Correction');
        $table->create();
        $table->content  = $content;
        $table->suggest  = $suggest;
        $table->error_url= $error_url;
        $table->pic		 = $info[0]['savename'];
        $table->add_time = time();  
        $table->add();
        $this->assign('jumpUrl', '__APP__/');
        $this->success('纠错信息提交成功'); 
     }

     public function find_correction(){
     	$table=M('Correction');
        $list=$table->order('add_time desc')->findAll();//
        $this->assign('list',$list);
       // dump($list);
        $this->assign('success');
     }


}
?>