<!DOCTYPE html>
<html>
    <head>
        <title><?=$this->pageTitle?></title> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="<?= CSS_URL ?>bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="<?= CSS_URL ?>doc.css"/>
    </head>
    <body>
        <? require 'head.php';?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2">
                    <!--Sidebar content-->
                    <? require 'left.php';?>
                </div>
                <div class="span10">
                    <!--Body content-->
                    <?
                    echo $_layoutContent;
                    ?>
                </div>
            </div>
        </div>
        
        <script type="text/javascript" src="<?= JS_URL ?>jquery.min.js"></script>
        <script type="text/javascript" src="<?= JS_URL ?>bootstrap.js"></script>
        <script type="text/javascript" src="<?= JS_URL ?>jquery.tablesorter.js"></script>
        <script type="text/javascript" src="<?= JS_URL ?>jquery.tablecloth.js"></script>
        <script type="text/javascript" src="<?= JS_URL ?>application.js"></script>
        <script type="text/javascript" src="<?= JS_URL ?>run.js"></script>
        <?
        echo $_scriptContent;
        ?>
    </body>
</html>