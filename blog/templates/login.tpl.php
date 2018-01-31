<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?=$title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px !important;
        padding-bottom: 40px;
      }
      .hero-unit {
          padding:30px;
      }
      @media screen and (max-width: 480px) {
          .created-time {display:none;}
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
        <div class="span12">
        </div>
      </div>

      <div class="row">
        <div class="span3">
        </div><!--end span3-->

        <div class="span9"> 
            <form class="form-horizontal" action="./login_db.php" method="post"> 
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
                    <button type="submit" class="btn">登入</button>
                    </div>
                </div>
            </form> 
        </div><!--end span9-->

      </div><!--end row-->

    </div> <!-- /container -->

    <script>

    </script> 
</body>
</html>
