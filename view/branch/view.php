<?php
echo Html::breadcumb(array(
        '分支管理' => 'branch/index',
        '查看分支' => 'active',
));
echo Component::view($model, array(
        'project',
        'branch',
        'owner',
        'from_trunk_version',
        'is_online',
        'remark',
));
?>
<div style="margin-top: 30px;">
        <?php echo Html::link('返回分支管理', array('branch/index'),array('class'=>'btn btn-primary'));?>
</div>