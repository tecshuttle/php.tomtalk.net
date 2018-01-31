<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?= $title ?></title>

    <link rel="stylesheet" href="/css/bootstrap-3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/todo.css">
    <style>
        pre:hover {
            color: #333;
        }

        pre {
            background-color: #fff;
            border: 0;
            border-radius: 0;
        }
    </style>
</head>

<body>
<div id="todo-week">
    <div style="margin-bottom: 10px;">
        <button class="btn btn-default btn-xs" onclick="week_chart('pre')">上一周</button>
        <button class="btn btn-default btn-xs" onclick="week_chart('next')">下一周</button>
        <span style="margin-left: 6em;"><?= $user_name ?></span>
        <span style="margin-left: 3em;"><a href="/user/logout">Logout</a></span>
        <span style="margin-left: 12em;color:#ff1493;" id="week_range"></span>
        <span style="float: right;">
            <a class="btn btn-default btn-xs" href="/?project_id=<?= $project_id ?>">周视图</a>
        </span>
    </div>

    <div id="day_report">
        <?= $day_report ?>
    </div>
</div>

</body>

<script src="/js/jquery-1.11.1.min.js"></script>
<script src="/css/bootstrap-3.1.1/js/bootstrap.min.js"></script>

<?php foreach ($js as $jsFile): ?>
    <script src="<?= $jsFile ?>" type="text/javascript"></script>
<?php endforeach; ?>
</html>