<?php

/**
 * 菜单管理
 * @author bric.shi
 * @create 2013.04.18
 */
class MenuModel extends Model {

    public $tableName = "menu";
    public $_pripary = "id";

    protected function rules() {
        return array(
            'id' => array('int'),
            'name' => array('string'),
            'url' => array('string'),
            'type' => array('int'),
            'parent' => array('int')
        );
    }

    /**
     * 返回菜单信息
     */
    public function getMenuList($where = "", $limit = '0,1000', $orderby = "id DESC") {
        $sql = "SELECT id,parent_id,name,url FROM " . $this->tableName;

        if ($where != "") {
            $sql .= " WHERE " . $where;
        }

        $sql .= " ORDER BY " . $orderby;
        $sql .= " LIMIT " . $limit;

        return $this->selectAll($sql);
    }

    /**
     * 按ID返回菜单项
     * @param unknown_type $id
     * @return multitype:
     */
    public function getMenuById($id) {
        return $this->selectRow("SELECT * FROM " . $this->tableName . " WHERE id=" . $id);
    }

    /**
     * 更新菜单项
     * @param unknown_type $where
     * @param unknown_type $data
     */
    public function updateMenu($where, $data) {
        $this->updateNew($this->tableName, $where, $data);
    }

    /**
     * 添加菜单项
     * @param unknown_type $data
     */
    public function addMenu($data) {
        $this->insertNew($this->tableName, $data);
    }

    /**
     * 设置对应角色的菜单
     * @param unknown_type $data
     */
    public function setMenus($data) {
        return $this->insertMany("menu_relation_role", array('menu_id', 'role_id'), $data
        );
    }

    /**
     * 删除指定菜单项
     * @param unknown_type $id
     * @return boolean
     */
    public function delMenu($id) {
        $sql = "DELETE FROM " . $this->tableName . " WHERE id=" . $id;
        $this->query($sql);
    }
    
    /**
     * 删除指定menu对应的role关系
     * @param unknown_type $id
     * @return boolean
     */
    public function delRoleByMenu($id) {
        $sql = "DELETE FROM menu_relation_role WHERE menu_id=" . $id;
        $this->query($sql);
    }

}

?>
