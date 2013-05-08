<ul class="breadcrumb">
    <li><a href="?r=role/index">角色管理</a> <span class="divider">/</span></li>
    <li><a href="?r=role/index">角色列表</a> <span class="divider">/</span></li>
    <li class="active">添加角色</li>
</ul>
<form id="roleForm" class="form-horizontal" action="?r=role/update" method="post">
    <input type="hidden" name="smt" value="1" />
    <input type="hidden" name="id" value="<?php echo $role['id'] ?>" />
    <div class="control-group">
        <label class="control-label" for="name">角色名称</label>
        <div class="controls">
            <input type="text" id="name" name="name" value="<?php echo $role['name']; ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="info">角色描述</label>
        <div class="controls">
            <textarea class="input-xlarge" rows="4" id="info" name="info"><?php echo $role['info']; ?></textarea>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input type="button" onclick="checkSub();" class="btn btn-info" value="提交" />
            <input type="reset" class="btn" value="重置" />
            <input type="button" class="btn" onclick="location.href='?r=role/index'" value="返回列表" />
        </div>
    </div>
</form>
<script>
    function checkSub() {
        var name = $("#name").val();
        var info = $("#info").val();
        if(name == "") {
            VPFbox.alert("角色名称是必须的");
            return false;
        }
        if(info == "") {
            VPFbox.alert("角色描述必须的");
            return false;
        }
        $("#roleForm").submit();
    }
</script>