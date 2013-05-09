<!DOCTYPE html>
<html>
    <head>
        <title>登录   - 我的后台</title> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="<?=CSS_URL?>bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="<?=CSS_URL?>login.css"/>
    </head>
    <body>
        <div class="login_box light round tran">
            <div class="til">
                <p class="til_china">管理后台Demo</p>
                <p class="til_en">By zouliming</p>
            </div>
            <div class="inf">
                <form id="myForm" method="post" action="index.php?r=welcome/login">
                    <ul>
                        <li>
                            <dl class="ul_title">商家ID：</dl>
                            <dl class="ul_input">
                                <input id="merchId" class="" name="merchId" type="text" placeholder="请输入商家ID" m="请输入商家ID"/>
                            </dl>
                        </li>
                        <li>
                            <dl class="ul_title">用户名：</dl>
                            <dl class="ul_input">
                                <input class="" name="userName" id="userName" placeholder="请输入用户名" type="text" m="请输入用户名"/>
                            </dl>
                        </li>
                        <li>
                            <dl class="ul_title">密　码：</dl>
                            <dl class="ul_input">
                                <input class="" id="password" name="password" placeholder="请输入密码" type="password" m="请输入密码"/>
                            </dl>
                        </li>
                        <li>
                            <dl class="ul_title">验证码：</dl>
                            <dl class="ul_input">
                                <input class="inf_veri" name="checkWord" id="checkWord" type="text" placeholder="请输入验证码" m="请输入验证码"/>
                            </dl>
                            <dl class="ul_img"  id="checkCode"><img src="index.php?r=site/img"/></dl>
                        </li>
                        <li style="height:40px;">
                            <dl class="ul_title"></dl>
                            <dl class="ul_input" style="height:40px;"><a class="login_bnt" id="subMit" href="#">登录</a></dl>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        
        <script type="text/javascript" src="<?=JS_URL?>jquery.min.js"></script>
        <script type="text/javascript" src="<?=JS_URL?>validator.js"></script>
        <script type="text/javascript">
            $("#checkCode").click(function(){
                $("#checkCode").html('<img src="index.php?r=site/img&t='+Math.random()+'"/>');
            });
            $('#subMit').click(function(){
                $("#myForm").va({
                   'required':{
                       'ele':'#userName,#password,#checkWord',
                       'errorAttr':'placeholder'
                   }
                });
            });
            document.onkeydown = function(e){
                e = e||event;
                if(e.keyCode=="13"){
                    $('#myForm').submit();
                }
            }
        </script>
    </body>
</html>