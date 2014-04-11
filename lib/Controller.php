<?php

/**
 * 控制器的父类
 */
class Controller {

        private $r = "r"; //默认的访问参数
        public $id; //Controller的原名称
        public $action; //action的名称
        public $layout;
        public $script = array(); //运行在页面上的脚本
        private $controller; //完整的控制器的类名称
        private $controllerFile; //控制器的文件名
        public $pageTitle = "";

        public function __construct() {
                $this->parseController();
        }

        /*
         * 转换路由
         */

        private function parseController() {
                $this->controller = $this->setController(DEFAULT_CONTROLLER);
                $this->action = $this->setAction(DEFAULT_ACTION);
                $route = Bee::get('get', $this->r);
                if ($route) {
                        if (($route = trim($route, '/')) !== '') {
                                $pos = strpos($route, '/');
                                if ($pos) {
                                        $c = (string) substr($route, 0, $pos);
                                        $a = (string) substr($route, $pos + 1);
                                        $this->controller = $this->setController($c);
                                        $this->action = $this->setAction($a);
                                } else {
                                        //如果路由参数里没有"/",则默认采用当前的Controller,参数代表的是Action
                                        $this->action = $this->setAction($route);
                                }
                        }
                }
        }

        /**
         * 检验当前用户是否已经登录，否则跳转到登录界面
         */
        public function checkLogin() {
                if (Bee::app()->isGuest()) {
                        $this->forward('welcome/login');
                }
        }

        public function run() {
                session_start();
                if (($ca = $this->createController()) != null) {
                        $actionMethod = $this->getAction();
                        if (method_exists($ca, $actionMethod)) {
                                $ca->$actionMethod();
                                if (BEE_DEBUG == 2)
                                        require(LIB_DIRECTORY . SEP . 'Debug.php');
                        } else {
                                showError($this->controller . " 找不到对应的Action：" . $actionMethod);
                        }
                } else {
                        showError("找不到对应的控制器");
                }
        }

        /**
         * 设置控制器
         * @return type
         */
        public function setController($c) {
                $id = $c ? $c : DEFAULT_CONTROLLER;
                $controller = ucfirst($id) . 'Controller';
                $controllerFile = CONTROLLER_DIRECTORY . SEP . $controller . '.php';
                if (is_file($controllerFile)) {
                        require_once($controllerFile);
                        if (class_exists($controller)) {
                                $this->id = $id;
                                $this->controller = $controller;
                                $this->controllerFile = $controllerFile;
                                return $controller;
                        }
                } else {
                        showError("找不到对应的控制器文件，这个文件不存在:" . $controllerFile);
                        die;
                }
        }

        public function createController() {
                $controller = $this->getController();
                return new $controller();
        }

        public function getController() {
                return $this->controller;
        }

        public function getId() {
                return $this->id;
        }

        public function getAction() {
                return $this->action;
        }

        /**
         * 设置Action
         * @return type
         */
        public function setAction($a) {
                return 'action' . ucfirst($a);
        }

        /**
         * 标识开始运行脚本
         */
        public function beginScript() {
                ob_start();
        }

        /**
         * 标识停止脚本
         */
        public function endScript() {
                $this->script[] = ob_get_clean();
        }

        /**
         * 获取当前的脚本内容
         * @return type
         */
        public function getScriptContent() {
                $r = "";
                foreach ($this->script as $s) {
                        $r .= $s;
                }
                return $r;
        }

        public function view($view, $data = array(), $layout = null, $return = false) {
                if (BEE_DEBUG == 2)
                        $beginTime = microtime(TRUE);$beginMem = memory_get_usage();
                $viewFile = $this->getViewFile($view);
                $output = $this->renderInternal($viewFile, $data, true);
                if (BEE_DEBUG == 2)
                        Bee::$data['debug']['tplData'] = $data;
                $layoutFile = $this->getLayoutFile($layout);
                if ($layoutFile !== false) {
                        $scriptContent = $this->getScriptContent();
                        $output = $this->renderInternal($layoutFile, array_merge($data, array('_layoutContent' => $output, '_scriptContent' => $scriptContent)), true);
                }
                if ($return) {
                        return $output;
                } else {
                        echo $output;
                }
                if (BEE_DEBUG == 2)
                        Bee::$data['debug']['flow']['view'][] = array('txt' => $view, 'time' => microtime(TRUE) - $beginTime, 'mem' => memory_get_usage() - $beginMem);
        }
        
        public function renderPartial($view,$data = array(),$return=false){
                $viewFile = $this->getViewFile($view);
                $output = $this->renderInternal($viewFile, $data, true);
                if ($return) {
                        return $output;
                } else {
                        echo $output;
                }
        }
        /**
         * 渲染一个视图文件
         * @param string $_viewFile_ 视图文件
         * @param array $_data_ 将要在视图文件中被转换的变量
         * @param boolean $_return_ 是否要将渲染的结果作为一个字符串进行返回
         * @return string 渲染结果. 如果没有请求到渲染页面,则返回Null
         */
        public function renderInternal($_viewFile_, $_data_ = null, $_return_ = false) {
                if (file_exists($_viewFile_)) {
                        //我们在这里用特殊的变量来避免冲突
                        if (is_array($_data_)) {
                                extract($_data_, EXTR_PREFIX_SAME, 'data');
                        } else {
                                $data = $_data_;
                        }
                        if ($_return_) {
                                ob_start();
                                ob_implicit_flush(false);
                                require($_viewFile_);
                                return ob_get_clean();
                        } else {
                                require($_viewFile_);
                        }
                } else {
                        showError('找不到这个视图文件：' . $_viewFile_);
                }
        }

        public function getViewFile($viewName) {
                if (strpos($viewName, '/')) {
                        $viewName = str_replace('/', SEP, $viewName);
                }
                return VIEW_DIRECTORY . SEP . $viewName . '.php';
        }

        public function getLayoutFile($layoutName) {
                $l_name = $layoutName ? $layoutName : $this->getLayout();
                if ($l_name == null) {
                        return false;
                } else {
                        return VIEW_DIRECTORY . SEP . 'layout' . SEP . $l_name . '.php';
                }
        }

        public function getLayout() {
                return $this->layout;
        }

        /**
         * 快捷方式
         */
        public function getGet($field = '') {
                return Bee::get('get', $field);
        }

        public function getPost($field = '') {
                return Bee::get('post', $field);
        }

        public function getMethod() {
                return Bee::get('method');
        }

        public function isPost() {
                return Bee::get('method') == 'POST' ? true : false;
        }

        public function forward($url) {
                $u = '';
                $url = trim($url, '/');
                if (strpos($url, '/')) {
                        $u = '?r=' . $url;
                } else {
                        $c = $this->getId();
                        $u = '?r=' . $c . '/' . $url;
                }
                header("Location: {$u}");
                exit;
        }

}
