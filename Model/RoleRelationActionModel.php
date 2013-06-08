<?php

class RoleRelationActionModel extends Model{
    public $tableName = "role_relation_action";
    public $_pripary = "id";
    protected function rules(){
        return array(
        );
    }
    public function getActionsByRole($role){
        $data = $this->getCol('action_id',"where `role_id`={$role}");
        return $data;
    }
}
?>