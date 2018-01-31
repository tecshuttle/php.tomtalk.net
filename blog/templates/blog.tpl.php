<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $blog['title'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- All in One SEO -->
    <meta name="description" content="<?= $desc ?>"/>
    <meta name="keywords" content="<?= $blog['title'] ?>"/>
    <link rel="canonical" href="http://www.tomtalk.net/"/>
    <!-- /all in one seo pack -->

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <? if (count($lang_js) > 0) : ?>
        <link type="text/css" rel="stylesheet" href="css/shCoreDefault.css"/>
    <? endif; ?>

    <style type="text/css">
        body {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        ol, ul {
            margin-left: 3em;
        }

        h1 {
            margin-top: 0px;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row">
        <div class="span9" id="content">
            <h1><?= $blog['title'] ?></h1>
            <?= $blog['text'] ?>
        </div>
        <!--end span9-->

        <div class="span3">
            <? if ($blog['uid'] === $_SESSION['uid'] OR $_SESSION['uid'] == 6) : ?>
                <a class="btn" style="margin-bottom:.4em;" href="/blog">返回</a>
                <a class="btn" style="margin-bottom:.4em;" href="./edit_blog.php">发表新文章</a>
                <a class="btn" style="margin-bottom:.4em;" href="./edit_blog.php?cid=<?= $blog['cid'] ?>">编辑</a>
                <a class="btn" style="margin-bottom:.4em;" href="./delete_blog.php?cid=<?= $blog['cid'] ?>">删除</a>
            <? endif; ?>
        </div>
        <!--end span3-->
    </div>
</div>
<!-- /container -->
<script src="js/jquery.min.js"></script>

<? if (count($lang_js) > 0) : ?>
    <script type="text/javascript" src="js/syntaxhighlighter/shCore.js"></script>
<? foreach ($lang_js as $lang) : ?>
    <script type="text/javascript" src="js/syntaxhighlighter/shBrush<?= $lang ?>.js"></script>
<? endforeach; ?>

    <script type="text/javascript">
        SyntaxHighlighter.defaults['gutter'] = false;
        SyntaxHighlighter.all();
    </script>
<? endif; ?>

<script>
    var cid = <?=$blog['cid']?>;
    var sec = 10;

    window.setInterval(add_reading_time, 1000 * sec); //10秒

    function add_reading_time() {
        $.get('./reading_time.php', {cid: cid, sec: sec});
    }
</script>
</body>
</html>