<?php

/**
 * 角色管理
 * @author bric.shi
 * @create 2013.04.17
 */
class RoleModel extends ActiveRecord {
    public $tableName = "role";
    protected $_primaryKey = "id";
    protected function rules(){
        return array(
            'name'=>array('required'),
            'info'=>array('required'),
            'update_master_id'=>array('required','uint'),
            'update_master_name'=>array('required'),
            'update_time'=>array('required','uint'),
        );
    }
    public function attributeLabels(){
        return array(
            'id' => '角色id',
            'name' => '角色名称',
            'info' => '角色描述',
            'update_master_id' => '最近修改者id',
            'update_master_name' => '最近修改人',
            'update_time' => '最近修改日期',
        );
    }
    
    /**
     * 返回所有角色
     */
    public function getAllRoles() {
        $roles = $this->getAll("`id`,`name`",'');
        $r = array();
        foreach($roles as $v){
            $r[$v['id']] = $v['name'];
        }
        return $r;
    }

    /**
     * 按menuId返回角色信息
     */
    public function getRoleListByMenu($menuId) {
        $sql = "SELECT mrr.menu_id,r.* FROM menu_relation_role mrr left join ".$this->tableName . " r on (r.id=mrr.role_id) WHERE mrr.menu_id=" . $menuId;

        return $this->db->selectAll($sql);
    }

    /**
     * 按ID返回角色
     * @param unknown_type $id
     * @return multitype:
     */
    public function getRoleById($id) {
        return $this->db->selectRow("SELECT * FROM " . $this->tableName . " WHERE id=" . $id);
    }
    public function getRoleList(){
        return $this->getAll('*');
    }
    
    /**
     * 按ID返回角色名称
     * @param unknown_type $id
     * @return multitype:
     */
    public function getRoleNameById($id) {
        $role = $this->db->selectCol("SELECT name FROM " . $this->tableName . " WHERE id=" . $id);
        return $role[0];
    }

    /**
     * 更新操作项
     * @param unknown_type $where
     * @param unknown_type $data
     */
    public function updateRole($where, $data) {
        $this->updateNew($this->tableName, $where, $data);
    }

    /**
     * 删除指定操作项
     * @param unknown_type $id
     * @return boolean
     */
    public function delRole($id) {
        $sql = "DELETE FROM " . $this->tableName . " WHERE id=" . $id;
        $this->query($sql);
    }

    /**
     * 设置对应角色的权限
     * @param unknown_type $data
     */
    public function setActions($data) {
        return $this->insertMany("role_relation_action", array('role_id', 'role_name', 'action_id', 'action_name', 'update_master_id', 'update_master_name', 'update_time'), $data
        );
    }
    
    /**
     * 删除指定role对应的action关系
     * @param unknown_type $id
     * @return boolean
     */
    public function delActionByRole($id) {
        $sql = "DELETE FROM role_relation_action WHERE role_id=" . $id;
        $this->query($sql);
    }

}

?>
