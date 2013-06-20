<?php

/**
 * 根据设置处理魔术引用
 * @param mixed $value
 * @param boolean $flag 为ture时则强制进行转义
 * @return type
 */
function magic_quotes($value, $flag = FALSE) {
    if (!get_magic_quotes_gpc() || $flag) {
        return is_array($value) ? array_map('magic_quotes', $value) : addslashes($value);
    } else {
        return $value;
    }
}
/**
 * 提示错误信息
 * @param type $status
 * @param type $message
 */
function showError($message,$status=0){
    header('Content-type: text/html; charset=utf-8');
    echo $status!==0?"<h1>".$status."</h1>":"";
    echo "<p>".$message."</p>";
}
/**
 * 提示错误信息，然后进入到某个页面
 * @param type $message
 * @param type $url
 */
function alert($message,$url){
    header('Content-type: text/html; charset=utf-8');
    echo "<script type='text/javascript'>";
    echo "alert('".$message."');";
    echo "window.location.href='". Html::url($url)."';";
    echo "</script>";
    die;
}
/**
 * 获取验证器的快捷方法
 * @return type
 */
function va(){
    return Bee::app()->getValidator();
}
/**
 * 得到Cookie
 * @param type $key
 * @return type
 */
function getCookie($key){
    return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
}
/**
 * 销毁Cookie
 * @param type $key
 */
function unsetCookie($key){
    setcookie($key, "", time() - 3600);
}
/**
 * json数据形式返回
 * @param unknown_type $data
 */
function jsonExit($data) {
    echo json_encode ( $data );
    exit ();
}

/**
 * ajaxTip
 *
 * @param unknown_type $data
 */
function ajaxTip($msg, $callback = "", $parent = false, $width = 215) {
    header('Content-Type: text/html; charset=utf-8');
    $js_string = "<script>";

    if ($callback != "") {
        if ($parent) {
            $js_string .= "parent.";
        }
        $js_string .= $callback;
    }
    if ($parent) {
        $js_string .= "parent.";
    }
    $js_string .= "VPFbox.alert('" . $msg . "'";
    $js_string .= ",function(){}";
    $js_string .= ",'确定'";
    $js_string .= "," . $width . ");";

    $js_string .= "</script>";

    echo $js_string;
    exit();
}
?>