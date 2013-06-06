<?
echo Html::breadcumb(array(
    '角色管理'=>'role/index',
    '添加角色'=>'active'
));
echo Html::form($model, array(
    'name'=>'',
    'info'=>array('textarea')
));
?>