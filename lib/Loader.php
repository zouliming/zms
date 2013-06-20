<?php
Class Loader{
    
    protected static $_instance;
    /**
     * @var string Base path to resource classes
     */
    protected static $_basePath = array();
    /**
     * Constructor
     *
     * Registers instance with spl_autoload stack
     *
     * @return void
     */
    protected function __construct(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    /**
     * Retrieve singleton instance
     *
     * @return Vipcore_Loder
     */
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public static function setBasePath($basePath){
        if (is_array($basePath)) {
            self::$_basePath = $basePath;
        } elseif (is_string($basePath)) {
            self::$_basePath = array($basePath);
        }
        return self::getInstance();
    }
    /**
     * Autoload a class
     *
     * @param  string $class
     * @return bool
     */
    public static function autoload($class){
        $loader = self::getInstance();
        if ($loader) {
            if ($loader->_autoload($class)) {
                return true;
            }
        }
        return false;
    }
    /**
     * Attempt to autoload a class
     *
     * @param  string $class
     * @return mixed False if not matched, otherwise result if include operation
     */
    public function _autoload($class){
        $classPath = $this->getClassPath($class);
        if (false !== $classPath) {
            return include $classPath;
        }
        return false;
    }
    /**
     * Helper method to calculate the correct class path
     *
     * @param string $class
     * @return False if not matched other wise the correct path
     */
    public function getClassPath($class){
        /*
         * 如果需要对加载的文件做过滤,可以取消掉下面的注释
        $segments = explode('_', $class);
        if (count($segments) < 2) {
            //假定所有的资源文件都有一个组件名字和类名,所以最小值是2
            return false;
        }
         */
        foreach (self::$_basePath as $path) {
            $classPath = $path . '/' . str_replace('_', '/', $class) . '.php';
            if (self::isReadable($classPath)) {
                return $classPath;
            }
        }
        return false;
    }
    /**
     * Returns TRUE if the $filename is readable, or FALSE otherwise.
     * This function uses the PHP include_path, where PHP's is_readable()
     * does not.
     *
     * Note from ZF-2900:
     * If you use custom error handler, please check whether return value
     *  from error_reporting() is zero or not.
     * At mark of fopen() can not suppress warning if the handler is used.
     *
     * @param string   $filename
     * @return boolean
     */
    public static function isReadable($filename){
        if (is_readable($filename)) {
            // Return early if the filename is readable without needing the
            // include_path
            return true;
        }
    
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' && preg_match('/^[a-z]:/i', $filename)) {
            // If on windows, and path provided is clearly an absolute path,
            // return false immediately
            return false;
        }
    
        foreach (self::explodeIncludePath() as $path) {
            if ($path == '.') {
                if (is_readable($filename)) {
                    return true;
                }
                continue;
            }
            $file = $path . '/' . $filename;
            if (is_readable($file)) {
                return true;
            }
        }
        return false;
    }
    /**
     * Explode an include path into an array
     *
     * If no path provided, uses current include_path. Works around issues that
     * occur when the path includes stream schemas.
     *
     * @param  string|null $path
     * @return array
     */
    public static function explodeIncludePath($path = null){
        if (null === $path) {
            $path = get_include_path();
        }
    
        if (PATH_SEPARATOR == ':') {
            // On *nix systems, include_paths which include paths with a stream
            // schema cannot be safely explode'd, so we have to be a bit more
            // intelligent in the approach.
            $paths = preg_split('#:(?!//)#', $path);
        } else {
            $paths = explode(PATH_SEPARATOR, $path);
        }
        return $paths;
    }
}
?>
