<form id="menuForm" class="form-horizontal" action="" method="post">
        <div class="control-group">
                <label class="control-label" for="project">项目：</label>
                <div class="controls">
                        <?php echo Html::text('project', array('placeholder'=>'请输入项目名称','value'=>gv($model->project)))?>
                </div>
        </div>
        <div class="control-group">
                <label class="control-label" for="branch">分支：</label>
                <div class="controls">
                        <?php echo Html::text('branch', array('placeholder'=>'请输入分支名称','value'=>gv($model->branch)))?>
                </div>
        </div>
        <div class="control-group" id="urlBox" >
                <label class="control-label" for="owner">负责人：</label>
                <div class="controls">
                        <?php echo Html::text('owner', array('placeholder'=>'请输入负责人','value'=>gv($model->owner)))?>
                </div>
        </div>
        <div class="control-group" id="urlBox" >
                <label class="control-label" for="from_turnk_version">起始Trunk版本：</label>
                <div class="controls">
                        <?php echo Html::text('from_trunk_version', array('placeholder'=>'请输入起始Trunk版本','value'=>gv($model->from_trunk_version)))?>
                </div>
        </div>
        <div class="control-group" id="urlBox" >
                <label class="control-label" for="url">是否已经上线：</label>
                <div class="controls">
                        <?php echo  Html::dropdown(array('name'=>'is_online'), array(0=>'否','1'=>'是'),gv($model->is_online));?>
                </div>
        </div>
        <div class="control-group" id="urlBox" >
                <label class="control-label" for="url">备注：</label>
                <div class="controls">
                        <?php echo Html::textarea(array('name'=>'remark','value'=>gv($model->remark),'placeholder'=>'备注信息','rows'=>4,'class'=>'input-xlarge'))?>
                </div>
        </div>
        <div class="control-group">
                <div class="controls">
                        <input type="submit" id='subBtn' class="btn btn-primary" value="提交" />
                        <input type="reset" class="btn" value="重置" />
                        <?= Html::link('返回列表', 'branch/index', array('class' => 'btn')) ?>
                </div>
        </div>
</form>