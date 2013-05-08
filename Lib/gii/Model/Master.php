<?php

class MasterModel extends Model{
    public $tableName = "master";
    public $_pripary = "id";
    protected function rules(){
        return array(
                'id'=>array('int'),
                'merch_id'=>array('int'),
                'name'=>array('string'),
                'password'=>array('string'),
                'realname'=>array('string'),
                'dept'=>array('string'),
                'position'=>array('string'),
                'create_time'=>array('int'),
                'create_master_id'=>array('int'),
                'update_time'=>array('int'),
                'update_master_id'=>array('int'),
            );
    }
}
?>