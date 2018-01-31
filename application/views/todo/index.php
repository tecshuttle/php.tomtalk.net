<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Todo List</title>
    <link rel="stylesheet" href="/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/css/bootstrap-3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/todo.css">
</head>

<body>

<div id="todo-week">
    <div style="margin-bottom: 10px;">
        <button class="btn btn-default btn-xs" onclick="week_date('pre')">上一周</button>
        <button class="btn btn-default btn-xs" onclick="week_date('next')">下一周</button>
        <span style="margin-left: 6em;"><?= $user_name ?></span>
        <span style="margin-left: 3em;"><a href="/user/logout">Logout</a></span>

        <div style="float: right;">
            <a class="btn btn-default btn-xs" href="/analyse">月统计</a>
            <a class="btn btn-default btn-xs" href="/analyse/week_view">周统计</a>
            <a class="btn btn-default btn-xs" href="/analyse/work_week_report">工作周报</a>
            <select class="form-control project-select" id="project-select">
                <option value="0">无</option>
                <?php foreach ($projects as $p): ?>
                    <option value="<?= $p->id ?>"><?= $p->name ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($project_id !== false): ?>
                <a class="btn btn-default btn-xs" href="/analyse/send_report_mail?project_id=<?= $project_id ?>"
                   id="send_report_mail">周报邮件</a>
            <?php endif; ?>
            <a class="btn btn-success btn-xs" onclick="export_csv();">导出csv</a>
        </div>
    </div>

    <div id="day_title1" class="day_title"></div>
    <div id="day_title2" class="day_title"></div>
    <div id="day_title3" class="day_title"></div>
    <div id="day_title4" class="day_title"></div>
    <div id="day_title5" class="day_title"></div>
    <div id="day_title6" class="day_title"></div>
    <div id="day_title0" class="day_title"></div>

    <br/>

    <ul id="day1" class="connectedSortable"></ul>
    <ul id="day2" class="connectedSortable"></ul>
    <ul id="day3" class="connectedSortable"></ul>
    <ul id="day4" class="connectedSortable"></ul>
    <ul id="day5" class="connectedSortable"></ul>
    <ul id="day6" class="connectedSortable"></ul>
    <!-- sunday's index is 0  -->
    <ul id="day0" class="connectedSortable"></ul>
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
                        <label class="col-sm-2 control-label">归属项目</label>

                        <div class="col-sm-10">
                            <select class="form-control" id="project_id" style="padding:4px 7px;">
                                <option value="0">无</option>
                                <?php foreach ($projects as $p): ?>
                                    <option value="<?= $p->id ?>"><?= $p->name ?></option>
                                <?php endforeach; ?>
                            </select>
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

                    <div class="form-group" id="work_type">
                        <label class="col-sm-2 control-label">工作类型</label>

                        <div class="col-sm-10">
                            <?php foreach ($work_type as $item): ?>
                                <label class="radio-inline">
                                    <input type="radio" name="task_type_id"
                                           value="<?= $item['id'] ?>" <?= ($item['id'] == 0 ? 'checked' : '') ?>> <?= $item['name'] ?>
                                </label>
                            <?php endforeach; ?>
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
                        <label class="col-sm-2 control-label">任务名称</label>

                        <div class="col-sm-10">
                            <input type="input" class="form-control" id="job_name" placeholder="任务名称">
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
<script src="/js/todo.js"></script>

</html>