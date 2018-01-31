<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Day</title>
    <link rel="stylesheet" href="/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/css/bootstrap-3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/todo.css">

    <style>
        .connectedSortable {
            width: 100%;
        }

        .day_title {
            text-align: center;
            width: 100%;
        }
    </style>
</head>

<body>


<div id="todo-week" class="container-fluid">
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-4">
            <button class="btn btn-default btn-xs" onclick="load_date('pre');">
                <span class="glyphicon glyphicon-arrow-left"></span>
            </button>
            <button class="btn btn-default btn-xs" onclick="load_date('today');">
                今天
            </button>
            <button class="btn btn-default btn-xs" onclick="load_date('next');">
                <span class="glyphicon glyphicon-arrow-right"></span>
            </button>
        </div>

        <div class="col-xs-4"> <?= $user_name ?> </div>
        <div class="col-xs-2"><a href="/user/logout">Logout</a></div>
        <div class="col-xs-2"><a class="btn btn-default btn-xs" target="_blank" href="/analyse">统计</a></div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <div id="today" class="day_title">1月23号 周五</div>
        </div>
        <div class="col-xs-6">
            <div id="day_title1" class="day_title"></div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <ul id="day1" class="connectedSortable"></ul>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">任务编辑</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" role="form">
                    <input type="hidden" id="job_id" value="0">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">任务名称</label>

                        <div class="col-sm-10">
                            <input type="input" class="form-control" id="job_name" placeholder="任务名称">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">任务类型</label>

                        <div class="col-sm-10">
                            <label class="radio-inline"> <input type="radio" name="job_type_id" value="0" checked> 其它
                            </label>
                            <label class="radio-inline"> <input type="radio" name="job_type_id" value="1"> 家庭 </label>
                            <label class="radio-inline"> <input type="radio" name="job_type_id" value="2"> 学习 </label>
                            <label class="radio-inline"> <input type="radio" name="job_type_id" value="3"> 工作 </label>
                            <label class="radio-inline"> <input type="radio" name="job_type_id" value="4"> 睡觉 </label>
                            <label class="radio-inline"> <input type="radio" name="job_type_id" value="5"> 跑步 </label>
                            <label class="radio-inline"> <input type="radio" name="job_type_id" value="6"> GRE </label>
                            <label class="radio-inline"> <input type="radio" name="job_type_id" value="7"> 交通 </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">任务日期</label>

                        <div class="col-sm-10">
                            <input type="input" class="form-control" id="start_time" placeholder="任务日期">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">任务耗时</label>

                        <div class="col-sm-10">
                            <input type="input" class="form-control" id="time_long" placeholder="任务时长" disabled>

                            <div id="time_long_slider" style="margin-top:8px;"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">任务说明</label>

                        <div class="col-sm-10">
                            <textarea class="form-control" rows="7" id="job_desc" placeholder="任务说明"></textarea>
                        </div>
                    </div>
                </form>


            </div>
            <div class="modal-footer" style="text-align: left;">
                <button type="button" class="btn btn-success" id="job_done_btn" data-dismiss="modal">完成</button>
                <button type="button" class="btn btn-primary" id="job_save_btn" data-dismiss="modal"
                        style="float:right;">保存
                </button>
            </div>
        </div>
    </div>
</div>
</body>

<script src="/js/jquery-1.11.1.min.js"></script>
<script src="/js/jquery-ui-1.11.1.js"></script>
<script src="/js/jquery.ui.touch-punch.min.js"></script>
<script src="/css/bootstrap-3.1.1/js/bootstrap.min.js"></script>
<script src="/js/day.js"></script>

</html>