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

    /**
     * 插入数据
     * @param array $data 将要插入的数据 需要键值对应
     * @return mixed 成功返回最新插入的数据的Id，失败返回false
     */
    public function insert($data) {
        if (!is_array($data) || count($data) == 0)
            return false;
        $cols = $values = "";
        foreach ($data as $key => $val) {
            $values .= "'" . mysqli_real_escape_string($this->link, $val) . "',";
            $cols .= "`" . trim($key) . "`,";
        }
        $cols = rtrim($cols, ',');
        $values = rtrim($values, ',');
        $sql = "INSERT INTO {$this->tableName} ({$cols}) VALUES ({$values})";
        $result = $this->query($sql);
        if ($result) {
            return $this->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * 插入多条表数据
     * @param array $data 数据数组
     * @return resource
     */
    public function insertMany($data) {
        if (!is_array($data) || count($data) == 0)
            return;
        $values = "";
        $keys = array_keys($data[0]);
        $cols = implode('`,`', $keys);
        foreach ($data as $key => $val) {
            $values .= "(";
            foreach ($val as $k => $v) {
                $values .= "'" . mysqli_real_escape_string($this->link, $v) . "',";
            }
            $values = rtrim($values, ',');
            $values .= "),";
        }
        $cols = '`' . $cols . '`';
        $values = rtrim($values, ',');
        $sql = "INSERT INTO {$this->tableName} ({$cols}) VALUES {$values}";
        $result = $this->query($sql);
        if ($result) {
            return $this->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * 更改数据
     * @param array $data 二维数组
     * @param String $where where条件语句
     * @return boolean
     */
    public function update($data, $where) {
        $tem = "";
        foreach ($data as $k => $v) {
            $tem .= " `{$k}`='" . mysqli_real_escape_string($this->link, $v) . "',";
        }
        $tem = rtrim($tem, ',');
        $sql = "update " . $this->tableName . "  SET {$tem} WHERE {$where}";
        $result = $this->query($sql);
        if ($result) {
            return $this->affectRows();
        } else {
            return false;
        }
    }

    /**
     * 删除数据
     * @param type $where
     * @return type
     */
    public function delete($where) {
        $sql = "DELETE from " . $this->tableName . " WHERE " . $where;
        return $this->execute($sql);
    }
    
}
?>
