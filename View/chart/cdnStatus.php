<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>CDN4XX5XX TOP100</title>
        <style type="text/css">
            .red{
                color:red;
            }
        </style>
		<script type="text/javascript" src="<?=JS_URL?>jquery.min.js"></script>
        <script src="<?=JS_URL?>highcharts.js"></script>
		<script type="text/javascript">
            var chart;
            var options = {
                chart: {
                    renderTo: 'container',
                    type: 'bar'
                },
                title: {
                    text: 'CDN 4XX 5XX TOP 100'
                },
                subtitle: {
                    text: 'Source: Wikipedia.org'
                },
                xAxis: {
                    categories: ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
                    title: {
                        text: null
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Population (millions)',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                tooltip: {
                    formatter: function() {
                        return ''+
                            this.series.name +': '+ this.y +' millions';
                    }
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -100,
                    y: 0,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: '#FFFFFF',
                    shadow: true
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Year 1800',
                    data: [107, 31, 635, 203, 2]
                }, {
                    name: 'Year 1900',
                    data: [133, 156, 947, 408, 6]
                }, {
                    name: 'Year 2008',
                    data: [973, 914, 4054, 732, 34]
                }]
            };
            $(document).ready(function(){
                chart = new Highcharts.Chart(options);
            });
            function getData(){
                var status = $("#status").val();
                var param = {status:status};
                $.get('index.php?r=chart/ajaxGetCdnData',param,function(data){
                    options.series = [];
                    for(i=0;i<data.length;i++){
                        var v = data[i];
                        options.series.push(v);
                    }
                    chart = new Highcharts.Chart(options);
                },'json');
            }
    </script>
	</head>
	<body>
    <div>
        <select name="status" id="status">
            <?=Html::html_options($dataType)?>
        </select>
        <input type="button" onclick="getData()" value="开始获取数据">
        <input type="button" onclick="javascript:window.location.href='index.php?r=welcome/index';" value="返回">
    </div>
    <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
	</body>
</html>
