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
      input, textarea {
        width:100%;
      }
      body {
        padding-top: 1em !important;
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

        <div class="span12"> 
            <form class="form-horizontal" action="./edit_blog_db.php" method="post">
                <input type="hidden" name="cid" value="<?=$blog['cid']?>"> 

                <div class="control-group">
                    <label class="control-label" for="inputEmail">标题</label>
                    <div class="controls">
                    <input type="text" id="title" name="title" value="<?=$blog['title']?>">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputEmail">标签</label>
                    <div class="controls">
                        <select name="tag"> 
                        <option value="">请选择</option>
                        <? while ($row = mysql_fetch_array($tags)) :?>
                        <option <?=($blog_tag['tag_id'] == $row['id'] ? 'selected' : '') ?> value="<?=$row['id']?>"><?=$row['tag']?></option>
                        <? endwhile ;?>
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputPassword">内容</label>
                    <div class="controls">
                        <textarea name="content" rows="30"><?=$blog['text']?></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                    <button type="submit" class="btn">发表</button>
                    </div>
                </div>
            </form> 
        </div>

      </div><!--end row-->

    </div> <!-- /container -->

    <script>

    </script> 
</body>
</html>
