<?php

class WelcomeController extends Controller {

        public $layout = 'main';

        public function actionIndex() {
                $this->forward('welcome/login');
        }

        public function actionLogin() {
                if (isset($_SESSION['user'])) {
                        $this->forward('welcome/welcome');
                } else {
                        $showIndentifyCode = false;
                        $freeLoginTimes = 3;
                        if (isset($_SESSION['login_fail_time']) && $_SESSION['login_fail_time'] >= $freeLoginTimes) {
                                $showIndentifyCode = true;
                        }
                        $error = "";
                        if ($this->isPost()) {
                                $name = $this->getPost('userName');
                                $password = $this->getPost('password');

                                $verifyPass = false;
                                if ($showIndentifyCode) {
                                        $verifyCode = $this->getPost('checkWord'); //用户输入的验证码
                                        $cookieCode = getCookie('loginVerifyCode');
                                        if ($cookieCode == IdentifyCode::encrypt($verifyCode)) {
                                                $verifyPass = true;
                                        } else {
                                                $error = "验证码错误";
                                        }
                                } else {
                                        $verifyPass = true;
                                }
                                if ($verifyPass) {
                                        if (empty($name)) {
                                                $error = "用户名不能为空";
                                        } elseif (empty($password)) {
                                                $error = "密码不能为空";
                                        } else {
                                                $masterModel = Model::mo('Master');
                                                $authinfo = $masterModel->authMaster($name, $password);
                                                if ($authinfo) {
                                                        unset($_SESSION['login_fail_time']);
                                                        //写session
                                                        $_SESSION['user'] = array(
                                                                'id' => $authinfo['id'],
                                                                'name' => $authinfo['name'],
                                                                'realname' => $authinfo['realname']
                                                        );
                                                        $this->forward('welcome/welcome');
                                                } else {
                                                        $error = "用户名或者密码信息错误，请重新登录";
                                                }
                                        }
                                }
                        }
                        if ($error) {
                                $_SESSION['login_fail_time'] = isset($_SESSION['login_fail_time']) ? $_SESSION['login_fail_time'] + 1 : 1;
                        }
                        $this->view('welcome/login', array(
                                'showIndentifyCode' => $showIndentifyCode,
                                'error' => $error
                                ), 'none');
                }
        }

        public function actionLogout() {
                unset($_SESSION['user']);
                $this->forward('welcome/login');
        }

        public function actionWelcome() {
                $this->view('welcome/welcome', array());
        }

        public function actionMain() {
                $this->view('welcome/main', array());
        }

}

?>