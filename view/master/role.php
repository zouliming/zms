<?
echo Html::breadcumb(array(
    '用户管理'=>'#',
    '用户列表'=>'master/index',
    '指定角色'=>'active',
));
?>
<style type="text/css">
    select.lang_select{
        height: 300px;float:left;
    }
    .lang_select option{
        padding: 5px 13px;
    }
    .seperate{
        float: left;
        width: 100px;
        height: 300px;
        display: inline-block;
        display:table;
        text-align:center;
    }
</style>
<form id="masterForm" class="form-horizontal" action="?r=master/changeRole" method="post" onsubmit="return checkForm();">
    <div class="control-group">
        <input type="hidden" name="masterId" value="<?=$masterId?>">
        <input type="hidden" id="newRole" name="newRole"/>
        <select id="srcRole" class="lang_select" multiple="multiple">
            <?php foreach ($srcRoles as $id=> $role) { ?>
                <option value="<?=$id?>"><?=$role?></option>
            <?php } ?>
        </select>
        <div class="seperate">
            <div style="display:table-cell;vertical-align: middle;">
                <input class="btn" id="addBtn" type="button" value="&gt;&gt;"/>
                <br><br>
                <input class="btn" id="delBtn" type="button" value="&lt;&lt;"/>
            </div>
        </div>
        <select id="targetRole" class="lang_select" multiple="multiple">
            <?php foreach ($masterRoles as $id=>$role) { ?>
                <option value="<?=$id?>"><?=$role?></option>
            <?php } ?>
        </select>
    </div>
    <div class="control-group">
        <button type="submit" class="btn btn-primary">确定</button>
        <?= Html::link('返回',array('master/index'),array('class'=>'btn'))?>
    </div>
</form>
<script type="text/javascript">
    function checkForm(){
        var r="";
        $.each($('#targetRole option'), function(i,n){
            r += $(n).val()+',';
        });
        $('#newRole').val(r);
        return true;
    }
    $(document).ready(function(){
        $('#addBtn').click(function(){
            $("#targetRole").append($("#srcRole option:selected").removeAttr('selected'));
        });
        $('#delBtn').click(function(){
            $("#srcRole").append($("#targetRole option:selected").removeAttr('selected'));
        });
    });
</script>