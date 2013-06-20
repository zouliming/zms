<?
require_once('Define.php');
/*
 * 这个是核心类
 */
class Bee {
    private static $_app;
    public static $data = array();
    public static $componets = array();
    private $config = array();
    private static $_aliases = array(
        'application'=>APP_DIRECTORY,
        'config'=>CONFIG_DIRECTORY,
        
    );
    public function __construct($config) {
        $this->init();
        $this->configure($config);
        self::$_app = $this;
        self::$data['get'] = magic_quotes($_GET);
        self::$data['post'] = magic_quotes($_POST);
        self::$data['method'] = $_SERVER['REQUEST_METHOD'];
        self::$data['server'] = $_SERVER;
        unset($_POST, $_GET);
    }
    public function getConfig($attribute,$key = ""){
        if($key){
            return isset($this->config[$attribute][$key]) ? $this->config[$attribute][$key] : null;
        }else{
            return isset($this->config[$attribute]) ? $this->config[$attribute] : null;
        }
    }
    public function configure($config){
        if(is_string($config))
            $config=require($config);
        $this->config = $config;
        if(isset($this->config['project']['timezone'])){
            // 设置时区,上海--以免出现时间不正确的情况
            date_default_timezone_set($this->config['project']['timezone']);
        }
        $this->loadImportFiles();
        require_once CONFIG_DIRECTORY . SEP . 'global.php';
        unset($config);
    }
    public function loadImportFiles(){
        if(isset($this->config['importFiles'])){
            foreach($this->config['importFiles'] as $f){
                $path = self::getPathOfAlias($f);
                if($path){
                    $isClass = substr($f,strrpos($f, '.')+1)=="*" ? false : true;
                    if($isClass){
                        if(is_file($path.'.php')){
                            include_once($path.'.php');
                        }else{
                            throw new Exception('找不到这个文件:'.$path.'.php');
                        }
                    }else{
                        if(is_dir($path) && ($files = scandir($path))!==false){
                            //只加载一级目录
                            foreach($files as $file){
                                if(is_file($file))
                                    include_once($file);;
                            }
                        }
                    }
                }else{
                    throw new Exception('找不到这个别名对应的路径:'.$f);
                }
            }
        }
    }
    public static function getPathOfAlias($alias) {
        if (isset(self::$_aliases[$alias])){
            return self::$_aliases[$alias];
        }elseif (($pos = strpos($alias, '.')) !== false) {
            $rootAlias = substr($alias, 0, $pos);
            if (isset(self::$_aliases[$rootAlias])){
                return self::$_aliases[$alias] = rtrim(self::$_aliases[$rootAlias] . SEP . str_replace('.', SEP, substr($alias, $pos + 1)), '*' . SEP);
            }       
        }
        return false;
    }
    public function init(){
        //加载全局函数库
        include_once(LIB_DIRECTORY . SEP . 'Common.php');
        include_once('Loader.php');
        Loader::setBasePath(
            array(
                LIB_DIRECTORY,
                CONFIG_DIRECTORY,
                CONTROLLER_DIRECTORY,
                MODEL_DIRECTORY,
                VIEW_DIRECTORY
            )
        );
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
