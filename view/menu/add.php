<?
echo Html::breadcumb(array(
        '菜单管理' => '#',
        '菜单列表' => 'menu/index',
        '添加菜单' => 'active',
));
?>
<form id="menuForm" class="form-horizontal" action="?r=menu/add" method="post">
        <div class="control-group">
                <label class="control-label" for="inputNumber">父级</label>
                <div class="controls">
                        <select id="pid" name="pid">
                                <?= Html::html_options($parents) ?>
                        </select>
                </div>
        </div>
        <div class="control-group">
                <label class="control-label" for="name">菜单名称</label>
                <div class="controls">
                        <input type="text" id="menuName" name="menuName" placeholder="请输入菜单名称">
                </div>
        </div>
        <div class="control-group" id="urlBox" style="display:none;">
                <label class="control-label" for="url">菜单链接</label>
                <div class="controls">
                        <textarea class="input-xlarge" rows="4" id="url" name="url" placeholder="请输入菜单链接">#</textarea>
                </div>
        </div>
        <div class="control-group">
                <div class="controls">
                        <input type="button" id='subBtn' class="btn btn-primary" value="提交" />
                        <input type="reset" class="btn" value="重置" />
                        <?= Html::link('返回列表', 'menu/index', array('class' => 'btn')) ?>
                </div>
        </div>
</form>
<? $this->beginScript(); ?>
<script type="text/javascript" src="<?= JS_URL ?>validator.js"></script>
<script type="text/javascript">
         (function() {
                 $("#pid").change(function() {
                         if($(this).val() == 0) {
                                 $("#urlBox").slideUp();
                         } else {
                                 $("#urlBox").slideDown();
                         }
                 });
                 $('#subBtn').click(function(){
                         $('#menuForm').va({
                                 'required':{
                                         'ele':'#menuName,#url',
                                         'errorAttr':'placeholder'
                                 },
                                 'autoSubmit':true
                         });
                 });
         })();
</script>
<? $this->endScript(); ?>