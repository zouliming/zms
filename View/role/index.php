<ul class="breadcrumb">
    <li><a href="?r=role/index">角色管理</a> <span class="divider">/</span></li>
    <li class="active">角色列表</li>
</ul>
<div class="main_addbtn">
    <dl>
        <a class="all_btn" href="javascript:;" onclick="showAdd();">
            添加角色 
        </a>
    </dl>
</div>
<div class="data-list">
    <table id="table1" >
        <thead>
            <tr>
                <th>角色名</th>
                <th>角色描述</th>
                <th>上次修改人</th>
                <th>上次修改时间</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody>
            <?php if (!empty($items)) { ?>
                <?php foreach ($items as $item) { ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['info']; ?></td>
                        <td><?php echo $item['update_master_name']; ?></td>
                        <td><?php echo date("Y-m-d H:i:s", $item['update_time']); ?></td>
                        <td>
                            <a href="javascript:void(0);" onclick="priv('<?php echo $item['id']; ?>')">分配权限</a>
                            <a href="javascript:void(0);" onclick="edit('<?php echo $item['id']; ?>')">修改</a>
                            <a href="javascript:void(0);" onclick="del('<?php echo $item['id']; ?>')">删除</a>
                        </td>
                    </tr>
                <?php
                }
            } else {
                ?>
                <tr align="center">
                    <td colspan="5" style="text-align:center;">暂无此类数据</td>
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
        //VPFbox.iframe('?r=role/add', "添加角色", 260, 270);
        location.href = "?r=role/add";
    }
    /**
     * 分配权限
     * @param id roleid
     */
    function priv(id) {
        //VPFbox.iframe('?r=role/priv&id='+id, "给角色分配权限", 445, 330);
        location.href = '?r=role/priv&id='+id;
    }
    /**
     * 弹出修改框
     * @param id roleid
     */
    function edit(id) {
        //VPFbox.iframe('?r=role/update&id='+id, "修改角色", 260, 270);
        location.href = "?r=role/update&id="+id;
    }
    /**
     * 删除
     * @param id roleid
     */
    function del(id) {
        VPFbox.confirm("确认删除此操作项吗？", function() {
            $.getJSON('?r=role/del',{id:id}, 
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

    //表格初始化
    $('#table1').tablecloth();
</script>