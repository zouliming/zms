<?
echo Html::breadcumb(array(
    '角色管理'=>'role/index',
    '添加角色'=>'active'
));
?>
<form id="roleForm" class="form-horizontal" action="?r=role/add" method="post">
    <input type="hidden" name="smt" value="1" />
    <div class="control-group">
        <label class="control-label" for="name">角色名称</label>
        <div class="controls">
            <input type="text" id="name" name="name">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="info">角色描述</label>
        <div class="controls">
            <textarea class="input-xlarge" rows="4" id="info" name="info"></textarea>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input type="submit" class="btn btn-primary" value="提交" />
            <input type="reset" class="btn" value="重置" />
            <?=Html::link('返回列表', 'role/index',array('class'=>'btn'))?>
        </div>
    </div>
</form>
<script>
</script>