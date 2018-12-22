$(function(){
    buildChart('/admin/stats/charts.json');
});

function buildChart(link)
{
    try {
        $('#charts').highcharts().destroy();
    } catch (ex) {

    }
    $.getJSON(link, function (json) {
        $('#charts').highcharts('StockChart', {
            xAxis: {
                type: 'datetime',
                title: {
                    text: json.title
                }
            },
            yAxis: {
                title: {
                    text: 'Сумма'
                }
            },
            rangeSelector: {
                selected : 1,
                inputEnabled: false
            },
            navigator : {
                enabled : false
            },
            series: [
                {
                    name: 'Еврокредиты',
                    data: json.data['ekr_diff'],
                    pointInterval: 24 * 3600 * 1000
                },
                {
                    name: 'Кредиты',
                    data: json.data['kr_diff'],
                    pointInterval: 24 * 3600 * 1000
                },
                {
                    name: 'Монеты',
                    data: json.data['gold_diff'],
                    pointInterval: 24 * 3600 * 1000
                }
            ]
        });
    });
}
