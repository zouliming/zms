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
        $u = APP_URL.'/index.php?r='.$url;
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
        $h = "<a";
        if(is_array($url)){
            $route = array_shift($url);
            $h .= " href=\"".self::url($route,$url)."\"";
        }else{
            $h .= " href=\"".self::url($url)."\"";
        }
        
        if($attribute){
            foreach($attribute as $k=>$v){
                $h .= " ".$k."=\"".$v."\"";
            }
        }
        $h .= ">".$title;
        $h .= "</a>";
        return $h;
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
}
?>
