<ul class="breadcrumb">
    <li><a href="<?=Html::url('gii/index')?>">代码生成器</a> <span class="divider">/</span></li>
    <li class="active">生成Model</li>
</ul>
<form class="form-horizontal" action="index.php?r=gii/generateModel" method="post">
    <div class="control-group">
        <label class="control-label" for="inputTable">表名</label>
        <div class="controls">
            <input name="inputTable" type="text" id="inputTable" placeholder="Table">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button class="btn" type="submit">提交</button>
        </div>
    </div>
</form>