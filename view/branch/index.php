<?
echo Html::breadcumb(array(
        '分支管理' => 'Branch/index',
        '查看所有分支' => 'active',
));
?>
<?= Html::link('添加分支', array('branch/addBranch'), array('class' => 'btn btn-primary')) ?>
<hr>
<div class="data-list">
        <table id="table1" >
                <thead>
                        <tr>
                                <th>项目</th>
                                <th>分支</th>
                                <th>负责人</th>
                                <th>起始Trunk版本号</th>
                                <th>是否已经上线</th>
                                <th>备注</th>
                                <th>操作</th>
                        </tr> 
                </thead>
                <tbody>
                        <?php if (!empty($items)) { ?>
                                <?php foreach ($items as $item) { ?>
                                        <tr>
                                                <td><?php echo $item['project']; ?></td>
                                                <td><?php echo $item['branch']; ?></td>
                                                <td><?php echo $item['owner']; ?></td>
                                                <td><?php echo $item['from_trunk_version']; ?></td>
                                                <td><?php echo BranchModel::showIsOnline($item['is_online']); ?></td>
                                                <td><?php echo mb_substr($item['remark'], 0, 20, 'UTF-8'); ?></td>
                                                <td>
                                                        <?= Html::link('查看', array('Branch/view', 'id' => $item['id'])) ?>
                                                        <?= Html::link('修改', array('Branch/update', 'id' => $item['id'])) ?>
                                                        <?= Html::link('删除', array('Branch/delete', 'id' => $item['id']), array('onclick' => "javascript:return confirm('你确定要删除吗？')")) ?>
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
</div>
<? $this->beginScript(); ?>
<script type="text/javascript">
        $('#table1').tablecloth();
</script>
<? $this->endScript(); ?>