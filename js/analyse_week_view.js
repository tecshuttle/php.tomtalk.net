(function ($) {
    $.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        return (r !== null ? decodeURI(r[2]) : null);
    }
})(jQuery);

// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
// 例子：
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
Date.prototype.Format = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1,                 //月份
        "d+": this.getDate(),                    //日
        "h+": this.getHours(),                   //小时
        "m+": this.getMinutes(),                 //分
        "s+": this.getSeconds(),                 //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds()             //毫秒
    };
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(fmt)) {
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        }
    }
    return fmt;
};

function pie(id, data) {
    var total = 0;

    $.each(data, function () {
        total += this.y;
    });

    var day_name = {
        'day_1': '周一',
        'day_2': '周二',
        'day_3': '周三',
        'day_4': '周四',
        'day_5': '周五',
        'day_6': '周六',
        'day_7': '周日',
        'week': '本周总计'
    };

    $('#' + id).highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: day_name[id] + ' ' + total.toFixed(1) + ' 小时'  //'Browser market shares at a specific website, 2010'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    distance: 10,
                    connectorColor: '#000000',
                    style: {"color": "#606060", "fontSize": "12px"},
                    format: '<b>{point.name}</b><br/>{point.y} 小时'
                }
            }
        },
        series: [
            {
                type: 'pie',
                name: 'Work Type',
                data: data
            }
        ],
        credits: {enabled: false}
    });
}

$(function () {
    $.ajax({
        url: "/todo/get_work_type",
        type: "POST",
        data: {
            dataType: 'json'
        },
        dataType: "json",
        success: function (work_type) {
            draw_pie(work_type);
        }
    });

    get_week_time_by_project();
});

function get_week_time_by_project() {
    var url_day = $.getUrlParam('day');

    if (url_day === null) {
        url_day = new Date().Format('yyyy-MM-dd');
    }

    $.ajax({
        url: "/analyse/get_week_time_by_project",
        type: "POST",
        data: {
            week_date: url_day
        },
        dataType: "json",
        success: function (result) {
            var project = [];

            $.each(result, function (i, p) {
                var name = (p.project_name === null ? '其它' : p.project_name);
                project.push(name + ' ' + p.total / 3600 + '小时');
            });

            $('#time_by_project').html('<h4>项目用时</h4>' + project.join('<span style="display: inline-block;width:2em;"></span>'));
        }
    });
}

function draw_pie(work_type) {
    var url_day = $.getUrlParam('day');

    if (url_day === null) {
        url_day = new Date().Format('yyyy-MM-dd');
    }

    var pies = $('.pie');

    $.each(pies, function () {
        var id = $(this).attr('id');

        $.ajax({
            url: "/analyse/getPieDataOfTaskType",
            type: "POST",
            data: {
                week_date: url_day,
                i_day: id
            },
            dataType: "json",
            success: function (result) {
                var data = [];
                $.each(result, function () {
                    data.push({
                        name: work_type[this.task_type_id].name,
                        color: work_type[this.task_type_id].color,
                        y: parseInt(this.time_long) / 3600
                    });
                });

                if (data.length > 0) {
                    pie(id, data);
                }
            }
        });
    });
}


function week_chart(direct) {
    var url_day = $.getUrlParam('day');

    if (url_day === null) {
        url_day = new Date().Format('yyyy-MM-dd');
    }

    var day_time = new Date(url_day).getTime();

    if (direct == 'pre') {
        day_time -= 3600 * 24 * 1000 * 7;
    } else {
        day_time += 3600 * 24 * 1000 * 7;
    }

    var day = new Date(day_time);

    window.location.href = '/analyse/week_view?day=' + day.Format('yyyy-MM-dd');
}

//end file