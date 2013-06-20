<?
echo html::breadcumb(array(
    '角色管理'=>'role/index',
    '分配权限'=>'active'
));
?>
<p>
    <button id="expand" class="btn" type="button">全部收缩</button>
    <button id="collapse" class="btn" type="button">全部展开</button>
    <button id="checkAll" class="btn" type="button">全部选中</button>
    <button id="uncheckAll" class="btn" type="button">全部不选</button>
    <button id="init" class="btn" type="button">初始化</button>
</p>
<hr>
<form id="roleForm" class="form-horizontal" action="?r=role/changeAssign" method="post">
    <div class="control-group">
        <input type="hidden" name="roleId" value="<?=$roleId?>">
        <div class="select_area">
            <?php foreach ($allActions as $id => $action) { ?>            
            <div class="p">
                <div class="item_name"><i class="icon-chevron-up"></i> <?=$action['name']?></div>
                <div class="check">
                    <input name="actions[]" type="checkbox" value="<?=$id?>" <?=$action['mark']==1?"checked='checked' mark='1'":""?>/>
                </div>
            </div>
            <ul>
            <?
                foreach ($action['children'] as $children) {
            ?>
            <li class="c">
                <div class="item_name"><?=$children['name']?></div>
                <div class="check">
                    <input name="actions[]" type="checkbox" value="<?=$children['id']?>" <?=$children['mark']==1?"checked='checked' mark='1'":""?>/>
                </div>
            </li>
            <?
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>
    <div class="control-group">
        <button type="submit" class="btn btn-primary">确定</button>
        <?= Html::link('返回',array('role/index'),array('class'=>'btn'))?>
    </div>
</form>
<script type="text/javascript">
    var app = app || {
        init:function(){
            $(".select_area :checkbox").removeAttr('checked');
            $(".select_area :checkbox[mark='1']").attr('checked','checked');
        },
        bindEvent:function(){
            $('.icon-chevron-down').live('click',function(){
                $(this).attr('class','icon-chevron-up');
                $(this).parents('div.p').next('ul').slideToggle('fast');
            });
            $('.icon-chevron-up').live('click',function(){
                $(this).attr('class','icon-chevron-down');
                $(this).parents('div.p').next('ul').slideToggle('fast');
            });
            $(".p :checkbox").bind('change',function(){
                var e = $(this);
                if(e.attr('checked')=='checked'){
                    $(this).parents('div.p').next('ul').find(":checkbox").attr('checked','checked');
                }else{
                    $(this).parents('div.p').next('ul').find(":checkbox").removeAttr('checked');
                }
            });
            $(".c :checkbox").bind('change',function(){
                if($(this).attr('checked')!="checked"){
                    $(this).parents('ul').prev().find(':checkbox').removeAttr('checked');
                }
            });
        },
        expandAll:function(){
            $(".select_area ul").slideUp('fast');
        },
        collapseAll:function(){
            $(".select_area ul").slideDown('fast');
        },
        checkAll:function(){
            $(".select_area :checkbox").attr('checked','checked');
        },
        uncheckAll:function(){
            $(".select_area :checkbox").removeAttr('checked');
        }
    };
    $(document).ready(function(){
        app.bindEvent();
        $("#expand").bind('click',function(){
            app.expandAll();
        });        
        $("#collapse").bind('click',function(){
            app.collapseAll();
        });
        $("#checkAll").bind('click',function(){
            app.checkAll();
        });
        $("#uncheckAll").bind('click',function(){
            app.uncheckAll();
        });
        $("#init").bind('click',function(){
            app.init();
        });
    });
</script>