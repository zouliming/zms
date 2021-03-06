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
                if (BEE_DEBUG == 2)
                        $beginTime = microtime(TRUE);
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
                if (BEE_DEBUG == 2)
                        Bee::$data['debug']['db'][] = array('sql' => 'Connect DB Server', 'time' => microtime(TRUE) - $beginTime);
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
                if (BEE_DEBUG == 2)
                        $beginTime = microtime(TRUE);
                $result = mysqli_query($this->link, $sql);
                if (BEE_DEBUG == 2)
                        Bee::$data['debug']['db'][] = array('sql' => $sql, 'time' => microtime(TRUE) - $beginTime);
                if ($result == false && (BEE_DEBUG > 0 || self::$_debug)) {
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

        /**
         * 插入数据
         * @param array $data 将要插入的数据 需要键值对应
         * @return mixed 成功返回最新插入的数据的Id，失败返回false
         */
        public function insert($tableName, $data) {
                if (!is_array($data) || count($data) == 0)
                        return false;
                $cols = $values = "";
                foreach ($data as $key => $val) {
                        $values .= "'" . mysqli_real_escape_string($this->link, $val) . "',";
                        $cols .= "`" . trim($key) . "`,";
                }
                $cols = rtrim($cols, ',');
                $values = rtrim($values, ',');
                $sql = "INSERT INTO {$tableName} ({$cols}) VALUES ({$values})";
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
        public function insertMany($tableName, $data) {
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
                $sql = "INSERT INTO {$tableName} ({$cols}) VALUES {$values}";
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
        public function update($tableName,$data, $where) {
                $tem = "";
                foreach ($data as $k => $v) {
                        $tem .= " `{$k}`='" . mysqli_real_escape_string($this->link, $v) . "',";
                }
                $tem = rtrim($tem, ',');
                $sql = "update {$tableName} " . $this->tableName . "  SET {$tem} WHERE {$where}";
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
        public function delete($tableName,$where) {
                $sql = "DELETE from {$tableName}" . " WHERE " . $where;
                return $this->execute($sql);
        }

}

?>
