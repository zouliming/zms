<?php
echo Html::breadcumb(array(
        '分支管理' => 'branch/index',
        '修改分支' => 'active',
));
$this->renderPartial('branch/_form',array(
        'model'=>$model
));
?>