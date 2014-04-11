<?php
class Model{
    public $errors = array();
    public static $models = array();
    public $tableName;
    public $db;
    public static function mo($cl= __CLASS__ ){
        $className = ucfirst($cl).'Model';
        if(isset(self::$models[$className])){
            return self::$models[$className];
        }else{
            return (self::$models[$className]=new $className());
        }
    }
    public function __construct() {
        $this->db = Bee::app()->getComponet('Db');
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
    public function getAttributeLabel($attribute=NULL){
        $labels = $this->attributeLabels();
        if($attribute===NULL){
            return $labels;
        }else{
            return $labels[$attribute];
        }
    }
    /***************      便捷方法           **************** */

    /**
     * 得到数量
     * @param String $where where条件语句
     * @return int count数量
     */
    public function getCount($where = "") {
        return $this->db->selectOne("select count(*) from `" . $this->tableName . "` " . $where);
    }

    /**
     * 查询数据
     * @param String $fields 字段名
     * @param String $where where条件
     * @param int $currpage 当前页码
     * @param int $pageSize 每页显示的数量
     * @return Array 二维数组
     */
    public function getAll($fields, $where = '', $currpage = '', $pageSize = '') {
        $sql = 'select  ' . $fields . ' from `' . $this->tableName . '` ' . $where;
        if (!empty($currpage) && !empty($pageSize)) {
            $sql .= ' limit ' . ($currpage - 1) * $pageSize . ',' . $pageSize;
        }
        return $this->db->selectAll($sql);
    }
    /**
     * 查询一条数据
     * @param type $fields
     * @param type $where
     * @return type
     */
    public function getRow($fields,$where = ""){
        $sql = 'select  ' . $fields . ' from `' . $this->tableName . '` ' . $where .' limit 1';
        return $this->db->selectRow($sql);
    }
    public function getCol($fields,$where = ""){
        $sql = 'select  ' . $fields . ' from `' . $this->tableName . '` ' . $where;
        return $this->db->selectCol($sql);
    }
    public function insert($data){
        return $this->db->insert($this->tableName,$data);
    }
    public function insertMany($data){
        return $this->db->insertMany($this->tableName,$data);
    }
    public function update($data,$where=""){
            return $this->db->update($this->tableName,$data,$where);
    }
    public function delete($where){
            return $this->db->delete($this->tableName,$where);
    }
}
?>
