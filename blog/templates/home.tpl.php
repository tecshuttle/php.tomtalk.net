<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>tomtalk.net</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="享受编程和技术所带来的快乐 – http://www.tomtalk.net" /> 
    <meta name="description" content="正则表达式,程序员,算法,软件开发,AJAX,Bash,book,Code Review,Coding,CSS,Database,Debug,Erlang,Go,Google,HTML,Javascript,jQuery,Linux,Mac,MySQL,Perl,PHP,Programmer,programming,Python,Ruby,SQL,vim,Web">
    <meta name="author" content="tom.xie">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

    <style type="text/css">
      html{
        height:100%;
      }
      body {
        background-color: #ffffff;
        color: #333333;
        font-family: "Microsoft Yahei","Helvetica Neue","Luxi Sans","DejaVu Sans",Tahoma,"Hiragino Sans GB","STHeiti" !important;
        font-size: 1em;
        line-height: 20px;
        margin-top:0px;
        margin-bottom:0px;
        margin-left:auto;
        margin-right:auto; 
        width:90%;
        height:100%
      }
      a {
        color: #08C;
        text-decoration: none;
      }
      #container {
        height:100%;
        margin-bottom:-3em;
      }
      #footer {
        height:2empx;
        text-align:center;
      }
      .top-menu {
        padding-top:2em;
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
      }

      .verycd-title {
        text-align:center; 
      }
      .verycd-title span{
          text-align:left;
          color: #666;
          font-size: 2em;
          line-height: 1.7em;
          display:inline-block;
      }
      @media screen and (max-width: 480px) {
          .created-time {display:none;}
      }
    </style> 
  </head> 

  <body> 

    <div id="container">
      <div class="top-menu">
        <a href="/" style="background-color:#2FB8CB">首页</a>
        <a href="http://todo.tomtalk.net/vue" >博客</a>
        <a href="/wiki" >wiki</a>
        <a href="/memorize" >别忘了</a>
        <a href="http://todo.tomtalk.net" >GTD</a>
        <a href="/monitor.html" >monitor</a>
      </div> 

      <div class="verycd-title">
        <span> <?=$verycd_title?> </span> 
      </div>
    </div> 
                                
    <div id="footer">
      本站由<a href="blog/user.php?uid=6">tecshuttle</a>制做，
      并由<a href="blog/all_user.php">全体成员</a>贡献内容。   
    </div>

  </body>
<?=$ga?>
</html>
