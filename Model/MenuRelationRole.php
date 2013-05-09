<?php

class MenuRelationRoleModel extends Model{
    public $tableName = "menu_relation_role";
    public $_pripary = "";
    protected function rules(){
        return array(
                'menu_id'=>array('int'),
                'role_id'=>array('int'),
            );
    }
}
?>