<ul class="breadcrumb">
    <li><a href="?r=menu/index">菜单管理</a> <span class="divider">/</span></li>
    <li class="active">菜单列表</li>
</ul>
<?=html::link('添加菜单', 'menu/add',array('class'=>'btn btn-primary'))?>
<hr>
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
                            <?=Html::link('分配角色', array('menu/role','id'=>$item['info']['id']))?>
                            <?=Html::link('修改', array('menu/update','id'=>$item['info']['id']))?>
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
                                    <?=Html::link('分配角色', array('menu/role','id'=>$subitem['id']))?>
                                    <?=Html::link('修改', array('menu/update','id'=>$subitem['id']))?>
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
<script type="text/javascript">
    function del(id) {
        if(confirm('确认删除此操作项吗？')){
            window.location.href="index.php?r=menu/del&id="+id;
        }
    }
    //表格初始化
    $('#table1').tablecloth();
</script>