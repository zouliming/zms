<ul class="breadcrumb">
    <li><a href="?r=role/index">角色管理</a> <span class="divider">/</span></li>
    <li><a href="?r=role/index">角色列表</a> <span class="divider">/</span></li>
    <li class="active">分配权限</li>
</ul>
<script>
    var privList = <?php echo $actions; ?>;
    var selectedPriv = <?php echo $selected; ?>;
</script>
<form id="roleForm" class="form-horizontal" action="?r=role/priv" method="post">
    <input type="hidden" name="smt" value="1" />
    <input type="hidden" name="id" value="<?php echo $roleid; ?>" />
    <table id="multilist">
        <tr>
            <td class="list-left">
                <h2>后台权限列表</h2>
                <dl id="privlist" class="privlist mullist">
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
                <h2>已分配权限</h2>
                <dl id="selectedPriv" class="selectedPriv mullist">
                </dl>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center; padding-top:8px;">
                <div class="control-group">
                    <div class="">
                        <input type="button" onclick="setPriv();" class="btn btn-info" value="确认分配" />
                        <input type="button" class="btn" onclick="location.href='?r=role/index'" value="返回列表" />
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

        //初始化权限列表
        var privHtml = "", selectedHtml="";
        var dt = "<dt privid='<%=privid%>'><%=name%></dt>";
        var dd = "<dd privid='<%=privid%>' parent_id='<%=parent_id%>'><%=name%></dd>";
		
        for(var priv in privList){ 
            privHtml += renderTpl(dt, {
                privid: priv,
                name: privList[priv]['name']
            }); 
            for(var sub in privList[priv]['sublist']){ 
                privHtml += renderTpl(dd, {
                    privid: sub,
                    parent_id:priv,
                    name: privList[priv]['sublist'][sub]
                }); 
            }
        }
        for(var priv in selectedPriv){ 
            selectedHtml += renderTpl(dt, {
                privid: priv,
                name: selectedPriv[priv]['name']
            }); 
            for(var sub in selectedPriv[priv]['sublist']){ 
                selectedHtml += renderTpl(dd, {
                    privid: sub,
                    parent_id:priv,
                    name: selectedPriv[priv]['sublist'][sub]
                }); 
            }
        } 
        $("#privlist").html(privHtml);
        $("#selectedPriv").html(selectedHtml);
		
        //权限父集
        $(".mullist dt").live("click",function() {
            if($(this).hasClass("choosed")) {
                $(this).removeClass("choosed").siblings("dd[parent_id="+$(this).attr("privid")+"]").removeClass("choosed");
            } else {
                $(this).addClass("choosed").siblings("dd[parent_id="+$(this).attr("privid")+"]").addClass("choosed");
            }
        })
        //权限子集
        $(".mullist dd").live("click",function() {
            if($(this).hasClass("choosed")) {
                $(this).removeClass("choosed");
            } else {
                $(this).addClass("choosed");
            }

            //判断当前权限集合是否全部选中
            var siblings = $("mullist dd[parent_id="+$(this).attr("parent_id")+"]:not(.choosed)");
            var parentDT = $("mullist dt[privid="+$(this).attr("parent_id")+"]");
            if(siblings.size() > 0) {
                parentDT.removeClass("choosed");
            } else {
                parentDT.addClass("choosed");
            }
        });

        //选中
        $("#chooseBtn").click(function() {
            var choosedHtml = "";
            var choosed = $("#privlist .choosed");
            if(choosed.size() ==0 ) {
                alert("请先选择");
            } else {
                choosed.each(function(i) {
                    var tmp = {};
                    tmp.name = $(this).html();
                    tmp.privid = $(this).attr("privid");
                    if(typeof $(this).attr("parent_id") != "undefined") {
                        tmp.parent_id = $(this).attr("parent_id");
                        choosedHtml += renderTpl(dd,tmp); 
                    } else {
                        choosedHtml += renderTpl(dt,tmp); 
                    }
					
                });
                $("#selectedPriv").empty().html(choosedHtml);
            }
        });
        //删除
        $("#delBtn").click(function() {
            var choosed = $("#selectedPriv .choosed");
            if(choosed.size() ==0 ) {
                alert("请先选择");
            } else {
                choosed.remove();
            }
        })
    });

    //执行确认分配
    function setPriv() {
        //清空pirv
        $("#roleForm .privInput").remove();
        var choosed = $("#selectedPriv dd");
        if(choosed.length == 0) {
            VPFbox.alert("请至少选择一项权限");
            return false;
        }
		
        choosed.each(function(i) {
            $("#roleForm").append("<input class='privInput' type='hidden' name='priv[]' value='"+$(this).attr("privid") + ":" + $(this).html()+"' />");
        });
		

        //设置priv
        $.ajax({
            type: "POST",
            url: "?r=role/priv",
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