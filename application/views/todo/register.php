<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register</title>

    <link rel="stylesheet" href="/css/bootstrap-3.1.1/css/bootstrap.min.css">

    <style>
        body {
            margin: 1em;
            padding: 15px 0px 10px 0;
            font-size: 14px;
            font-family: "Lantinghei SC", "Open Sans", Arial, "Hiragino Sans GB", "Microsoft YaHei", "微软雅黑", "STHeiti", "WenQuanYi Micro Hei", SimSun, sans-serif;
            color: #333;
        }
    </style>

</head>

<body>

<div class="col-xs-12 col-sm-12" style="font-size: 2em;text-align: center;margin-bottom: 1em;">
    <p> Simple Todo List

    <p> 时间，看起来多，用起来少！
</div>


<form action="/user/register_submit" method="post">
    <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="请您输入用户名">
    </div>

    <div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="请您输入密码">
    </div>

    <button type="submit" class="col-xs-12 col-sm-12 btn btn-success">注册</button>
</form>

</body>

<script src="/js/jquery-1.10.2.js"></script>
<script src="/css/bootstrap-3.1.1/js/bootstrap.min.js"></script>

</html>