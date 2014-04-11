<?php
echo Html::breadcumb(array(
        '分支管理' => 'branch/index',
        '添加分支' => 'active',
));
$this->renderPartial('branch/_form');
?>