<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>登入</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top:40px !important;
        padding-bottom: 40px;
      }
      @media screen and (max-width: 480px) {
          .created-time {display:none;}
          .verycd-title {display:none;}
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head> 

  <body> 
  <div class="container">

      <div class="row">

        <div class="span6" style="margin-bottom:2em;">
            <img src="img/tomtalk.png">
            <p class="verycd-title" style="padding:0 2em;font-size:1.5em;color:#666;"><?=$verycd_title?>
        </div>

        <div class="span6"> 
            <ul class="nav nav-tabs" id="myTab">
                <li id="login" class="active"><a href="#" onclick="tab('login');">登入</a></li>
                <li id="reg"><a href="#" onclick="tab('reg');">注册</a></li>
            </ul>

            <form id="form" class="form-horizontal" action="./login_db.php" method="post"> 
                <input type="hidden" name="return" value="<?=$return?>">

                <div class="control-group">
                    <label class="control-label" for="inputEmail">用户名</label>
                    <div class="controls">
                    <input type="text" id="title" name="name">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">密码</label>
                    <div class="controls">
                        <input type="password" id="title" name="password">
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                    <button type="submit" id="submit" class="btn btn-primary">登入</button>
                    <span style="margin-left:2em;"><a href="./list.php">访客</a>
                    </div>
                </div>
            </form> 
        </div><!--end span9-->

      </div><!--end row-->

    </div> <!-- /container -->
    <footer class="footer" style="margin-top:1em;border-top:1px solid #DDD;">
        <div class="container">
        <div class="row" style="text-align:center;">
            <div class="span6" style="padding-top:1em;">
                本站由<a href="./user.php?uid=6">tecshuttle</a>制做，并由<a href="./all_user.php">全体会员</a>贡献文章。   
            </div>
            <div class="span6" style="padding-top:1em;">
                目前共有 <?=$user_num?> 用户
            </div>
        </div>
        </div>
    </footer>

    <script src="/js/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script>
    function tab(tab) {
        if (tab == 'login') {
            $('#login').addClass('active')
            $('#reg').removeClass('active')
            $('#submit').html('登入')
            $('#form').attr('action', './login_db.php')
        } else {
            $('#login').removeClass('active')
            $('#reg').addClass('active')
            $('#submit').html('注册')
            $('#form').attr('action', './regist_db.php')
        }
    }
    </script>
</body>
<?=$ga?>
</html>
