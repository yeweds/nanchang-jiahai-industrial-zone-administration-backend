<?php
/**
 * System  系统维护类
 * @author 熊彦 <cnxiongyan@gmail.com>
 */
class SystemAction extends GlobalAction{
    private $web_cfg_path = "./web_config.php";
    /**
     * 系统维护首页菜单
    */
    public function index()
    {
        $this->assign("uid",$this->uid );  //用于修改密码传参
        $this->display();
    }

    /**
     * 自定义配置文件列表
    */
    public function sys_cfg()
    {
        $web_config = require ($this->web_cfg_path);
        foreach($web_config as $key=>$value)
        {
            if($value===true)
            $web_config[$key] = "true";
            if($value===false)
            $web_config[$key] = "false";
        }
       //dump($web_config);
        $this->assign("web_config",$web_config);
        $this->display();
    }

    /**
     * 保存自定义配置文件web_config.php
     */
    public function update_cfg()
    {
        $data = $_POST;
        //填充为空的项目       
        if($data["cfg_sitename"]=='')$data["cfg_sitename"]='南昌佳海产业园' ;
        if($data["cfg_domain"]=='')$data["cfg_domain"]='';
        if($data["cfg_allow_reg"]=='')$data["cfg_allow_reg"]= 'true';
        if($data["cfg_title"]=='')$data["cfg_title"]='南昌佳海产业园' ;
        if($data["cfg_metakeyword"]=='')$data["cfg_metakeyword"]='南昌佳海产业园';
        if($data["cfg_design"]=='')$data["cfg_design"]='tengfang.Net';
        if($data["cfg_version"]=='')$data["cfg_version"]='1.1';
        
        /* 生成配置项连续字符串 */

        $web_config = "<?php\n//南昌佳海产业园核心配置文件\nif (!defined('THINK_PATH')) exit();\nreturn array(\n";
        foreach($data as $key=>$value)
        {
            if(strtolower($value)=="true" || strtolower($value)=="false" || is_numeric($value))
            $web_config .= "    '".$key."'=>$value,\n";
            else
            $web_config .= "    '".$key."'=>'$value',\n";
        }
        $web_config .= ");\n?>";

        $configfile = $this->web_cfg_path ; //自定义配置文件路径
        $r=@chmod($configfile,0777);
        if(!is_writeable($configfile))
        {
            $this->error("配置文件'{$configfile}'不支持写入，无法修改系统配置参数！");
            exit();
        }
        file_put_contents($configfile,$web_config);
        //清空缓存
        //clearCache(1);
        $this->assign("jumpUrl",__URL__."/sys_cfg");
        $this->assign('waitSecond',3);
        $this->success('系统配置修改成功！');
    }

    // 修改用户密码
    public function modify_pwd(){
        $id=intval($_REQUEST["id"]);
        if (!$id) $this->error('未知用户');
        $Member=M("Admin")->where('id='.$id)->find();
        //dump($Member);
        if (!$Member) $this->error('该用户不合法');
        $this->assign('vo',$Member);
        $this->display();
    }

    /**
     * 保存新密码
     */
    public function update_pwd()
    {
        $old_pwd   = $_POST['old_pwd'];  //旧密码
        $password  = $_POST['password'];
        $opassword = $_POST['opassword'];
        $id = intval($_POST['id']);
        if (!$id) $this->error('该用户ID不存在');
        if($password != $_POST['pwd2'])  $this->error('两次密码不一致');
        $Member = D("Admin");
        $map['id'] = $id;
        $vo = $Member->where($map)->find();
        if ($vo['pwd'] != md5($old_pwd))  $this->error('旧密码不对！');
        if($Member->create()) { 
            if(!empty($password)){
                $Member->pwd = md5(trim($password)); 
            }else{
                $Member->pwd = $opassword;
            }
            $rs = $Member->where($map)->save();
            if($rs !== false ){
                $this->assign('waitSecond',5);
                $this->assign('jumpUrl', __APP__."/Public/login");
                $this->success('密码修改成功,请重新登录！');
            }else{ 
                $this->error('密码未被失败！'); 
            } 
        }else{ 
            $this->error($Member->getError()); 
        } 
    }

    /**
     * 服务器相关辅助信息
     */
    public function server_info(){
        
        $Goods   =M('New_loupan');   //楼盘
        $Member  =M('User');         //操作员
        $Reviews =M('Reviews_mx');   //点评
        $GoodsC  =$Goods->count();
        $MemberC =$Member->count();
        $ClassC  =$Reviews->count();

        $this->assign(array('GoodsC'=>$GoodsC,
                    'MemberC'=>$MemberC,
                    'ClassC'=>$ClassC,
        ));
        //获取系统信息
        $config=new model();
        $array=array();
        $serverinfo = PHP_OS.' / PHP v'.PHP_VERSION;
        $serverinfo .= @ini_get('safe_mode') ? ' Safe Mode' : NULL;
        $dbversion = $config->query("SELECT VERSION()");
        $fileupload = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : '<font color="red">不支持上传</font>';
        $dbsize = 0;
        $tablepre = C('DB_PREFIX');
        $query = $tables = $config->query("SHOW TABLE STATUS LIKE '$tablepre%'");
        //dump($query);
        foreach($tables as $table) {
            $dbsize += $table['Data_length'] + $table['Index_length'];
        }
        $dbsize = $dbsize ? realSize($dbsize) : '未知大小';
        $dbversion = $config->query("SELECT VERSION()");
        $magic_quote_gpc = get_magic_quotes_gpc() ? 'On' : 'Off';
        
        $array['serverinfo']=$serverinfo;
        $array['dbversion']=$dbversion;
        $array['fileupload']=$fileupload;
        $array['dbsize']     =$dbsize;
        $array['dbversion']=$dbversion[0]['VERSION()'];
        $array['magic_quote_gpc']=$magic_quote_gpc;
        $this->assign($array);
        $this->display();
    }

    /**
     * 初始化清理缓存
     */
    public function cache()
    {   
        $arr = array(
            "Admin(后台)"=>array(
                "缓存目录(Runtime)"=>APP_PATH."/Runtime",   
                "模版缓存(Cache)"=>APP_PATH."/Runtime/Cache",
                "数据缓存目录(Temp)"=>APP_PATH."/Runtime/Temp",
                "日志目录(Logs)"=>APP_PATH."/Runtime/Logs",
                "数据目录(Data)"=>APP_PATH."/Runtime/Data"
            ),
            "Home(前台)"=>array(
                "缓存目录(Runtime)"=>"./Home/Runtime",  
                "模版缓存(Cache)"=>"./Home/Runtime/Cache",
                "数据缓存目录(Temp)"=>"./Home/Runtime/Temp",
                "日志目录(Logs)"=>"./Home/Runtime/Logs",
                "数据目录(Data)"=>"./Home/Runtime/Data"
            )
        );
        $this->assign("cache",$arr);
        $this->display();
    }
    
    //处理清理缓存
    public function submit_cache(){
        $dirs = $_POST['id'];
        //dump($dirs);
        $say = null;
            foreach($dirs as $value)
            {
                $this->clearCache($path=$value);
                $say.= "清理缓存文件夹成功! ".$value."</br>";
            }
            $this->assign('waitSecond',15);
            $this->success($say);
    }

    // 清除缓存目录
    public function clearCache($path=null) {
        //echo $path;
        import("ORG.Io.Dir");
        if( is_dir($path)==true ){
            $dir = new Dir($path,$pattern='*');
            $dir->delDir($path);  //删除目录，包括文件
        }
    }

}
?>