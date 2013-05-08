<?php
abstract class Model{
    public static $_debug = false;
    public static $models = array();
    private $link = null;
    
    abstract protected function rules();
    
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
     * 取得结果数据
     *
     * @param resource $query
     * @param int $row 字段的偏移量或者字段名
     * @return mixed
     */
    function result($query, $row) {
        $query = mysql_result($query, $row);
        return $query;
    }

    /**
     * 取得上一步 INSERT 操作产生的 ID
     *
     * @return int
     */
    function insertId() {
        return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
    }
	
    /**
     * 插入/加入表数据
     *
     * @param string $table 数据表名
     * @param array $inlist 数据数组,数组名对应字段名
     * @return resource
     */
    function insertNew($table, $inlist ,$where=null) {
        if(!$table) return;
        if(!is_array($inlist) || count($inlist) == 0) return;
        foreach($inlist as $key => $val) {
                $val=mysql_escape_string($val);
                $set[] = "$key='$val'";
        }
        $SQL = "INSERT $table SET ".implode(", ", $set)." $where";
        return $this->query($SQL);
    }
	
    /**
     * 插入多条表数据
     *
     * @param string $table 数据表名
     * @param array $fields 字段数组
     * @param array $data 数据数组
     * @return resource
     */
    function insertMany($table, $fields, $data) {
        if(!$table) return;
        if(!is_array($data) || count($data) == 0) return;
        foreach($data as $key => $val) {
            foreach($val as $k=>$v) {
                $val[$k] = "'".mysql_escape_string($v)."'";
            }
            $values[] = "(".implode(",", $val).")";
        }
        $SQL = "INSERT INTO {$table}(".implode(",", $fields).") VALUES ".implode(",", $values);

        return $this->query($SQL);
    }
	
    /**
     * 更新表数据
     *
     * @param string $table 数据表名
     * @param string $where 更新条件
     * @param array $uplist 更新的数据数组,数组名对应字段名
     * @return resource
     */
    function updateNew($table,$where,$uplist,$replace=0) {
        if(!$table) return;
        if(!is_array($uplist) || count($uplist) == 0) return;
        $where = $where ? "WHERE $where" : '';
        foreach($uplist as $key => $val) {
            $set[] = "$key='$val'";
        }
        if($replace) {
            $SQL = "REPLACE INTO %s SET %s";
        } else {
            $SQL = "UPDATE %s SET %s";
        }
        $SQL = sprintf($SQL, $table, implode(", ", $set)." $where");
        return $this->query($SQL);
    }
}
?>
