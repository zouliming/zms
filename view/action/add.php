<ul class="breadcrumb">
    <li><a href="?r=action/index">权限项管理</a> <span class="divider">/</span></li>
    <li><a href="?r=action/index">权限项列表</a> <span class="divider">/</span></li>
    <li class="active">添加权限项</li>
</ul>
<form id="actionForm" class="form-horizontal" action="?r=action/add" method="post">
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
        <label class="control-label" for="name">权限名称</label>
        <div class="controls">
            <input type="text" id="name" name="name">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="info">权限描述</label>
        <div class="controls">
            <textarea class="input-xlarge" rows="4" id="info" name="info"></textarea>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input type="button" onclick="checkSub();" class="btn btn-info" value="提交" />
            <input type="reset" class="btn" value="重置" />
            <input type="button" class="btn" onclick="location.href='?r=action/index'" value="返回列表" />
        </div>
    </div>
</form>
<script>
    function checkSub() {
        var name = $("#name").val();
        var info = $("#info").val();
        if(name == "") {
            VPFbox.alert("权限名称是必须的");
            return false;
        }
        if(info == "") {
            VPFbox.alert("权限描述必须的");
            return false;
        }
        $("#actionForm").submit();
    }
</script>