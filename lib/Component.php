<?php
class Component{
        public static function view($model,$attributes){
                $str = '<dl class="dl-horizontal dl-beautiful">';
                if(is_array($attributes)){
                        $attributeLabels = $model->attributeLabels();
                        foreach ($attributes as $key=>$v){
                                $value = "";
                                $name = $v;
                                if(is_array($v)){
                                        if(isset($v['name']) && isset($v['value'])){
                                                $value = isset($v['value'])?$v['value']:"";
                                                $name = $v['name'];
                                        }else{
                                                throw new Exception("view Component配置错误，缺少必有的参数:name 或者 value");
                                        }
                                }else{
                                        $value = $model->$name;
                                }
                                $label = $attributeLabels[$name];
                                $str .= '<dt>'.$label.'</dt><dd>'.$value.'</dd>';
                        }
                }
                $str .= '</dl>';
                return $str;
        }
}