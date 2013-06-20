<?php
class ActiveRecord extends Model{
    private $_new = true;
    protected $_primaryKey = "";
    private $_primaryValue = "";
    protected $_attributes = array();//记录数据库的字段和值
    
    public function __set($name, $value) {
        $this->_attributes[$name] = $value;
    }
    public function __get($name) {
        return $this->_attributes[$name];
    }
    public function isNewRecord(){
        return $this->_new ? TRUE : FALSE;
    }
    private function setNewRecord($isNew){
        $this->_new = $isNew;
    }
    public function save($runValidation = true){
        if(!$runValidation || $this->validate()){
            return $this->isNewRecord() ? $this->insert($this->_attributes) : $this->updateByPrimary();
        }else{
            return false;
        }
    }
    public function find($id){
        if(($data=$this->getRow("*"," where " . $this->_primaryKey."='".$id."'"))){
            $this->_attributes = $data;
            $this->_primaryValue = $id;
            $this->setNewRecord(false);
            return $this;
        }else{
            return null;
        }
    }
    public function updateByPrimary(){
        return $this->update($this->_attributes,  $this->_primaryKey."=".$this->_primaryValue);
    }
    public function loadValue($data){
        $this->_attributes = array_merge($this->_attributes,$data);
        return $this;
    }
}

?>
