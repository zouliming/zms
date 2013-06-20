<?php

/**
 * 控制器的父类
 */
class Controller {

    private $r = "r"; //默认的访问参数
    private $id; //Controller的原名称
    private $action; //action的名称
    public $layout;
    private $controller; //完整的控制器的类名称
    private $controllerFile; //控制器的文件名

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
    public function checkLogin(){
        if(Bee::app()->isGuest()){
            $this->forward('welcome/login');
        }
    }
    public function run() {
        session_start();
        if (($ca = $this->createController()) != null) {
            $actionMethod = $this->getAction();
            if (method_exists($ca, $actionMethod)) {
                $ca->$actionMethod();
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
        }
        return null;
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

    public function view($view, $data = array(), $layout = null, $return = false) {
//        if (!empty($data)) foreach ($data as $key => $value) $$key = $value;
        $viewFile = $this->getViewFile($view);
        $output = $this->renderInternal($viewFile, $data, true);

        $layoutFile = $this->getLayoutFile($layout);
        if ($layoutFile !== false) {
            $output = $this->renderInternal($layoutFile, array_merge($data, array('_layoutContent' => $output)), true);
        }
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
    public function isPost(){
        return Bee::get('method')=='POST'?true:false;
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
