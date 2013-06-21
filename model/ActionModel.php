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
    
    public function formatActions($actions){
        $r = array();
        foreach($actions as $v){
            if($v['parent_id']==0){
                $r[$v['id']] = array(
                    'id'=>$v['id'],
                    'name'=>$v['name']
                );
            }else{
                if(array_key_exists($v['parent_id'], $r)){
                    $r[$v['parent_id']]['children'][] = array(
                        'id'=>$v['id'],
                        'name'=>$v['name']
                    );
                }else{
                    $r[$v['id']] = array(
                        'id'=>$v['id'],
                        'name'=>$v['name']
                    );
                }
            }
        }
        return $r;
    }
    public function getAllActions(){
        $actions = $this->getAll("`id`,`name`,`parent_id`",'order by `parent_id` asc,`id` desc');

        return $actions;
    }
    public function getMasterAction(){
        
    }
    /**
     * 返回操作项信息
     */
    public function getActionList($where = "1=1") {
        return $this->getAll('*',"where ".$where." order by parent_id asc,id asc");
    }

    /**
     * 按roleId返回角色信息
     */
    public function getActionByRole($roleId) {
        $sql = "SELECT * FROM role_relation_action";

        $sql .= " WHERE role_id=" . $roleId;

        return $this->db->selectAll($sql);
    }

    /**
     * 按ID返回操作项
     * @param unknown_type $id
     * @return multitype:
     */
    public function getActionById($id) {
        return $this->db->selectRow("SELECT * FROM " . $this->tableName . " WHERE id=" . $id);
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
