<?php 
class AdminModel extends Model {
    // 自动验证设置
    protected $_validate     =   array(
        array('admin','require','请输入用户名！'),
        array('verify','require','验证码必须！'),
        array('verify','CheckVerify','验证码错误',0,'callback'),
        array('admin','','该管理帐户已经存在',0,'unique','add'),
        );
    // 自动填充设置
    protected $_auto     =   array(
        //array('status','1','ADD'),
        array('login_time','time','ADD','function'),
        );

    public function CheckVerify() {
        return md5($_POST['verify']) == $_SESSION['verify'];
    }

    // public function deleteById($id)
    // {
        
    // }
}
?>