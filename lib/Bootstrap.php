<?php

class BootStrap {

    public static function controlGroup($model, $name, $type, $config = array()) {
        $strHtml = "";
        if (empty($name)) {
            throw new Exception("The name of control group can not be null");
        }
        if(is_array($name)){
            $columnName = $name['name'];
            $label = $name['label'];
        }else{
            $columnName = $name;
            $label = $model->getAttributeLabel($name);
        }
        $groupClass = isset($model->error[$columnName]) ? "control-group error" : "control-group";
        
        $strHtml .= '<div class="' . $groupClass . '">';
        $strHtml .= BootStrap::label($columnName, $label);
        $strHtml .= '<div class="controls">';
        $strHtml .= Html::widget($type, $columnName, $config);
        if(isset($model->errors[$columnName])){
            $strHtml .= "<span class=\"help-inline\">".$model->errors[$columnName]."</span>";
        }
        $strHtml .= '</div></div>';
        return $strHtml;
    }
    public static function buttonGroup($buttonColumns){
        $seperator = "&nbsp;";
        $strHtml = "<div class=\"control-group\"><div class=\"controls\">";
        $buttonTypes = array('button','reset','link','hidden','submit');
        if(is_array($buttonColumns) && !empty($buttonColumns)){
            foreach($buttonColumns as $buttonType => $buttonCol){
                if(in_array($buttonType, $buttonTypes)){
                    $strHtml .= Html::widget($buttonType, "", $buttonCol).$seperator;
                }
            }
        }
        $strHtml .= "</div></div>";
        return $strHtml;
    }
    public static function label($name, $label, $class = "control-label") {
        return "<label class='$class' for='$name'>$label</label>";
    }

}

?>
