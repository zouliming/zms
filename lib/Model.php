<?php
class Model extends Db {
    public $errors = array();
    public static $models = array();
    public static function mo($cl= __CLASS__ ){
        $className = ucfirst($cl).'Model';
        if(isset(self::$models[$className])){
            return self::$models[$className];
        }else{
            return (self::$models[$className]=new $className());
        }
    }
    public function __construct() {
        parent::__construct();
    }
    public function validate(){
        $va = new Validator();
        $va->check($this->_attributes,  $this->rules());
        if($va->success){
            $this->errors = array();
            return true;
        }else{
            $this->errors = $va->error;
            return false;
        }
    }
    public function getErrors($atttibute = null){
        if($atttibute===null){
            return $this->errors;
        }else{
            return isset($this->errors[$atttibute]) ? $this->errors[$atttibute] : "";
        }
    }
    
}
?>
