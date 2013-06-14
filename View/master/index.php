<?
echo Html::breadcumb(array(
    '用户管理'=>'master/index',
    '用户列表'=>'active',
));
echo Html::link('指定角色', array('master/add'),array('class'=>'btn btn-primary'));
?>
<hr>
<div class="data-list">
    <table id="table1" >
        <thead>
            <tr>
                <th>用户名</th>
                <th>真实姓名</th>
                <th>性别</th>
                <th>部门</th>
                <th>职务</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($userData)) { ?>
                <?php foreach ($userData as $item) { ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['realname']; ?></td>
                        <td><?php echo $item['sex']; ?></td>
                        <td><?php echo $item['dept']; ?></td>
                        <td><?php echo $item['position']; ?></td>
                        <td><?php echo date("Y-m-d H:i:s", $item['create_time']); ?></td>
                        <td>
                            <?= Html::link('指定角色', array('master/role','id'=>$item['id']))?>
                            <?= Html::link('修改', array('master/update','id'=>$item['id']))?>
                            <a href="javascript:void();" onclick="del(<?=$item['id']?>)">删除</a>
                        </td>
                    </tr>
                <?php
                }
            } else {
                ?>
                <tr align="center">
                    <td colspan="7" style="text-align:center;">暂无此类数据</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php echo $pageStr; ?>
</div>
<script type="text/javascript">
    function del(id) {
        if(confirm('确认删除此操作项吗？')){
            location.href = "index.php?r=master/del&id="+id;
        }
    }
    $('#table1').tablecloth();
</script>