<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?= $title ?></title>

    <link rel="stylesheet" href="/css/bootstrap-3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/todo.css">

    <style>
        @media screen and (max-width: 600px) {
            .pie {
                height: 150px;
            }
        }

        @media screen and (min-width: 760px) and (max-width: 800px) {
            .pie {
                height: 200px;
            }
        }

        @media screen and (min-width: 820px) and (max-width: 1200px) {
            .pie {
                height: 300px;
            }
        }

        @media screen and (min-width: 1210px) and (max-width: 1600px) {
            .pie {
                height: 350px;
            }
        }

        @media screen and (min-width: 1600px) and (max-width: 2000px) {
            .pie {
                height: 400px;
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
            <span style="float: right;">
            <a class="btn btn-default btn-xs" href="/todo">周视图</a>
            </span>
        </div>
    </div>

    <div class="container-fluid">

        <div class="row" id="time_by_project" style="margin: 0em 0em;"></div>

        <div class="row">
            <div id="day_1" class="col-xs-3 pie"></div>
            <div id="day_2" class="col-xs-3 pie"></div>
            <div id="day_3" class="col-xs-3 pie"></div>
            <div id="day_4" class="col-xs-3 pie"></div>
        </div>

        <div class="row" style="margin-top: 6em;">
            <div id="day_5" class="col-xs-3 pie"></div>
            <div id="day_6" class="col-xs-3 pie"></div>
            <div id="day_7" class="col-xs-3 pie"></div>
            <div id="week" class="col-xs-3 pie"></div>
        </div>
    </div>
</body>

<script src="/js/jquery-1.11.1.min.js"></script>
<script src="/css/bootstrap-3.1.1/js/bootstrap.min.js"></script>
<script src="http://www.tomtalk.net/js/highcharts.js"></script>

<?php foreach ($js as $jsFile): ?>
    <script src="<?= $jsFile ?>" type="text/javascript"></script>
<?php endforeach; ?>
</html>