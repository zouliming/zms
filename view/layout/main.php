<!DOCTYPE html>
<html>
    <head>
        <title><?=@$title?></title> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="css/login.css"/>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/tablecloth.css">
        <link rel="stylesheet" type="text/css" href="css/application.css">
        <script type="text/javascript" src="<?= JS_URL ?>jquery.min.js"></script>
        <script type="text/javascript" src="<?= JS_URL ?>bootstrap.js"></script>
        <script type="text/javascript" src="<?= JS_URL ?>jquery.tablesorter.js"></script>
        <script type="text/javascript" src="<?= JS_URL ?>jquery.tablecloth.js"></script>
    </head>
    <body>
        <div class="container">
            <?
            echo $_layoutContent;
            ?>
        </div>
    </body>
</html>