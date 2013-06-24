<?php
class Html{
    /**
     * 相当于Smarty的同名方法
     * @param type $data 需要循环的数组,键作为value,值作为显示的内容
     * @param type $selected 将要选中的数据
     */
    public static function html_options($data,$selected=null){
        $strHtml = "";
        if($data){
            foreach($data as $k=>$u){
                $selectStr = ($selected!="" && $k==$selected) ? "selected='selected'":"";
                $strHtml .= "<option value='{$k}' {$selectStr}>{$u}</option>";
            }
        }
        return $strHtml;
    }
    /**
     * 相当于Smarty同名方法,生成Checkbox的Html代码
     * @param type $name name属性
     * @param type $options 数据集
     * @param type $seperator 分割Html
     * @param type $selected 将要选中的值
     * @return string 生成的Html内容
     */
    public static function html_checkbox($name,$options,$seperator="",$selected=""){
        $strHtml = "";
        if($options){
            foreach($options as $k=>$option){
                $selectStr = ($selected!="" && $k==$selected) ? "checked='checked'":"";
                $strHtml .= '<label><input type="checkbox" name="'.$name.'[]" value="'.$k.'" '.$selectStr.'/>'.$option.'</label>'.$seperator;
            }
        }
        return $strHtml;
    }
    /**
     * 标准化url
     * @param String $url 类似“site/index”
     * @param Array $param 代表附加参数
     * @return type
     */
    public static function url($url,$param=array()){
        $u = '/index.php?r='.$url;
        if($param){
            foreach($param as $k=>$v){
                $u .= '&'.$k.'='.$v;
            }
        }
        return $u;
    }
    /**
     * 生成<a>标签
     * @param String $title 链接显示的文字
     * @param Array $url 二维数组，第一个元素代表路由，后面的元素代表附加参数
     * @param Array $attribute 二维数组 a标签的属性
     * @return Html 生成的Html内容
     */
    public static function link($title,$url,$attribute = array()){
        $href = $attrStr = $desc ="";
        if(is_array($url)){
            $route = array_shift($url);
            $href = self::url($route,$url);
        }else{
            $href = self::url($url);
        }
        if($attribute){
            $attrStr = Html::attributes($attribute);
        }
        $h = "<a href=\"{$href}\" {$attrStr}>{$title}</a>";
        return $h;
    }
    
    public static function specialchars($str, $double_encode = TRUE) {
        $str = (string) $str;

        //默认是过滤html实体的
        if ($double_encode === TRUE) {
            $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        } else {
            //这里不会过滤html实体
            // 从PHP 5.2.3 开始，系统增加了自带的方法，其他情况只能使用正则了
            if (version_compare(PHP_VERSION, '5.2.3', '>=')) {
                $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8', FALSE);
            } else {
                $str = preg_replace('/&(?!(?:#\d++|[a-z]++);)/ui', '&amp;', $str);
                $str = str_replace(array('<', '>', '\'', '"'), array('&lt;', '&gt;', '&#39;', '&quot;'), $str);
            }
        }

        return $str;
    }
    public static function attributes($attrs) {
        if (empty($attrs))
            return '';

        if (is_string($attrs))
            return ' ' . $attrs;

        $compiled = '';
        foreach ($attrs as $key => $val) {
            $compiled .= ' ' . $key . '="' . Html::specialchars($val) . '"';
        }

        return $compiled;
    }

    /**
     * 生成面包屑
     * @param array $infos 一个二维数组，key是链接文字，value是链接，比如“site/index”
     * 如果需要指定哪个是当前的位置，只需要把value的值设置为active即可
     * @return html
     */
    public static function breadcumb($infos){
        $c = count($infos);
        $i = 1;
        $str = '<ul class="breadcrumb">';
        foreach($infos as $t=>$u){
            if($u=="active"){
                $str .= '<li class="active">'.$t.'</li>';
            }else{
                $url = strpos($u, '/')?Html::url($u):$u;
                $str .= '<li><a href="'.$url.'">'.$t.'</a>';
            }
            if($i++!=$c){
                $str .= '<span class="divider">/</span></li>';
            }
        }
        $str .= '</ul>';
        return $str;
    }
    public static function input($data, $value = '', $extra = ''){
        if (!is_array($data)) {
            $data = array('name' => $data);
        }
        // Type and value are required attributes
        $data += array(
            'type' => 'text',
            'value' => $value
        );
        return '<input' . self::attributes($data) . ' ' . $extra . ' />';
    }
    public static function textarea($data, $value = '', $extra = '', $double_encode = TRUE) {
        if (!is_array($data)) {
            $data = array('name' => $data);
        }

        // Use the value from $data if possible, or use $value
        $value = isset($data['value']) ? $data['value'] : $value;

        // Value is not part of the attributes
        unset($data['value']);

        return '<textarea' . self::attributes($data, 'textarea') . ' ' . $extra . '>' . self::specialchars($value, $double_encode) . '</textarea>';
    }
    public static function dropdown($data, $options = NULL, $selected = NULL, $extra = '') {
        if (!is_array($data)) {
            $data = array('name' => $data);
        } else {
            if (isset($data['options'])) {
                // Use data options
                $options = $data['options'];
            }

            if (isset($data['selected'])) {
                // Use data selected
                $selected = $data['selected'];
            }
        }

        if (is_array($selected)) {
            // Multi-select box
            $data['multiple'] = 'multiple';
        } else {
            // Single selection (but converted to an array)
            $selected = array($selected);
        }

        $input = '<select' . self::attributes($data, 'select') . ' ' . $extra . '>' . "\n";
        foreach ((array) $options as $key => $val) {
            // Key should always be a string
            $key = (string) $key;

            if (is_array($val)) {
                $input .= '<optgroup label="' . $key . '">' . "\n";
                foreach ($val as $inner_key => $inner_val) {
                    // Inner key should always be a string
                    $inner_key = (string) $inner_key;

                    $sel = in_array($inner_key, $selected) ? ' selected="selected"' : '';
                    $input .= '<option value="' . $inner_key . '"' . $sel . '>' . $inner_val . '</option>' . "\n";
                }
                $input .= '</optgroup>' . "\n";
            } else {
                $sel = in_array($key, $selected) ? ' selected="selected"' : '';
                $input .= '<option value="' . $key . '"' . $sel . '>' . $val . '</option>' . "\n";
            }
        }
        $input .= '</select>';

        return $input;
    }
    public static function form($model,$columns){
        echo '<form class="form-horizontal" method="post">';
        $labels = $model->attributeLabels();
        foreach($columns as $column=>$columnConfig){
            echo '<div class="control-group">';
            echo '<label class="control-label" for="'.$column.'">'.$labels[$column].'</label>';
            echo '<div class="controls">';
            if(is_array($columnConfig)){
                $inputType = array_shift($columnConfig);
                switch ($inputType){
                    case 'textarea':
                        echo self::textarea($column,$model->$column,'class="input-xlarge" rows="4"');
                        break;
                    case 'dropdown':
                        echo self::dropdown($columnConfig);
                        break;
                    default:
                        echo self::input($column,$model->$column);
                        break;
                }
            }else{
                echo self::input($column,$model->$column);
            }
            echo '<span class="help-inline">'.$model->getErrors($column).'</span>';
            echo '</div>';
            echo '</div>';
        }
        echo '<div class="control-group">
                <div class="controls">
                    <input type="submit" class="btn btn-info" value="提交" />
                    <input type="reset" class="btn" value="重置" />';
        echo Html::link('返回列表','role/index',array('class'=>'btn'));
        echo '</div></div></form>';
    }
}
?>
