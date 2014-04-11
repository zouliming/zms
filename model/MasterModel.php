<?php

/**
 * 后台用户model
 * @author bric.shi
 * @create 2013.04.17
 */
class MasterModel extends ActiveRecord {
    
    public $tableName = "master";

    public function attributeLabels(){
        return array(
            'id'=>'id',
            'name'=>'用户名',
            'password'=>'密码',
            'role'=>'角色',
            'realname'=>'真实姓名',
            'sex'=>'性别',
            'dept'=>'部门',
            'position'=>'职务',
            'create_time'=>'创建时间',
            'create_master_id'=>'创建者id',
            'update_time'=>'修改时间',
            'update_master_id'=>'修改者id',
            'enable'=>'1:有效2无效',
        );
    }
    protected function rules() {
        return array(
            array('name,password,dept,position','required'),
            array('create_time,update_time','uint'),
            array('sex','equal','in'=>array('男','女'))
        );
    }

    /**
     * 返回后台用户信息
     */
    public function getMasterList($where = "", $limit = '0,200', $orderby = "id DESC") {
        $sql = "SELECT id,name,password,realname,sex,dept,position,create_time,create_master_id,update_time,update_master_id FROM " . $this->tableName;

        if ($where != "") {
            $sql .= " WHERE " . $where;
        }

        $sql .= " ORDER BY " . $orderby;
        $sql .= " LIMIT " . $limit;

        return $this->db->selectAll($sql);
    }

    /**
     * 按ID返回后台用户
     * @param unknown_type $id
     * @return multitype:
     */
    public function getMasterById($id) {
        return $this->db->selectRow("SELECT * FROM " . $this->tableName . " WHERE id=" . $id);
    }
    /**
     * 按masterId返回角色信息
     */
    public function getMasterRole($masterId) {
        $sql = "SELECT `role` FROM master WHERE `id`=" . $masterId;
        $data = $this->db->selectOne($sql);
        $r = empty($data)?"":explode(',',$data);
        return $r;
    }
    /**
     * 按ID返回后台用户名称
     * @param unknown_type $id
     * @return multitype:
     */
    public function getMasterNameById($id) {
        $master = $this->db->selectCol("SELECT name FROM " . $this->tableName . " WHERE id=" . $id);
        return $master[0];
    }

    /**
     * 删除指定后台用户
     * @param unknown_type $id
     * @return boolean
     */
    public function delMaster($id) {
        return $this->update(array(
            'enable'=>2
        ), 'id='.$id);
    }

    /**
     * 设置对应用户的角色
     * @param unknown_type $data
     */
    public function setRole($data) {
        return $this->insertMany("master_relation_role", array('master_id', 'master_realname', 'role_id', 'role_name', 'update_master_id', 'update_master_name', 'update_time'), $data);
    }

    /**
     * 验证用户身份信息
     * @param type $username
     * @param type $password
     * @return type
     */
    public function authMaster($username, $password) {
        return $this->db->selectRow("select * from master where `name`='" . $username . "' and `password`='" . md5($password) . "' and `enable`=1");
    }
    
    /**
     * 删除指定master对应的role关系
     * @param unknown_type $id
     * @return boolean
     */
    public function delRoleByMaster($id) {
        $sql = "DELETE FROM master_relation_role WHERE master_id=" . $id;
        $this->query($sql);
    }

}

?>
