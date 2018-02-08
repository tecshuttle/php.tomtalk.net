<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Todo Chart</title>

    <link rel="stylesheet" href="/css/bootstrap-3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/todo.css">
</head>

<body>
<div id="todo-week">
    <div style="margin-bottom: 10px;">
        <button class="btn btn-default btn-xs" onclick="month_chart('pre')">上一月</button>
        <button class="btn btn-default btn-xs" onclick="month_chart('next')">下一月</button>
        <span style="margin-left: 6em;"><?= $user_name ?></span>
        <span style="margin-left: 3em;"><a href="/user/logout">Logout</a></span>
        <span style="float: right;">
        <a class="btn btn-default btn-xs" href="/todo">周视图</a>
        </span>
    </div>

    <div id="disk_usage" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

    <div style="margin: 1em;">
        <?php foreach ($projects as $item): ?>
            <p>
                <span style="display: inline-block; width:12em;"><?= $item['name'] ?></span>
                <span style="display: inline-block; width:6em;text-align: right;margin-right:2em;">
                    <?= $item['total_hours'] ?>小时
                </span>

                <button onclick="project_chart('<?= $item['project_id'] ?>', '<?= $item['name'] ?>');" class="btn btn-default btn-xs">
                    图表
                </button>

                <a href="/analyse/export_project_task_list?project_id=<?= $item['project_id'] ?>" style="margin-left:2em;">
                    任务清单CSV
                </a>
            </p>
            <div id="<?= $item['project_id'] ?>_chart" style="display:none;"></div>
        <?php endforeach; ?>
    </div>
</div>
</body>

<script src="/js/jquery-1.11.1.min.js"></script>
<script src="/css/bootstrap-3.1.1/js/bootstrap.min.js"></script>
<script src="http://www.tomtalk.net/js/highcharts.js"></script>

<script type="text/javascript">
    var month = '<?=$month?>';
    var data = <?=$month_chart_data?>;
</script>

<?php foreach ($js as $jsFile): ?>
    <script src="<?= $jsFile ?>" type="text/javascript"></script>
<?php endforeach; ?>
</html>