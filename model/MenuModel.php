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
     * 得到菜单的角色
     * @param type $roleId
     * @return type
     */
    public function getMenuRole($roleId){
        return $this->db->selectCol("select role_id from menu_relation_role where menu_id=".$roleId);
    }
    /**
     * 返回菜单信息
     */
    public function getMenuList($where = "") {
        $sql = "SELECT * FROM " . $this->tableName." where `status`=1";
        
        if ($where != "") {
            $sql .= " AND ".$where;
        }
        return $this->db->selectAll($sql);
    }
    /**
     * 得到所有父级菜单
     * @return type
     */
    public function getParentMenu(){
        $data = $this->getMenuList('`parent_id`=0');
        return $this->formatMenu($data);
    }
    /**
     * 格式化菜单信息
     * @param type $menuData
     * @return type
     */
    public function formatMenu($menuData){
        $rs = array();
        foreach($menuData as $v){
            $rs[$v['id']] = $v['name'];
        }
        return $rs;
    }
    /**
     * 按ID返回菜单项
     * @param unknown_type $id
     * @return multitype:
     */
    public function getMenuById($id) {
        return $this->db->selectRow("SELECT * FROM " . $this->tableName . " WHERE id=" . $id);
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
        $this->insert($data);
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
        return $this->update(array('status'=>0), 'id='.$id);
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
