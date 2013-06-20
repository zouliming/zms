<ul class="breadcrumb">
    <li><a href="?r=master/index">用户管理</a> <span class="divider">/</span></li>
    <li><a href="?r=master/index">用户列表</a> <span class="divider">/</span></li>
    <li class="active">修改用户</li>
</ul>
<form id="masterForm" class="form-horizontal" action="?r=master/update" method="post">
    <input type="hidden" name="smt" value="1" />
    <input type="hidden" name="id" value="<?php echo $master['id'] ?>" />
    <div class="control-group">
        <label class="control-label" for="name">用户名</label>
        <div class="controls">
            <input type="text" id="name" name="name" value="<?php echo $master['name'] ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="realname">真实姓名</label>
        <div class="controls">
            <input type="text" id="truename" name="truename" value="<?php echo $master['realname'] ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="sex">性别</label>
        <div class="controls">
            <input type="radio" id="sex1" value="男" name="sex"<?php if ($master['sex'] == '男') { ?> checked<?php } ?>>男　　　
            <input type="radio" id="sex2" value="女" name="sex"<?php if ($master['sex'] == '女') { ?> checked<?php } ?>>女
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="dept">部门</label>
        <div class="controls">
            <input type="text" id="dept" name="dept" value="<?php echo $master['dept'] ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="position">职务</label>
        <div class="controls">
            <input type="text" id="position" name="position" value="<?php echo $master['position'] ?>">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input type="button" onclick="checkSub();" class="btn btn-info" value="提交" />
            <input type="reset" class="btn" value="重置" />
            <input type="button" class="btn" onclick="location.href='?r=master/index'" value="返回列表" />
        </div>
    </div>
</form>
<script>
    function checkSub() {
        var name = $("#name").val();
        var realname = $("#realname").val();
        var pwd = $("#pwd").val();
        var pwdag = $("#pwdag").val();
        var dept = $("#dept").val();
        var position = $("#position").val();

        if(name == "") {
            alert("姓名是必须的");
            return false;
        }
        if(pwd == "" || pwdag == "") {
            alert("密码是必须的");
            return false;
        }

        if(pwd != pwdag) {
            alert("两次密码不一致");
            return false;
        }

        if(dept == "" || position == "") {
            alert("部门和职务是必须的");
            return false;
        }
        $.ajax({
            type: "POST",
            url: "?r=master/update",
            dataType:"json",
            data: $("#masterForm").serialize(),
            success: function(json){
                if(json.status == 1) {
                    VPFbox.alert("修改成功", function(){location.href=location.href;});
                } else {
                    VPFbox.alert(json.msg);
                }
            }
        });
        //$("#masterForm").submit();
    }
</script>