<?
/*
 * 这个是核心类
 */
class Bee {
    private static $_app;
    public static $data = array();
    public static $componets = array();
    public function __construct() {
        self::$_app = $this;
        self::$data['get'] = magic_quotes($_GET);
        self::$data['post'] = magic_quotes($_POST);
        self::$data['method'] = $_SERVER['REQUEST_METHOD'];
        self::$data['server'] = $_SERVER;
        unset($_POST, $_GET);
    }
    public function getComponet($componet){
        if(isset(self::$componets[$componet])){
            return self::$componets[$componet];
        }else{
            return (self::$componets[$componet]=new $componet());
        }
    }
    public static function app(){
        return self::$_app;
    }
    /**
     * 判断当前用户是否是访客
     * @return boolean
     */
    public function isGuest(){
        if(isset($_SESSION['user']) && isset($_SESSION['user']['id'])){
            return false;
        }else{
            return true;
        }
    }
    /**
     * 返回当前登录用户的id
     * @return string
     */
    public function userid(){
        if(isset($_SESSION['user']) && isset($_SESSION['user']['id'])){
            return $_SESSION['user']['id'];
        }else{
            return "";
        }
    }

    public function run(){
        $this->getComponet('Controller')->run();
    }
    public function getValidator(){
        return $this->getComponet('Validator');
    }
    public function getPager(){
        return $this->getComponet('Pager');
    }
    public static function get($key,$field=''){
        if($key == ''){
            return isset(self::$data['get'])?self::$data['get']:"";
        }else{
            if($field){
                return isset(self::$data[$key][$field])?self::$data[$key][$field]:"";
            }else{
                return isset(self::$data[$key])?self::$data[$key]:"";
            }
        }
    }
}
?>
