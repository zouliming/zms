<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <a class="brand" href="#"><?=Bee::app()->getConfig('project','name')?></a>
        <ul class="nav">
            <li class="active"><a href="#">首页</a></li>
            <li><a style="color:#ffffff;text-decoration: none;" target="main" href="?r=master/changepwd">修改密码</a></li>
            <li><a href="javascript:if(confirm('确定退出吗?')){window.location.href = '?r=welcome/logout';}" style="color:#ffffff;text-decoration:none;">退出</a></li>
        </ul>
    </div>
</div>