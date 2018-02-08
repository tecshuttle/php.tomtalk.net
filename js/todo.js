/*global $, console */

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

(function ($) {
    $.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        return (r !== null ? decodeURI(r[2]) : null);
    }
})(jQuery);


var todo_site = document.domain;

var bind_event_add = true;  //确保事件只绑定一次的信号量

function add_job(event) {
    //确定当前job，在哪天。
    var container = $(event.target);
    var id = $(container).attr('id');
    var i_day = id.substr(3, 1);

    $.ajax({
        url: "http://" + todo_site + "/todo/add_job",
        type: "POST",
        data: {
            i_day: i_day,
            project_id: $('#project-select').val(),
            week_date: $('#todo-week').attr('week_date')
        },
        timeout: 3000,
        dataType: "json",
        success: function (result) {
            load_todo_list();
        },
        error: function () {
            $('#content').html('数据读取出错！');
        }
    });
}

function job_item_click(obj) {
    var job_item = $(obj.target);
    var job_name = $(job_item).html();
    var rec_id = $(job_item).attr('rec_id');

    $.ajax({
        url: "http://" + todo_site + "/todo/get_job",
        type: "POST",
        data: {
            id: rec_id
        },
        timeout: 3000,
        dataType: "json",
        success: function (result) {
            if (result.success) {
                var job = result.job;
                $('#myModal').modal();
                $('#job_id').val(job.id);
                $('#project_id').val(job.project_id);
                $('#job_name').val(job.job_name);
                $('input:radio[name=job_type_id]')[job.job_type_id].checked = true;

                if (job.job_type_id == 3) {
                    $('input:radio[name=task_type_id]')[job.task_type_id].checked = true;
                    $('#work_type').show();
                } else {
                    $('#work_type').hide();
                }

                $('#time_long').val(job.time_long / 3600 + ' 小时');
                $('#job_desc').val(job.job_desc);

                var job_date = new Date(job.start_time * 1000).Format('yyyy-MM-dd');
                $('#start_time').val(job_date);
                $('#start_time').attr('job_date', job_date);

                $("#start_time").datepicker({
                    dateFormat: 'yy-mm-dd',
                    dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
                    monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                    firstDay: 1
                });

                $("#time_long_slider").slider({
                    value: job.time_long / 60,
                    min: 0,
                    max: 60 * 5, //最大5小时
                    step: 6,
                    slide: function (event, ui) {
                        $("#time_long").val(ui.value / 60 + ' 小时');
                    }
                });
            }
        },
        error: function () {
            $('#content').html('数据读取出错！');
        }
    });
}


function get_form_value() {

    var job_id = $('#job_id').val();
    var project_id = $('#project_id').val();
    var job_name = $('#job_name').val();
    var job_type_id = $("input[name=job_type_id]:checked").val()
    var task_type_id = $("input[name=task_type_id]:checked").val()
    var time_long = $('#time_long').val().replace(' 小时', '');
    var job_desc = $('#job_desc').val();

    var data = {
        project_id: project_id,
        id: job_id,
        job_name: job_name,
        job_type_id: job_type_id,
        task_type_id: task_type_id,
        time_long: time_long * 3600,
        job_desc: job_desc
    };


    //判断日期是否修改过，决定是否传值
    var job_date = $('#start_time').attr('job_date');
    var new_date = $('#start_time').val();
    if (job_date != new_date) {
        var start_time = new Date(new_date);
        data.start_time = start_time.valueOf() / 1000;
    }

    return data;
}

function job_save() {
    job_update(get_form_value());
}

function job_done() {
    var data = get_form_value();
    data.status = 1;

    job_update(data);
}

function job_update(data) {
    $.ajax({
        url: "http://" + todo_site + "/todo/job_edit",
        type: "POST",
        data: data,
        timeout: 3000,
        dataType: "json",
        success: function (result) {
            if (result.success) {
                load_todo_list();
            }
        },
        error: function () {
            $('#content').html('数据读取出错！');
        }
    });
}

function init_drag_drop() {
    $(".connectedSortable").sortable({
        connectWith: ".connectedSortable",
        stop: function (event) {
            //console.log(event);

            //var from_container = event.target;
            var item = event.toElement;
            var to_container = $(item).parent();

            //console.log($(item).prev().attr('rec_id'));
            //console.log($(item).next().attr('rec_id'));

            var rec_id = $(item).attr('rec_id');
            var to_day = $(to_container).attr('id');

            move_job(rec_id, to_day, $(item).prev(), $(item).next());
        },
        placeholder: "ui-state-highlight",
        cursor: "move",
        opacity: 0.6,                       //拖动时，透明度为0.6
        over: function (event, ui) {
            //console.log(event, ui);
        }
    }).disableSelection();
}

function move_job(rec_id, to_day, prev_item, next_item) {
    $.ajax({
        url: "http://" + todo_site + "/todo/move_job",
        type: "POST",
        data: {
            id: rec_id,
            week_date: $('#todo-week').attr('week_date'),
            to_day: to_day,
            prev_job_id: (prev_item.attr('rec_id') === undefined ? 0 : prev_item.attr('rec_id')),
            next_job_id: (next_item.attr('rec_id') === undefined ? 0 : next_item.attr('rec_id'))
        },
        timeout: 3000,
        dataType: "json",
        success: function (result) {
            if (!result.success) {
                console.log(result);
            }
        },
        error: function () {
            $('#content').html('数据读取出错！');
        }
    });
}

function set_day_title() {
    //取当前周日期
    var day = new Date($('#todo-week').attr('week_date'));

    //确定周一日期
    var day1_ms = '';
    if (day.getDay() === 0) {
        day1_ms = day.valueOf() - 3600 * 24 * 1000 * 6;
    } else {
        day1_ms = day.valueOf() - 3600 * 24 * 1000 * (day.getDay() - 1);
    }

    //循环计算本周日期
    var week_name = {1: '周一', 2: '周二', 3: '周三', 4: '周四', 5: '周五', 6: '周六', 0: '周日'};

    for (var i = 0; i < 7; i++) {
        var day1 = new Date(day1_ms);
        var day_title = $('#day_title' + day1.getDay());

        if (new Date().Format('yyyy-MM-dd') === day1.Format('yyyy-MM-dd')) {
            day_title.addClass('today');
        } else {
            day_title.removeClass('today');
        }

        day_title.prepend(day1.Format('MM-dd') + ' ' + week_name[day1.getDay()]);
        day1_ms = day1_ms + 3600 * 24 * 1000;
    }
}

function load_todo_list() {

    var week_date = $('#todo-week').attr('week_date');

    var project_id = $.getUrlParam('project_id');

    if (project_id === null) {
        project_id = '';
    } else {
        $('#send_report_mail').show();
        $('#project-select').val(project_id);
    }

    $.ajax({
        url: "http://" + todo_site + "/todo/get_jobs_of_week",
        type: "POST",
        data: {
            day: week_date,
            project_id: project_id

        },
        dataType: "json",
        success: function (result) {
            $('.connectedSortable>li').remove();

            var list = '';

            $.each(result, function (i, jobs) {
                i = parseInt(i);
                var total = 0;
                var done = 0;
                $.each(jobs, function (j, a) {
                    var status = parseInt(a.status);
                    var time_long = parseInt(a.time_long);
                    total += time_long;
                    done += (status === 1 ? time_long : 0);
                    var popover = '';
                    if (a.job_desc.trim() !== '') {
                        var position = 'right';
                        if (i === 0 || i === 6 || i === 5) {
                            position = 'left';
                        }
                        popover = 'data-toggle="popover" data-placement="' + position + '" data-content="' + a.job_desc + '"';
                    }

                    var is_done = '';
                    if (status === 1) {
                        is_done = ' done_job ';
                    }

                    var is_bold = '';
                    if (a.project_id !== '0') {
                        is_bold = ' project_job ';
                    }

                    list += '<li class="' + is_bold + is_done + '" rec_id="' + a.id + '" ' + popover + '>';
                    list += a.job_name + (a.job_desc === '' ? '' : '...');

                    var color_set = ['#dddddd', '#7cb5ec', '#90ed7d', '#f7a35c', '#058DC7', '#50B432', '#ED561B', '#DDDF00'];
                    var badge_color = ' style="background-color: ' + color_set[a.job_type_id] + ';" ';
                    list += '<span ' + badge_color + ' class="badge">' + (a.time_long / 3600).toFixed(1) + ' </span > ';

                    list += '</li>\n';
                });

                var day_job_time = '<span class="day_job_time">' + (done / 3600).toFixed(1) + '/' + (total / 3600).toFixed(1) + '</span>';
                $('#day_title' + i).html(day_job_time);
                if (list === '' && project_id === '') {
                    list = '<button class="btn btn-warning btn-xs" onclick="init_day(this, \'' + week_date + '\', ' + i + ');">初始化</button>';
                }
                $('#day' + i).html(list);
                list = '';
            });

            if (bind_event_add) {
                bind_event_add = false;
                $(".connectedSortable ").on('click', add_job);
            }

            $('.connectedSortable li').popover({
                trigger: 'hover',
                html: true
            });

            $(".connectedSortable li").on('click', job_item_click);

            init_drag_drop();

            set_day_title(); //更新星期标题

        },
        error: function () {
            $('#content').html('数据读取出错！');
        }
    });
}

function init_todo_list() {
    //确定当前周数，用于切换前后周
    $('#todo-week').attr('week_date', get_first_day_of_week(new Date()));

    $('#job_save_btn').on('click', job_save);
    $('#job_done_btn').on('click', job_done);
}

function get_first_day_of_week(date) {
    var w = date.getUTCDay();

    if (w == 0) {
        w += 1;
    }

    var time = date.getTime() - ((w - 1) * 3600 * 24 * 1000);
    var first = new Date(time);

    return first.Format('yyyy-MM-dd');
}

function init_day(obj, week_date, day) {
    $(obj).attr('disabled', true);
    $(obj).html('init...');

    $.ajax({
        url: "http://" + todo_site + "/todo/init_day",
        type: "POST",
        data: {
            week_date: week_date,
            day: day
        },
        timeout: 3000,
        dataType: "json",
        success: function (result) {
            if (result.success) {
                load_todo_list();
            }
        },
        error: function () {
            $('#content').html('数据读取出错！');
        }
    });
}


$(function () {
    init_todo_list();
    load_todo_list();

    $('input[name=job_type_id]').change(function () {
        var work_type = $('#work_type');

        if ($(this).val() == 3) {
            work_type.show();
        } else {
            work_type.hide();
        }
    });

    $('#project-select').change(function () {
        var project_id = $(this).val();
        if (project_id === '0') {
            window.location.href = '/todo';
        } else {
            window.location.href = '?project_id=' + project_id;
        }
    });
});

function week_date(direct) {
    var todo_week = $('#todo-week');
    var cur_day = todo_week.attr('week_date');
    var ms = new Date(cur_day).valueOf();

    var day = '';

    if (direct === 'pre') {
        day = new Date(ms - 3600 * 24 * 7 * 1000);
    } else {
        day = new Date(ms + 3600 * 24 * 7 * 1000);
    }

    todo_week.attr('week_date', day.Format('yyyy-MM-dd'));

    load_todo_list();
}

function export_csv() {
    window.location.href = "http://" + todo_site + "/todo/export_csv?day=" + $('#todo-week').attr('week_date');
}

//end file