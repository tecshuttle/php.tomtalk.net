<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?=$user['name']?></title>
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
        <div class="span1"><a href="/blog" >博客</a></div>
        <div class="span1"><a href="/photo">好图</a></div>
        <div class="span1"><a href="/shop" >东西</a></div>
        <div class="span1">朋友</div>
        <div class="span1">部落</div>
        <div class="span1">图书</div>
        <div class="span1">简历</div>
        <div class="span1">音乐</div>
        <div class="span1">电影</div>
      </div>

      <div class="row">
        <div class="span3">
        </div>

        <div class="span9"> 
          <p>name:<?=$user['name']?>
          <p>mail:<?=$user['mail']?>
          <p>group:<?=$user['group']?>

            <table class="table"> 
                <? while ( $row = mysql_fetch_array($read_blog_list) ): ?> 
                <tr>
                    <td><a href="blog.php?cid=<?=$row['cid']?>"><?=$row['title']?></a></td> 
                    <td class="author"><?=$row['second']?></td>
                </tr> 
                <? endwhile; ?> 
            </table>
        </div>

      </div>

    </div> <!-- container -->

  </body>
<?=$ga?>
</html>
