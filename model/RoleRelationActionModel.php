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
    public function changeAction($roleId,$actions){
        $this->delete('`role_id`='.$roleId);
        $r = array();
        foreach($actions as $v){
            $r[] = array(
                'role_id'=>$roleId,
                'action_id'=>$v
            );
        }
        return $this->insertMany($r);
    }
}
?>