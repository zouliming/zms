<ul class="breadcrumb">
    <li><a href="?r=menu/index">菜单管理</a> <span class="divider">/</span></li>
    <li><a href="?r=menu/index">菜单列表</a> <span class="divider">/</span></li>
    <li class="active">分配菜单</li>
</ul>
<form id="roleForm" class="form-horizontal" action="?r=role/priv" method="post">
    <input type="hidden" name="smt" value="1" />
    <input type="hidden" name="id" value="<?php echo $menuid; ?>" />
    <table id="multilist">
        <tr>
            <td class="list-left">
                <h2>角色列表</h2>
                <dl id="privlist" class="privlist mullist">
                    <?php
                    if (!empty($roles)) {
                        foreach ($roles as $k => $role) {
                            ?>
                            <dd roleid="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></dd>
                            <?php
                        }
                    }
                    ?>
                </dl>
            </td>
            <td class="list-btns">
                <div class="listBtn">
                    <input type="button" id="chooseBtn" value="分配&gt;&gt;" class="btn btn-info">
                    <br><br>
                    <input type="button" id="delBtn" value="&lt;&lt;删除" class="btn btn-info">
                </div>
            </td>
            <td class="list-right">
                <h2>已分配角色</h2>
                <dl id="selectedPriv" class="selectedPriv mullist">
                    <?php
                    if (!empty($sroles)) {
                        foreach ($sroles as $k => $role) {
                            ?>
                            <dd roleid="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></dd>
                            <?php
                        }
                    }
                    ?>
                </dl>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center; padding-top:8px;">
                <div class="control-group">
                    <div class="">
                        <input type="button" onclick="setRole();" class="btn btn-info" value="确认分配" />
                        <input type="button" class="btn" onclick="location.href='?r=menu/index'" value="返回列表" />
                    </div>
                </div>
            </td>
        </tr>
    </table>
</form>
<script>
    /**
     * 替换短句模板
     */
    function renderTpl(tpl, op, escapeHTML) {
        return tpl.replace(/<%\=(\w+)%>/g, function(e1, e2) {
            return op[e2] != null ? op[e2] : "";
        });
    }

    $(function() {
        $(".mullist dd").live("click",function() {
            if($(this).hasClass("choosed")) {
                $(this).removeClass("choosed");
            } else {
                $(this).addClass("choosed");
            }
        });
        //选中
        $("#chooseBtn").click(function() {
            var choosed = $("#privlist .choosed");
            if(choosed.size() ==0 ) {
                alert("请先选择");
            } else {
                choosed.each(function(i) {
                    $(this).appendTo("#selectedPriv");
                });
            }
        });
        //删除
        $("#delBtn").click(function() {
            var choosed = $("#selectedPriv .choosed");
            if(choosed.size() ==0 ) {
                alert("请先选择");
            } else {
                choosed.each(function(i) {
                    $(this).appendTo("#privlist");
                });
            }
        })
    });

    //执行确认分配
    function setRole() {
        //清空pirv
        $("#roleForm .privInput").remove();
        var choosed = $("#selectedPriv dd");
        if(choosed.length == 0) {
            VPFbox.alert("请至少选择一项角色");
            return false;
        }
		
        choosed.each(function(i) {
            $("#roleForm").append("<input class='privInput' type='hidden' name='role[]' value='"+$(this).attr("roleid")+"' />");
        });
		

        //设置role
        $.ajax({
            type: "POST",
            url: "?r=menu/role",
            dataType:"json",
            data:$("#roleForm").serialize(),
            success: function(json){
                if(json.status == 1) {
                    VPFbox.alert("添加成功", function(){location.href=location.href;});
                } else {
                    VPFbox.alert(json.msg);
                }
            }
        });
    }
</script>