<?php
class Model{
    public $tableName;
    public static $_debug = false;
    public static $models = array();
    private $link = null;
    
    public static function mo($cl= __CLASS__ ){
        $className = ucfirst($cl).'Model';
        if(isset(self::$models[$className])){
            return self::$models[$className];
        }else{
            return (self::$models[$className]=new $className());
        }
    }
    function __construct() {
        $db = AppConfig::$databaseSet;
        $this->_getDB($db);
    }
    public static function debug(){
        self::$_debug = true;
    }
    private function _getDB($db){
        $this->link = mysqli_connect($db['host'], $db['username'], $db['password']);
        if(!$this->link) {
            $this->halt('DATABASE CONNECT ERROR');
        }
        if($db['database']) {
            mysqli_select_db($this->link,$db['database']);
        }
        if(isset($db['encoding'])){
            mysqli_set_charset($this->link, $db['encoding']);
        }else{
            mysqli_set_charset($this->link, 'utf8');
        }
        return true;
    }
    /**
     * 执行一段Sql语句
     * @param String $sql
     * @return int 返回影响的行数
     */
    public function execute($sql){
        $this->query($sql);
        return $this->affectRows();
    }
    public function query($sql){
        if(self::$_debug) echo "<p>".$sql."<br></p>";
        $result = mysqli_query($this->link,$sql);
        if($result==false){
            header('Content-type: text/html; charset=utf-8');
            echo "查询时发生了错误：".mysqli_error($this->link)."<br/>";
            echo "<font style='color:red'>SQL:".$sql."</font>";
            die;
        }
        return $result;
    }
    /**
     * 返回影响的行数
     * @return int
     */
    public function affectRows(){
        return	mysqli_affected_rows($this->link);
    }
    public function selectAll($sql){
        $result = $this->query($sql);
        $r = array();
        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
            array_push($r, $row);
        }
        mysqli_free_result($result);
        return $r;
    }
    public function selectCol($sql){
        $result = $this->query($sql);
        $r=array();
        while (($row = mysqli_fetch_array($result, MYSQLI_NUM))!=false) {
            array_push($r,$row[0]);
        }
        mysqli_free_result($result);
        return $r;	
    }
    public function selectRow($sql){
        $result = $this->query($sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        return $row;	
    }
    public function selectOne($sql){
        $result = $this->query($sql);
        $row = mysqli_fetch_array($result, MYSQLI_NUM);
        mysqli_free_result($result);
        if($row===false){
            return false;
        }else{
            return $row[0];
        }
    }
    /*********      便捷方法           *****************/
    /**
     * 得到数量
     * @param String $where where条件语句
     * @return int count数量
     */
    public function getCount($where=""){
        return $this->selectOne("select count(*) from `".$this->tableName."` ".$where);
    }
    /**
     * 查询数据
     * @param String $fields 字段名
     * @param String $where where条件
     * @param int $currpage 当前页码
     * @param int $pageSize 每页显示的数量
     * @return Array 二维数组
     */
    public function getAll($fields,$where,$currpage='',$pageSize=''){
        $sql = 'select  '.$fields.' from `'.$this->tableName.'` '. $where;
        if(!empty($currpage) && !empty($pageSize) ){
            $sql .= ' limit '.($currpage-1)*$pageSize.','.$pageSize;
        }
        return $this->selectAll($sql);
    }
    /**
     * 插入数据
     * @param array $data 将要插入的数据 需要键值对应
     * @return mixed 成功返回最新插入的数据的Id，失败返回false
     */
    function insert($data) {
        if(!is_array($data) || count($data) == 0) return;
        $cols = $values = "";
        foreach($data as $key => $val) {
            $values .= "'" . mysqli_real_escape_string($this->link,$val) . "',";
            $cols .= "`" . trim($key) . "`,";
        }
        $cols = rtrim( $cols, ',');
        $values = rtrim( $values, ',');
        $sql = "INSERT INTO {$this->tableName} ({$cols}) VALUES ({$values})";
        $result = $this->query($sql);
        if($result){
            return $this->lastInsertId();
        }else{
            return false;
        }
    }
	
    /**
     * 插入多条表数据
     * @param array $data 数据数组
     * @return resource
     */
    function insertMany($data) {
        if(!is_array($data) || count($data) == 0) return;
        $values = "";
        $keys = array_keys($data[0]);
        $cols = implode('`,`', $keys);
        foreach($data as $key => $val) {
            $values .= "(";
            foreach($val as $k=>$v) {
                $values .= "'" . mysqli_real_escape_string($this->link,$v) . "',";
            }
            $values = rtrim( $values, ',');
            $values .= "),";
        }
        $cols = '`'.$cols.'`';
        $values = rtrim( $values, ',');
        $sql = "INSERT INTO {$this->tableName} ({$cols}) VALUES {$values}";
        $result = $this->query($sql);
        if($result){
            return $this->lastInsertId();
        }else{
            return false;
        }
    }
    /**
     * 更改数据
     * @param array $data 二维数组
     * @param String $where where条件语句
     * @return boolean
     */
    public function update($data,$where){
        $tem = "";
        foreach ($data as $k=>$v){
            $tem .= " `{$k}`='" . mysqli_real_escape_string($this->link,$v) . "',";
        }
        $tem = rtrim($tem, ',');
        $sql = "update ".$this->tableName."  SET {$tem} WHERE {$where}";
        $result = $this->query($sql);
        if($result){
            return $this->affectRows();
        }else{
            return false;
        }
    }
    /**
     * 删除数据
     * @param type $where
     * @return type
     */
    public function delete($where){
        $sql = "DELETE from ".$this->tableName." WHERE ".$where;
        return $this->execute($sql);
    }
    /**
     * 
     * 获取最近新增记录id：需紧跟insert后读取
     * @return int $insertId , 自增id
     */
    public function lastInsertId() {
        return mysqli_insert_id($this->link);
    }
}
?>
