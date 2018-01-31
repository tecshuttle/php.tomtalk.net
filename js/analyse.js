function disk_usage_chart(data) {
    $('#disk_usage').highcharts({
        chart: {
            type: 'area'
        },
        colors: ['#dddddd', '#7cb5ec', '#90ed7d', '#f7a35c', '#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
        title: { text: '' },
        xAxis: {
            type: 'datetime',
            labels: {
                formatter: function () {
                    return  Highcharts.dateFormat('%m-%d', this.value);
                }
            }
        },
        yAxis: {
            title: { text: '小时数' },
            tickInterval: 4,
            max: 24
        },
        plotOptions: {
            area: {
                stacking: 'normal',
                marker: {
                    enabled: false
                }
            }
        },
        tooltip: {
            valueSuffix: '%',
            formatter: function () {
                return Highcharts.dateFormat('%Y-%m-%d', this.x)
                    + '<br/><strong>' + this.series.name + '</strong> ' + this.y + '小时';
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: data,
        credits: {enabled: false}
    });
}

disk_usage_chart(data);

function project_day_hours_chart(project_code, project_name, pointStart, data) {
    $('#' + project_code + '_chart').highcharts({
        title: { text: '' },
        chart: {
            height: 200,
            marginRight: 50
        },
        xAxis: {
            type: 'datetime',
            labels: {
                formatter: function () {
                    return  Highcharts.dateFormat('%m-%d', this.value);
                }
            }
        },
        yAxis: {
            title: { text: '小时数' },
            tickInterval: 2,
            min: 0
        },
        plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
        tooltip: {
            valueSuffix: '%',
            formatter: function () {
                return Highcharts.dateFormat('%Y-%m-%d', this.x)
                    + '<br/><strong>' + this.series.name + '</strong> ' + this.y + '小时';
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0,
            enabled: false
        },
        series: [
            {
                name: '记录时数',
                pointInterval: 3600 * 1000 * 24,  //1天
                pointStart: Date.UTC(pointStart.getUTCFullYear(), pointStart.getUTCMonth(), pointStart.getUTCDate()),  //UTC(年，月，日，时，分)
                data: data
            }
        ],
        credits: {enabled: false}
    });
}
function project_chart(project_code, project_name) {
    var chart = $('#' + project_code + '_chart');

    if (chart.is(':visible')) {
        chart.hide();
        return;
    } else {
        chart.show();
    }

    $.ajax({
        url: "/analyse/get_project_day_hours",
        type: "POST",
        data: {
            code: project_code
        },
        timeout: 3000,
        dataType: "json",
        success: function (result) {
            if (result.success) {
                var data = [];
                var pointStart = '';
                $.each(result.data, function (idx, value) {
                    if (pointStart == '') {
                        pointStart = new Date(idx);
                    }

                    data.push(value);
                });

                project_day_hours_chart(project_code, project_name, pointStart, data);
            }
        },
        error: function () {
            $('#content').html('数据读取出错！');
        }
    });

}

function month_chart(direct) {
    var date = month.split('-');

    if (direct == 'pre') {
        if (date[1] == 1) {
            date[0] = date[0] - 1;
            date[1] = 12;
        } else {
            date[1] = date[1] - 1;
        }
    } else {
        if (date[1] == 12) {
            date[0] = parseInt(date[0]) + 1;
            date[1] = 1;
        } else {
            date[1] = parseInt(date[1]) + 1;
        }
    }

    window.location.href = '/analyse?month=' + date.join('-');
}

//end file
