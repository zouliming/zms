<?php
class WelcomeController extends Controller{
    public function actionIndex(){
        $this->forward('welcome/login');
    }
    public function actionLogin() {
        if (isset($_SESSION['user'])) {
            $this->forward('welcome/welcome');
        } else {
            if (Bee::get('method') == "POST") {
                $merchId = $this->getPost('merchId');
                $name = $this->getPost('userName');
                $password = $this->getPost('password');
                $verifyCode = $this->getPost('checkWord');
                $cookieCode = getCookie('loginVerifyCode');
                if ($cookieCode == IdentifyCode::encrypt($verifyCode)) {
                    if (empty($merchId)) {
                        alert("商家ID不能为空", 'welcome/login');
                    }
                    if (empty($name)) {
                        alert("用户名不能为空", 'welcome/login');
                    }
                    if (empty($password)) {
                        alert("密码不能为空", 'welcome/login');
                    }
                    $masterModel = Model::mo('Master');
                    $authinfo = $masterModel->authMaster($merchId, $name, $password);
                    if ($authinfo) {
                        //写session
                        $_SESSION['user'] = array(
                            'id' => $authinfo['id'],
                            'merch' => $authinfo['merch_id'],
                            'name' => $authinfo['name'],
                            'realname' => $authinfo['realname']
                        );
                        $this->forward('welcome/welcome');
                    } else {
                        alert("用户名或者密码信息错误，请重新登录", 'welcome/login');
                    }
                } else {
                    alert("验证码错误", 'welcome/login');
                }
            } else {
                $this->view('welcome/login');
            }
        }
    }
    public function actionLogout(){
        unset($_SESSION['user']);
        $this->forward('welcome/login');
    }
    public function actionWelcome(){
        $this->view('welcome/welcome',array(),'index');
    }
    public function actionMain(){
        $this->view('welcome/main',array(),'main');
    }
}
?>