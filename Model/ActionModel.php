<?php

/**
 * 操作权限管理
 * @author bric.shi
 * @create 2013.04.17
 */
class ActionModel extends Model {

    protected function rules() {
        
    }

    public $tableName = "action";

    /**
     * 返回操作项信息
     */
    public function getActionList($where = "", $limit = '0,1000', $orderby = "id DESC") {
        $sql = "SELECT id,parent_id,name,info,update_master_id,update_master_name,update_time FROM " . $this->tableName;

        if ($where != "") {
            $sql .= " WHERE " . $where;
        }

        $sql .= " ORDER BY " . $orderby;
        $sql .= " LIMIT " . $limit;

        return $this->selectAll($sql);
    }

    /**
     * 按roleId返回角色信息
     */
    public function getActionByRole($roleId) {
        $sql = "SELECT * FROM role_relation_action";

        $sql .= " WHERE role_id=" . $roleId;

        return $this->selectAll($sql);
    }

    /**
     * 按ID返回操作项
     * @param unknown_type $id
     * @return multitype:
     */
    public function getActionById($id) {
        return $this->selectRow("SELECT * FROM " . $this->tableName . " WHERE id=" . $id);
    }

    /**
     * 更新操作项
     * @param unknown_type $where
     * @param unknown_type $data
     */
    public function updateAction($where, $data) {
        $this->updateNew($this->tableName, $where, $data);
    }

    /**
     * 添加操作项
     * @param unknown_type $data
     */
    public function addAction($data) {
        $this->insertNew($this->tableName, $data);
    }

    /**
     * 删除指定操作项
     * @param unknown_type $id
     * @return boolean
     */
    public function delAction($id) {
        $sql = "DELETE FROM " . $this->tableName . " WHERE id=" . $id;
        $this->query($sql);
    }

}

?>
