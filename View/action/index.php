<ul class="breadcrumb">
    <li><a href="?r=action/index">操作权限管理</a> <span class="divider">/</span></li>
    <li class="active">操作权限列表</li>
</ul>
<div class="main_addbtn">
    <dl>
        <a class="all_btn" href="javascript:;" onclick="showAdd();">
            添加权限项 
        </a>
    </dl>
</div>
<div class="data-list">
    <table id="table1" >
        <thead>
            <tr>
                <th></th>
                <th>权限名</th>
                <th>权限描述</th>
                <th>上次修改人</th>
                <th>上次修改时间</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody>
            <?php if (!empty($items)) { ?>
                <?php foreach ($items as $item) { ?>
                    <tr>
                        <td class="expSub" style="text-align:center;" rel="row_<?php echo $item['info']['id']; ?>"><?php if (isset($item['sub'])) { ?>+<?php } ?></td>
                        <td><?php echo $item['info']['name']; ?></td>
                        <td><?php echo $item['info']['info']; ?></td>
                        <td><?php echo $item['info']['update_master_name']; ?></td>
                        <td><?php echo date("Y-m-d H:i:s", $item['info']['update_time']); ?></td>
                        <td>
                            <a href="javascript:void(0);" onclick="edit('<?php echo $item['info']['id']; ?>')">修改</a>
                        </td>
                    </tr>
                    <?php
                    if (isset($item['sub'])) {
                        foreach ($item['sub'] as $subitem) {
                            ?>
                            <tr class="subtable" rel="row_<?php echo $item['info']['id']; ?>">
                                <td style="text-align:right;"> ></td>
                                <td>　<?php echo $subitem['name']; ?></td>
                                <td><?php echo $subitem['info']; ?></td>
                                <td><?php echo $subitem['update_master_name']; ?></td>
                                <td><?php echo date("Y-m-d H:i:s", $subitem['update_time']); ?></td>
                                <td>
                                    <a href="javascript:void(0);" onclick="edit('<?php echo $subitem['id']; ?>')">修改</a>
                                </td>
                            </tr>

                            <?php
                        }
                    }
                }
            } else {
                ?>
                <tr align="center">
                    <td colspan="6" style="text-align:center;">暂无此类数据</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php echo $pageStr; ?>
</div>
<script>
    /**
     * 弹出添加框
     */
    function showAdd() {
        //VPFbox.iframe('?r=action/add', "添加权限项", 570, 270);
        location.href = "?r=action/add";
    }
    /**
     * 弹出修改框
     * @param id actionid
     */
    function edit(id) {
        //VPFbox.iframe('?r=action/update&id='+id, "修改权限项",570, 270);
        location.href = "?r=action/update&id="+id;
    }
    /**
     * 删除
     * @param id actionid
     */
    function del(id) {
        VPFbox.confirm("确认删除此操作项吗？", function() {
            $.getJSON('?r=action/del',{id:id}, 
            function(json){
                if(json.status==1){
                    VPFbox.alert("操作成功", function() {
                        location.reload();
                    });
                } else {
                    alert(json.msg);
                }
            }
        );	
        }, function() {
            return false;
        });
    }
    /**
     * 弹框关闭时自动刷新页面
     */
    function setRefeshFlag() {
        $(".dui-dialog-close").click(function() {
            location.href = location.href;
        });
    }

    $(function() {
        $(".expSub").click(function() {
            if($(this).hasClass("exp")) {
                $(this).html("+").removeClass("exp");
            } else {
                $(this).html("-").addClass("exp");
            }
            $(this).parent().siblings(".subtable[rel="+$(this).attr("rel")+"]").toggle();
        })
    });

    //表格初始化
    $('#table1').tablecloth();
</script>