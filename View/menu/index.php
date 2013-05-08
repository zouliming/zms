<ul class="breadcrumb">
    <li><a href="?r=menu/index">菜单管理</a> <span class="divider">/</span></li>
    <li class="active">菜单列表</li>
</ul>
<div class="main_addbtn">
    <dl>
        <a class="all_btn" href="javascript:;" onclick="showAdd();">
            添加菜单 
        </a>
    </dl>
</div>
<div class="data-list">
    <table id="table1" >
        <thead>
            <tr>
                <th></th>
                <th>菜单名</th>
                <th>菜单链接</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody>
            <?php if (!empty($items)) { ?>
                <?php foreach ($items as $item) { ?>
                    <tr>
                        <td class="expSub exp" style="text-align:center;" rel="row_<?= @$item['info']['id']; ?>"><?php if (isset($item['sub'])) { ?>-<?php } ?></td>
                        <td><?= @$item['info']['name']; ?></td>
                        <td><?= @$item['info']['url']; ?></td>
                        <td>
                            <a href="javascript:void(0);" onclick="role('<?= $item['info']['id']; ?>')">分配角色</a>
                            <a href="javascript:void(0);" onclick="edit('<?= $item['info']['id']; ?>')">修改</a>
                            <a href="javascript:void(0);" onclick="del('<?= $item['info']['id']; ?>')">删除</a>
                        </td>
                    </tr>
                    <?php
                    if (isset($item['sub'])) {
                        foreach ($item['sub'] as $subitem) {
                            ?>
                            <tr class="subtable" style="display:table-row;" rel="row_<?= @$item['info']['id']; ?>">
                                <td style="text-align:right;"> ></td>
                                <td>　<?php echo $subitem['name']; ?></td>
                                <td><?php echo $subitem['url']; ?></td>
                                <td>
                                    <a href="javascript:void(0);" onclick="role('<?php echo $subitem['id']; ?>')">分配角色</a>
                                    <a href="javascript:void(0);" onclick="edit('<?php echo $subitem['id']; ?>')">修改</a>
                                    <a href="javascript:void(0);" onclick="del('<?php echo $subitem['id']; ?>')">删除</a>
                                </td>
                            </tr>

                            <?php
                        }
                    }
                }
            } else {
                ?>
                <tr align="center">
                    <td colspan="4" style="text-align:center;">暂无此类数据</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    function role(id) {
        location.href = "?r=menu/role&id=" + id;
    }
    /**
     * 弹出添加框
     */
    function showAdd() {
        //VPFbox.iframe('?r=menu/add', "添加菜单项", 570, 270);
        location.href = "?r=menu/add";
    }
    /**
     * 弹出修改框
     * @param id menuid
     */
    function edit(id) {
        //VPFbox.iframe('?r=menu/update&id='+id, "修改菜单项",570, 270);
        location.href = "?r=menu/update&id="+id;
    }
    /**
     * 删除
     * @param id menuid
     */
    function del(id) {
        VPFbox.confirm("确认删除此操作项吗？", function() {
            $.getJSON('?r=menu/del',{id:id}, 
            function(json){
                if(json.status==1){
                    VPFbox.alert("操作成功", function() {
                        location.reload();
                    });
                } else {
                    VPFbox.alert(json.msg);
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