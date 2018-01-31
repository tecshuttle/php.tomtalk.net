<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>目录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/blog/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 30px !important;
        padding-bottom: 40px;
      }
      .hero-unit {
          padding:30px;
      }
      .table td { border:none; }
      @media screen and (max-width: 480px) {
          .created-time {display:none;}
          .author {display:none;}
      }
      a {
        color: #08C;
        text-decoration: none;
      }
      .top-menu {
        padding-top:0em;
        height:30%;
      } 
      .top-menu a {
        background-color:#08C;
        color:white;
        display: inline-block;
        height: 1.9em;
        line-height: 1.8em;
        margin: 0 .3em .6em 0;
        text-align: center;
        width: 6em
      }
      .top-menu a:hover {
        background-color:#2FB8CB;
        text-decoration: none;
      }
      .sel-tag {
        background-color:#2FB8CB !important;
        text-decoration: none;
      }
    </style>
    <link href="/blog/css/bootstrap-responsive.css" rel="stylesheet"> 
  </head> 

  <body> 
  <div class="container">
  
      <div class="row">
        <div class="span12" style="margin-bottom:1em;">
            <? if (isset($_SESSION['uid'])) :?>
                <a class="btn" href="./edit_blog.php">发表新文章</a>
                <a class="btn" href="./user.php?uid=<?=$_SESSION['uid']?>"><?=$_SESSION['name']?></a> 
                <a class="btn" href="./logout.php">退出</a> 
            <? else :?>
                <a class="btn" href="./login.php">登入</a>
            <? endif;?>
        </div>
      </div>

      <div class="row">
        <div class="span12 top-menu" style="margin-bottom:1em;">
            <a <?=($tag == $row['tag'] ? ' class="sel-tag" ' : '')?>href="/blog">未标记</a>
            <? while ( $row = mysql_fetch_array($tags) ): ?> 
            <a <?=($tag == $row['tag'] ? ' class="sel-tag" ' : '')?>href="/blog/tag/<?=$row['tag']?>"><?=$row['tag']?></a>
            <? endwhile;?>
        </div>
      </div>

      <div class="row">
        <div class="span3">
        </div><!--end span3-->

        <div class="span9"> 

            <table class="table"> 
                <? while ( $row = mysql_fetch_array($rows) ): ?> 
                <tr>
                    <td><a href="/blog/<?=$row['title']?>"><?=$row['title']?></a></td> 
                    <td class="created-time"><?=substr($row['created'],0,10)?></td>
                </tr> 
                <? endwhile; ?> 
            </table>

            <?=$pagebar?> 

        </div><!--end span9-->

      </div><!--end row-->

    </div> <!-- /container -->

    <script>

    </script>

    <script src="/blog/js/jquery.min.js"></script>
</body>
<?=$ga?>
</html>
