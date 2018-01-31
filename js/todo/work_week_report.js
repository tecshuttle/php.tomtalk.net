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

    window.location.href = '/analyse/work_week_report?day=' + day.Format('yyyy-MM-dd');
}

function get_week_range(date) {
    var w = date.getUTCDay();

    if (w == 0) {
        w += 1;
    }

    var start = date.getTime() - ((w - 1) * 3600 * 24 * 1000);
    var first = new Date(start);

    var end = first.getTime() + ( 6 * 3600 * 24 * 1000);
    var sunday = new Date(end);

    return first.Format('yyyy-MM-dd') + ' - ' + sunday.Format('yyyy-MM-dd');
}

var url_day = $.getUrlParam('day');

if (url_day === null) {
    url_day = new Date().Format('yyyy-MM-dd');
}

$(function () {
    get_week_time_by_project();

    $('#week_range').html(get_week_range(new Date(url_day)));
});

function show_project_title(projects) {
    var html = '';

    $.each(projects, function (i, p) {

        if (p.project_id === null) {
            p.project_id = 0;
            p.project_name = '其它';
        }

        html += '<div class="project-item">'
            + '<h4 class="project_title" project_id="' + p.project_id + '">' + p.project_name + ' ' + p.total / 3600 + '小时' + '</h4>'
            + '</div>';
    });

    $('#time_by_project').html(html);
}

function show_project_jobs() {
    var project_title = $('h4.project_title');

    $.each(project_title, function (i, p) {
        var project_id = $(p).attr('project_id');

        $.ajax({
            url: "/analyse/get_work_week_report_jobs_by_project_id",
            type: "POST",
            data: {
                week_date: url_day,
                project_id: project_id
            },
            dataType: "json",
            success: function (result) {
                //console.log(result);
                var jobs = '';
                $.each(result, function (i, job) {
                    jobs += '<p>' + job.name + '</p>';
                });

                $(p).after('<div class="project-jobs">' + jobs + '</div>');
            }
        });
    });
}

function get_week_time_by_project() {
    $.ajax({
        url: "/analyse/get_week_time_by_project",
        type: "POST",
        data: {
            week_date: url_day
        },
        dataType: "json",
        success: function (result) {
            show_project_title(result);
            show_project_jobs();
        }
    });
}


//end file