<?php
// 赋值
$runTime = @round(microtime_float() - microtime_float(BEE_BEGIN_TIME), 8);
$runMem = byteConvert(memory_get_usage() - BEE_START_MEMS);
$dbBebug = Bee::get('debug', 'db');
$flowBebug = Bee::get('debug', 'flow');
$tplData = Bee::get('debug', 'tplData');
$debug = Bee::get('debug');
?>
<section>
    <style type="text/css">
        #debug_html{
            height: auto;
            width: 100%;
            margin: 10px auto;
            background-color: #fff;
            padding: 10px;
        }
        .debug_head{
            color:#777;
            background-color:#FFFFDD;
            padding:10px 10px 10px 36px;
            border:0.1em solid #CC6633;
        }
        .debug_title{
            color:#339966;
            border-bottom:1px solid #DADADA;
            background:#F7F7F7;
        }
        .debug_value{
            color:red;
            border-bottom:1px solid #DADADA;
        }
    </style>
    <hr>
    <div id="debug_html">
        <table border=0 width=100%>
            <tr><th class="debug_head">页面执行 调试信息</th></tr>
            <tr align="left" bgcolor=#FFFFFF>
                <td>
                    <span class="debug_title">执行时间：</span><span class="debug_value"><?php echo $runTime; ?>(s)</span>
                    <span class="debug_title">内存消耗：</span><span class="debug_value"><?php echo $runMem; ?></span>
                </td>
            </tr>
        </table>

    <?php if (!empty($dbBebug)) { ?>
        <table border=0 width=100%>
            <tr><th  class="debug_head">数据库 SQL 调试信息</th></tr>

            <?php
            foreach ($dbBebug as $k => $v) {
                $totalTime += $v['time'];
                ?>
                <tr align="left" bgcolor=#FFFFFF>
                    <td>
                        <span class="debug_title">执行时间：</span><span class="debug_value"><?php echo round($v['time'], 8); ?>(s)</span>
                        <span class="debug_title">累计时间：</span><span class="debug_value"><?php echo round($totalTime, 8); ?>(s)</span>
                        <span class="debug_title">SQL：</span><span style="color:blue;border-bottom:1px solid #DADADA;"><?php echo $v['sql']; ?>;</span>
                    </td>
                </tr>
                <?php
            }
            ?>

        </table>
    <?php } ?>

        <table border=0 width=100%>
            <tr><th colspan=2  class="debug_head">页面流 调试信息</th></tr>

            <tr bgcolor=#cccccc><td colspan=2><b>common Data：</b></td></tr>
            <?php
            if ($flowBebug['common']) {
                ?>
                <?php
                foreach ($flowBebug['common'] as $k => $v) {
                    $mem = $v['mem'] < 0 ? 0 : round(($v['mem'] / 1024), 3);
                    ?>
                    <tr align="left" bgcolor=#FFFFFF>
                        <td>
                            <span class="debug_title">执行时间：</span><span class="debug_value"><?php echo round($v['time'], 8); ?>(s)</span>
                            <span class="debug_title">消耗内存：</span><span class="debug_value"><?php echo $mem; ?>k</span>
                            <span class="debug_title">common：</span><span style="color:blue;border-bottom:1px solid #DADADA;"><?php echo $v['txt']; ?></span>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <?php
            } else {
                ?>
                <tr bgcolor=#eeeeee><td colspan=2><tt><i>no debug data</i></tt></td></tr>
                <?php
            }
            ?>

            <tr bgcolor=#cccccc><td colspan=2><b>view Data：</b></td></tr>
            <?php
            if ($flowBebug['view']) {
                ?>
                <?php
                foreach ($flowBebug['view'] as $k => $v) {
                    $mem = $v['mem'] < 0 ? 0 : round(($v['mem'] / 1024), 3);
                    ?>
                    <tr align="left" bgcolor=#FFFFFF>
                        <td>
                            <span class="debug_title">执行时间：</span><span class="debug_value"><?php echo round($v['time'], 8); ?>(s)</span>
                            <span class="debug_title">消耗内存：</span><span class="debug_value"><?php echo $mem; ?>k</span>
                            <span class="debug_title">tpl：</span><span style="color:blue;border-bottom:1px solid #DADADA;"><?php echo $v['txt']; ?>.php</span>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <?php
            } else {
                ?>
                <tr bgcolor=#eeeeee><td colspan=2><tt><i>no debug data</i></tt></td></tr>
                <?php
            }
            ?>
        </table>

        <table border=0 width=100%>
            <tr><th colspan=2  class="debug_head">Debug 调试信息</th></tr>
            <tr bgcolor=#cccccc><td colspan=2><b>Debug Data：</b></td></tr>
            <?php
            if ($debug) {
            ?>
                <tr align="left" bgcolor=#eeeeee><td colspan=2 ><?php dump($debug); ?></td></tr>
            <?php
            } else {
            ?>
                <tr bgcolor=#eeeeee><td colspan=2><tt><i>no debug data</i></tt></td></tr>
            <?php
            }
            ?>
        </table>

        <table border=0 width=100%>
            <tr bgcolor=#cccccc><th colspan=2  class="debug_head">视图 调试信息</th></tr>
            <tr bgcolor=#cccccc><td colspan=2><b>Templates Data：</b></td></tr>
            <?php
            if ($tplData) {
            ?>
                <tr align="left" bgcolor=#eeeeee><td colspan=2 ><?php dump($tplData); ?></td></tr>
            <?php
            } else {
            ?>
                <tr bgcolor=#eeeeee><td colspan=2><tt><i>no templates included</i></tt></td></tr>
            <?php
            }
            ?>
        </table>
    </div>
</section>