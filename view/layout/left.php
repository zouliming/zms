<div class="well">
    <ul class="nav nav-list">
        <?php
        //获取菜单数据
        $menu = MenuController::getMenu();
        $url = $this->getget('r');
        foreach ($menu as $m) {
        ?>
            <li class="nav-header"><?=$m['info']['name']?></li>
            <? if (isset($m['sub'])) {
                foreach ($m['sub'] as $subitem) {
            ?>
            <li<?=$url==$subitem['url']?' class="active"':''?>><a href="?r=<?= $subitem['url'] ?>"><?=$subitem['name']?></a></li>
        <?php
                }
            }
        }
        ?>
        <li class="divider"></li>
        <li><a href="#">Help</a></li>
    </ul>
</div>
