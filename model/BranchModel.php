<?php

class BranchModel extends ActiveRecord {

        protected $_primaryKey = "id";

        protected function rules() {
                return array(
                );
        }

        public $tableName = "branch";

        public function attributeLabels() {
                return array(
                        'project' => '项目',
                        'branch' => '分支',
                        'owner' => '负责人',
                        'from_trunk_version' => '起始Trunk版本',
                        'is_online' => '是否已经上线',
                        'remark' => '备注',
                );
        }

        /**
         * 展示是否已经上线
         * @param type $v
         * @return type
         */
        public static function showIsOnline($v) {
                $config = array(
                        0 => '尚未上线',
                        1 => '已经上线'
                );
                return isset($config[$v]) ? $config[$v] : "";
        }

}

?>