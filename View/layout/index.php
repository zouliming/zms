<!DOCTYPE html>
<html>
    <head>
        <title>唯品会供应商平台</title> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="css/login.css"/>
        <link rel="stylesheet" type="text/css" href="css/style.css?t=1364899264">
        <script type="text/javascript" src="<?= JS_URL ?>jquery.min.js"></script>
        <script type="text/javascript" src="<?= JS_URL ?>box.js"></script>
        <script type="text/javascript">
            var resetIframeTime = 0;
            function reSetIframe(flag){
                var iframe = document.getElementById("main");
                try{
                    if(flag=='false'){
                        if(this.resetIframeTime>5){
                            window.clearInterval(this.thread_frame);
                        }else{
                            this.resetIframeTime++;
                        }
                    }
                    
                    var bHeight = iframe.contentWindow.document.body.scrollHeight;
                    var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
                    var height = Math.max(bHeight, dHeight);

                    var bodyWidth = document.body.clientWidth;
                    var leftWidth = $('#left').width();
                    var width = bodyWidth-leftWidth;
                    iframe.height = height;
                    iframe.width = width;
                }catch (ex){}
            }
            //唯品会首页层显示
            $("#show_home_link").die("mouseover").live("mouseover",function(){
                $(this).children("div").show();
            });
            $("#show_home_link").die("mouseout").live("mouseout",function(){
                $(this).children("div").hide();
            });
        </script>
    </head>
    <body onResize="reSetIframe('ture')">
        <? require 'head.php';?>
        <? require 'left.php';?>
        <div class="frame_main" style="float:left;">
            <iframe id="main" name="main" width="800" height="600" frameborder="0" src="<?=APP_URL.'?r=welcome/main'?>"></iframe>
            <div style="clear:both"></div>
        </div>
        <script type="text/javascript">
            var thread_frame = window.setInterval("reSetIframe('false')", 200);
        </script>
    </body>
</html>