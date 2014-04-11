<?
echo Html::breadcumb(array(
    '用户管理' => 'master/index',
    '添加用户' => 'active'
));
echo Html::form('master/add', 'post',array('id'=>'masterForm','class'=>'form-horizontal','onsubmit'=>'return checkForm()'));
echo BootStrap::controlGroup($model, 'name','text');
echo BootStrap::controlGroup($model, 'realname','text');
echo BootStrap::controlGroup($model, array('name'=>'pwd','label'=>'密码'),'password');
echo BootStrap::controlGroup($model, array('name'=>'pwadg','label'=>'确认密码'),'password');
echo BootStrap::controlGroup($model, 'sex','radio',array(
    'items'=>array(
        '男'=>'男',
        '女'=>'女',
    ),
    'selected'=>'男'
));
echo BootStrap::controlGroup($model, 'dept', 'text');
echo BootStrap::controlGroup($model, 'position', 'text');
echo BootStrap::buttonGroup(array(
    'submit'=>array('id'=>'subBtn','value'=>'提交','class'=>'btn btn-primary'),
    'reset'=>array('value'=>'重置','class'=>'btn'),
    'link'=>array('class'=>'btn','text'=>'返回列表','href'=>'master/index')
));
echo Html::endForm();
?>
<? $this->beginScript(); ?>
<script type="text/javascript" src="<?= JS_URL ?>validator.js"></script>
<script>
    var checkForm = function(){
        return $('#masterForm').va({
            'required':{
                'ele':'#name,#pwd,#pwdag,#dept,#position'
            },
            'compare':[{
                    'ele':'#pwd,#pwdag',
                    'errorMessage':'两次密码不一致'
            }],
            'autoSubmit':false
        });
    }
</script>
<? $this->endScript(); ?>