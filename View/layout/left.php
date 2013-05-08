<div id="left" class="main_menu">
<?php 
	//获取菜单数据
	$menu = MenuController::getMenu();
	foreach($menu as $m) {
?>
<h1><?=$m['info']['name'];?></h1>
<? if(isset($m['sub'])){ ?>
	<div class="menu_list">
		<ul id="my_menu" class="sdmenu">
            <?php
            	foreach($m['sub'] as $subitem) {
            ?>
            <li>
                <a href="?r=<?=$subitem['url']?>" class="test" target="main">
                    <?=$subitem['name']?>
                </a>
            </li>
            <?php 
                }
            ?>
        </ul>
    </div>
    <?php
        }
    }
    ?>
</div>
<script type="text/javascript">
	$('#left h1').bind('click',function(){
        $(this).next('div').slideToggle('fast');
    });
</script>