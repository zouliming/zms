<ul class="breadcrumb">
    <li><a href="?r=master/index">用户管理</a> <span class="divider">/</span></li>
    <li><a href="?r=master/index">用户列表</a> <span class="divider">/</span></li>
    <li class="active">添加用户</li>
</ul>
<form id="masterForm" class="form-horizontal" action="?r=master/add" method="post">
    <input type="hidden" name="smt" value="1" />
    <div class="control-group">
        <label class="control-label" for="name">用户名</label>
        <div class="controls">
            <input type="text" id="name" name="name">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="realname">真实姓名</label>
        <div class="controls">
            <input type="text" id="realname" name="realname">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="pwd">密码</label>
        <div class="controls">
            <input type="password" id="pwd" name="pwd">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="pwdag">确认密码</label>
        <div class="controls">
            <input type="password" id="pwdag" name="pwdag">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="sex">性别</label>
        <div class="controls">
            <input type="radio" id="sex1" value="男" name="sex" checked>男　　　
            <input type="radio" id="sex2" value="女" name="sex">女
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="dept">部门</label>
        <div class="controls">
            <input type="text" id="dept" name="dept">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="position">职务</label>
        <div class="controls">
            <input type="text" id="position" name="position">
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
            VPFbox.alert("姓名是必须的");
            return false;
        }
        if(pwd == "" || pwdag == "") {
            VPFbox.alert("密码是必须的");
            return false;
        }

        if(pwd != pwdag) {
            VPFbox.alert("两次密码不一致");
            return false;
        }

        if(dept == "" || position == "") {
            VPFbox.alert("部门和职务是必须的");
            return false;
        }

        $.ajax({
            type: "POST",
            url: "?r=master/add",
            dataType:"json",
            data: $("#masterForm").serialize(),
            success: function(json){
                if(json.status == 1) {
                    VPFbox.alert("添加成功", function(){location.href=location.href;});
                } else {
                    VPFbox.alert(json.msg);
                }
            }
        });

        //$("#masterForm").submit();
    }
</script>