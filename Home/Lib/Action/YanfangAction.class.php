<?php
/**
 +------------------------------------------------------------------------------
 * Yanfang  验房报名类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class YanfangAction extends GlobalAction{
	
	public function index(){
		$this->assign('TitleMsg','验房报名');
		$this->display();
    }

//---验房报名入库
	public function insert()
	{
		if( md5($_POST["CheckCode"]) != $_SESSION['verify']){
			$this->error('验证码错误！请刷新验证码再重新提交.');
		}

		$Guest = M("Yanfang");
		// 检查数据插入
		$data = $Guest->create();
		if(false === $data) {
        	$this->error($Guest->getError());
        }
		$expect = $_POST['expect'];
		if($expect == '请尽量详细的填写你的验房出发点，或者你的小区状况，这样有助于我们将你的申请提前。'){
			$expect = '';
		}
		if(empty($expect)){
			$this->error('请输入验房期望或其它备注信息.');
		}
		// 处理数据安全
		$data['expect'] = htmlspecialchars(trim($_POST['expect']));
		$data['add_time'] = time();

		if($Guest->add($data)) {
			$this->assign('jumpUrl',__APP__.'/Index'); 
			$this->assign('waitSecond',10);	
			$this->success('验房申请提交成功，请耐心等待本站客服与您联系！');
		} else {
			$this->error('留言提交失败,请联系管理员！');
		}
	}

}
?>