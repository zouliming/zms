<?
echo Html::breadcumb(array(
    '权限项管理' => 'action/index',
    '权限列表' => 'active'
));
echo Html::link('添加权限项', 'action/add', array('class' => 'btn btn-primary'));
?>
<hr>
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
            <?
            if ($items) {
                foreach ($items as $item) {
                    $allItems = array_merge(array($item['info']),$item['sub']);
                    foreach($allItems as $k=>$v){
            ?>
            <tr>
                <td class="expSub" style="text-align:center;" rel="row_<?php echo $v['id']; ?>"><?=$k==0?'-':''?></td>
                <td><?php echo $v['name']; ?></td>
                <td><?php echo $v['info']; ?></td>
                <td><?php echo $v['update_master_name']; ?></td>
                <td><?php echo date("Y-m-d H:i:s", $v['update_time']); ?></td>
                <td>
                    <?= Html::link('修改', array('action/update', 'id' => $v['id'])) ?>
                    <a onclick="del('<?=$v['id']?>')" href="javascript:void(0)">删除</a>
                </td>
            </tr>
            <?
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
</div>
<? $this->beginScript(); ?>
<script type="text/javascript">
    function del(id) {
        if(confirm('确认删除此权限项吗？')){
            location.href = "index.php?r=action/delete&id="+id;
        }
    }
    $('#table1').tablecloth();
</script>
<? $this->endScript(); ?>