<?php

class Db {
    public static $_debug = false;
    private $link = null;
    public static $instance;
    function __construct() {
        $db = Bee::app()->getConfig('database');
        $this->_getDB($db);
    }
    
    public static function debug() {
        self::$_debug = true;
    }

    private function _getDB($db) {
        if(BEE_DEBUG) $beginTime = microtime(TRUE);
        $this->link = mysqli_connect($db['host'], $db['username'], $db['password']);
        if (!$this->link) {
            $this->halt('DATABASE CONNECT ERROR');
        }
        if ($db['database']) {
            mysqli_select_db($this->link, $db['database']);
        }
        if (isset($db['encoding'])) {
            mysqli_set_charset($this->link, $db['encoding']);
        } else {
            mysqli_set_charset($this->link, 'utf8');
        }
        if(BEE_DEBUG) Bee::$data['debug']['db'][] = array('sql'=>'Connect DB Server','time'=>microtime(TRUE)-$beginTime);
        return true;
    }

    /**
     * 执行一段Sql语句
     * @param String $sql
     * @return int 返回影响的行数
     */
    public function execute($sql) {
        $this->query($sql);
        return $this->affectRows();
    }

    public function query($sql) {
        if (self::$_debug)
            echo "<p>" . $sql . "<br></p>";
        if(BEE_DEBUG) $beginTime = microtime(TRUE);
        $result = mysqli_query($this->link, $sql);
        if(BEE_DEBUG) Bee::$data['debug']['db'][] = array('sql'=>$sql,'time'=>microtime(TRUE)-$beginTime);
        if ($result == false && (BEE_DEBUG || self::$_debug)) {
            header('Content-type: text/html; charset=utf-8');
            echo "查询时发生了错误：" . mysqli_error($this->link) . "<br/>";
            echo "<font style='color:red'>SQL:" . $sql . "</font>";
            die;
        }
        return $result;
    }

    /**
     * 返回影响的行数
     * @return int
     */
    public function affectRows() {
        return mysqli_affected_rows($this->link);
    }

    public function selectAll($sql) {
        $result = $this->query($sql);
        $r = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            array_push($r, $row);
        }
        mysqli_free_result($result);
        return $r;
    }

    public function selectCol($sql) {
        $result = $this->query($sql);
        $r = array();
        while (($row = mysqli_fetch_array($result, MYSQLI_NUM)) != false) {
            array_push($r, $row[0]);
        }
        mysqli_free_result($result);
        return $r;
    }

    public function selectRow($sql) {
        $result = $this->query($sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        return $row;
    }

    public function selectOne($sql) {
        $result = $this->query($sql);
        $row = mysqli_fetch_array($result, MYSQLI_NUM);
        mysqli_free_result($result);
        if ($row === false) {
            return false;
        } else {
            return $row[0];
        }
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
