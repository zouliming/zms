<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>CDN全天的秒级4XX，5XX百分比报表</title>
        <style type="text/css">
            .red{
                color:red;
            }
        </style>
        <link rel="stylesheet" href="<?=CSS_URL?>jquery-ui.css" />
		<script type="text/javascript" src="<?=JS_URL?>jquery.min.js"></script>
        <script src="<?=JS_URL?>jquery-ui.js"></script>
        <script src="<?=JS_URL?>highcharts.js"></script>
		<script type="text/javascript">
            var chart;
            var options = {
                chart: {
                    type:'area',
                    renderTo: 'container',
                    zoomType: 'x',
                    spacingRight: 20
                },
                title: {
                    text: 'CDN全天的秒级4XX，5XX百分比报表'
                },
                subtitle: {
                    text: document.ontouchstart === undefined ?
                        '点击并且拖动绘图可以放大' :
                        '移动你的手指可以放大绘图'
                },
                xAxis: {
                    type: 'datetime',
                    dateTimeLabelFormats: { // don't display the dummy year
                        hour: '%H:%M'
                    },
                    title: {
                        text: null
                    }
                },
                yAxis: {
                    title: {
                        text: '时间'
                    },
                    showFirstLabel: false
                },
                tooltip: {
                    formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%H:%M', this.x) +': <b>'+ this.y +'</b> 毫秒';
                    }
                },
                legend: {
                    align: 'left',
                    verticalAlign: 'top',
                    y: 20,
                    floating: true,
                    borderWidth: 0
                },
                plotOptions: {
                    area: {
                        lineColor: '#666666',
                        lineWidth: 1,
                        marker: {
                            enabled:false,
                            lineWidth: 1,
                            lineColor: '#666666'
                        }
                    }
                },
                series:[]
            };
            function getData(){
                $("#btn_data").val("等待中...").attr("disabled","disabled");
                var supplier = $("#supplier").val();
                var dataType = $("#dataType").val();
                var date = $("#datepicker").val();
                var param = {supplier:supplier,dataType:dataType,date:date};
                $.get('index.php?r=chart/ajaxGetCdnRate',param,function(data){
                    options.series = [];
                    for(i=0;i<data.length;i++){
                        var v = data[i];
                        options.series.push(v);
                    }
                    chart = new Highcharts.Chart(options);
                    $("#btn_data").val("开始获取数据").removeAttr("disabled");
                },'json');
            }
            function getNowDate(){
                var s="",d = new Date();
                s += (1900+d.getYear()) + "-";
                var m = (d.getMonth()+1);
                s += m<10 ? "0"+m : m;
                s += "-";
                var day = d.getDate()-1;
                s += day<10 ? "0"+day:day;
                return s;
            }
            $(function() {
                var pickerOpts = {
                    dateFormat: "yy-mm-dd",
                    maxDate: -1
                };
                $("#datepicker").datepicker(pickerOpts);
                s = getNowDate();
                $("#datepicker").val(s);
            });
    </script>
	</head>
	<body>
    <div>
        <select name="supplier" id="supplier">
            <?=Html::html_options($supplier)?>
        </select>
		<select name="dataType" id="dataType">
            <?=Html::html_options($dataType)?>
        </select>
		<input type="text" id="datepicker" />
        <input id="btn_data" type="button" onclick="getData()" value="开始获取数据">
        <input type="button" onclick="javascript:window.location.href='index.php?r=welcome/index';" value="返回">
    </div>
    <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
	</body>
</html>
