<?
echo Html::breadcumb(array(
    '用户设置'=>'#',
    '修改密码'=>'active'
));
?>
<form id="pwdForm" class="form-horizontal" action="?r=master/changePwd" method="post">
    <div class="control-group">
        <label class="control-label" for="currentPwd">当前密码</label>
        <div class="controls">
            <input type="password" id="currentPwd" name="currentPwd" value="" placeholder="请输入当前密码">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="newPwd">新的密码</label>
        <div class="controls">
            <input type="password" id="newPwd" name="newPwd" value="" placeholder="请输入新的密码">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="newPwd2">再输入一次新密码</label>
        <div class="controls">
            <input type="password" id="newPwd2" name="newPwd2" value="" placeholder="请再一次输入新的密码">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button class="btn btn-primary" type="button" id="subBtn">提交</button>
            <button class="btn" type="reset">重置</button>
            <button class="btn" type="button" onclick="location.href='?r=master/index'">返回</button>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?=JS_URL?>jquery.min.js"></script>
<script type="text/javascript" src="<?=JS_URL?>validator.js"></script>
<script type="text/javascript">
    $('#subBtn').click(function(){
        $("#pwdForm").va({
           'required':{
               'ele':'#currentPwd,#newPwd,#newPwd2',
               'errorAttr':'placeholder'
           },
           'compare':{
               'ele':'#newPwd,#newPwd2',
               'errorMessage':'两次输入的密码不一致'
           }
        });
    });
</script>