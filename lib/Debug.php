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
        #debug_html tr{
            height: 25px;
        }
        .debug_head{
            font-size: 20px;
            margin-right: 10px;
            font-family: 微软雅黑,黑体;
            margin-top: 20px;
            margin-bottom: 8px;
        }
        .debug_title{
            color:#339966;
            background:#F7F7F7;
        }
        .debug_value{
            color:red;
        }
    </style>
    <hr>
    
    <div id="debug_html">
        <div class="debug_head">页面执行 调试信息</div>
        <table border=0 width=100%>
            <tr align="left" bgcolor=#FFFFFF>
                <td width="220px">
                    <span class="debug_title">执行时间：</span><span class="debug_value"><?php echo $runTime; ?>(s)</span>
                </td>
                <td>
                    <span class="debug_title">内存消耗：</span><span class="debug_value"><?php echo $runMem; ?></span>
                </td>
            </tr>
        </table>

    <?php if (!empty($dbBebug)) { ?>
        <div class="debug_head">数据库 SQL 调试信息</div>
        <table border=0 width=100%>
            <?php
            foreach ($dbBebug as $k => $v) {
                $totalTime += $v['time'];
                ?>
                <tr align="left" bgcolor=#FFFFFF>
                    <td width="220px;">
                        <span class="debug_title">执行时间：</span><span class="debug_value"><?php echo round($v['time'], 8); ?>(s)</span>
                    </td>
                    <td width="220px;">
                        <span class="debug_title">累计时间：</span><span class="debug_value"><?php echo round($totalTime, 8); ?>(s)</span>
                    </td>
                    <td>
                        <span class="debug_title">SQL：</span><span style="color:blue;"><?php echo $v['sql']; ?>;</span>
                    </td>
                </tr>
                <?php
            }
            ?>

        </table>
    <?php } ?>
        <div class="debug_head">页面流 调试信息</div>
        <table border=0 width=100%>
            <?php
            if ($flowBebug['view']) {
                ?>
                <?php
                foreach ($flowBebug['view'] as $k => $v) {
                    $mem = $v['mem'] < 0 ? 0 : round(($v['mem'] / 1024), 3);
                    ?>
                    <tr align="left" bgcolor=#FFFFFF>
                        <td width="220px;">
                            <span class="debug_title">执行时间：</span><span class="debug_value"><?php echo round($v['time'], 8); ?>(s)</span>
                        </td>
                        <td width="220px;">
                            <span class="debug_title">消耗内存：</span><span class="debug_value"><?php echo $mem; ?>k</span>
                        </td>
                        <td>
                            <span class="debug_title">TPL：</span><span style="color:blue;border-bottom:1px solid #DADADA;"><?php echo $v['txt']; ?>.php</span>
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

        <div class="debug_head">视图变量</div>
        <table border=0 width=100%>
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
        
        <div class="debug_head">Debug 调试信息</div>
        <table border=0 width=100%>
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
    </div>
</section>