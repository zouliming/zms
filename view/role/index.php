<?
echo Html::breadcumb(array(
    '角色管理'=>'role/index',
    '角色列表'=>'active',
));
?>
<?= Html::link('添加角色', array('role/add'),array('class'=>'btn btn-primary'))?>
<hr>
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
                            <?= Html::link('分配权限', array('role/assign','id'=>$item['id']))?>
                            <?= Html::link('修改', array('role/update','id'=>$item['id']))?>
                            <?= Html::link('删除', array('role/delete','id'=>$item['id']),array('onclick'=>"javascript:return confirm('你确定要删除吗？')"))?>
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
</div>
<script type="text/javascript">
    $('#table1').tablecloth();
</script>