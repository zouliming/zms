<ul class="breadcrumb">
    <li><a href="?r=menu/index">菜单管理</a> <span class="divider">/</span></li>
    <li><a href="?r=menu/index">菜单列表</a> <span class="divider">/</span></li>
    <li class="active">添加菜单</li>
</ul>
<form id="menuForm" class="form-horizontal" action="?r=menu/add" method="post">
    <input type="hidden" name="smt" value="1" />
    <div class="control-group">
        <label class="control-label" for="inputNumber">父级</label>
        <div class="controls">
            <select id="pid" name="pid">
                <option value="0">作为父级</option>
                <?php foreach ($parents as $parent) { ?>
                    <option value="<?php echo $parent['id']; ?>"><?php echo $parent['name']; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="name">菜单名称</label>
        <div class="controls">
            <input type="text" id="name" name="name">
        </div>
    </div>
    <div class="control-group" id="urlBox" style="display:none;">
        <label class="control-label" for="url">菜单链接</label>
        <div class="controls">
            <textarea class="input-xlarge" rows="4" id="url" name="url">#</textarea>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input type="button" onclick="checkSub();" class="btn btn-info" value="提交" />
            <input type="reset" class="btn" value="重置" />
            <input type="button" class="btn" onclick="location.href='?r=menu/index'" value="返回列表" />
        </div>
    </div>
</form>
<style>
    body{background:#fff;}
</style>
<script>
    $(function() {
        $("#pid").change(function() {
            if($(this).val() == 0) {
                $("#urlBox").hide();
                $("#url").val("#");
            } else {
                $("#urlBox").show();
                $("#url").val("");
            }
        })
    })
    function checkSub() {
        var name = $("#name").val();
        if(name == "") {
            VPFbox.alert("菜单名称是必须的");
            return false;
        }
        $("#menuForm").submit();
    }
</script>