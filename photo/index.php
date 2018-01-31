<?php
if (! isset($_SESSION['uid'])) {
    header('location:/blog/login.php'); 
}
?>
<!doctype html>
<html>
    <head>
    <title><?=isset($_REQUEST['tag']) ? $_REQUEST['tag'] : 'photo'?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/blog/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top:40px !important;
        padding-bottom: 40px;
      }
      @media screen and (max-width: 480px) {
          .created-time {display:none;}
          .verycd-title {display:none;}
      }
    .mypic { border:2px solid #FFF; }
    .pick { border:2px solid #04C; }
    </style>
    <link href="/blog/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]--> 

<body>
  <div class="container">

<?php 
require '../blog/config.php';

$sql = "select * from tags";
$rows = mysql_query($sql, $conn);

echo '<div class="row">'."\n";
echo '<div style="margin-bottom:1em;">';
echo '<a href="./" style="margin-right:1em;">未分类</a>'."\n";
while ($row = mysql_fetch_array($rows)) {
    echo '<a href="?tag='.$row['tag'].'" style="margin-right:1em;">'.$row['tag'].'</a>'."\n";
} 
echo '</div>';


echo '<div class="row">'."\n";
echo '<div class="span12">'."\n";


if (isset($_REQUEST['tag'])) {
    $tag = $_REQUEST['tag'];
    $tag_id = mysql_query("select id from tags where tag='$tag'", $conn);
    $tag_id = mysql_fetch_array($tag_id);
    $tag_id = $tag_id['id']; 
    $sql = "SELECT p.* FROM tagged AS t LEFT JOIN photos AS p ON (t.rec_id=p.id) 
            WHERE t.tag_id = $tag_id ORDER BY p.id DESC LIMIT 0, 250";
} else {
    $sql = "SELECT * FROM photos WHERE is_tagged = 'no' ORDER BY ID DESC LIMIT 0, 250";
}

$big_view = 1;
if (isset($_REQUEST['big_view'])) {
    $big_view = 1;
}

$rows = mysql_query($sql, $conn);

while ($row = mysql_fetch_array($rows)) {
    $pid = $row['id'];

    if ($big_view ==1) {
        $img = '/photo/' . $row['path'].'/'.$row['file'];
        echo "<img class='mypic' alt='img' pid='$pid' onclick='pick(this);' src='/blog/img/tomtalk.png' data-original='$img' />\n"; 
        echo '<button onclick="del_img(this, '.$pid.');">删除</button><br/>'."\n";
    } else {
        $img = '/photo/'.$row['path'].'/thumb_'.$row['file'];
        $maxSize = 'max-height:180px;';
        echo "<img class='mypic' alt='img' pid='$pid' style='$maxSize' onclick='pick(this);' src='$img' />\n";
    } 
} 

?>
</div>
</div>

</div><!--end container-->

<div id="panel" style="padding:10px;background-color:#fff;border:1px solid #ddd;position:fixed;right:30px;top:30px;display:none;">
    <form style="margin:0px;">
        <input type="hidden" id="tag_id" value="<?=$tag_id?>" />
        <input type="text" id="tag" name="tag" value="" style="width:4em;" />
        <input type="button" onclick="add_tag(this);" value="打新标签" /> <br/>
        <input type="button" onclick="remove_tag();" value="删除标签" /> 
        <input type="button" onclick="big_view();" value="大图查看" />
    </form>
</div>
</body>
<script type='text/javascript' src='/blog/js/jquery.min.js'></script>
<script type='text/javascript' src='/blog/js/jquery.lazyload.min.js'></script>
<script>

$(function(){
    $("img.mypic").lazyload();
});

function pick(obj) {
    console.log($(obj).attr('class'));

    if ($(obj).attr('class') == 'mypic') {
        $(obj).addClass('pick');
        $(obj).removeClass('mypic');
    } else {
        $(obj).addClass('mypic');
        $(obj).removeClass('pick');
    }

    if ($('.pick').length > 0) {
        $('#panel').show();
    } else {
        $('#panel').hide();
    }
}

function add_tag(obj) {
    $(obj).attr('disabled','disabled');

    var pids = '';
    $('.pick').each(function(){
        pids += $(this).attr('pid') + ','; 
    });
    console.log(pids);

    var tag = $('#tag').val();
    console.log(tag);

    $.post('tag_api.php', {op:'add', tag:tag, pids:pids},function(msg){
        $(obj).removeAttr('disabled');
        if (msg == 'ok') {
            $('.pick').each(function(){
                $(this).remove(); 
            }); 
            $('#panel').hide();
            console.log('tagged');
        } else {
            console.log(msg);
        }
    }); 
}

function remove_tag() {
    var pids = '';
    $('.pick').each(function(){
        pids += $(this).attr('pid') + ','; 
    });
    console.log(pids);
    var tag_id = $('#tag_id').val();

    $.get('tag_api.php', {op:'remove',tag_id:tag_id, pids:pids},function(msg){
        if (msg == 'ok') {
            $('.pick').each(function(){
                $(this).remove(); 
            }); 
            $('#panel').hide();
            console.log('removed');
        } else {
            console.log(msg);
        }
    }); 
}

function del_img(obj, id) { 
    $.get('img_api.php', {op:'del', id:id}, function(msg){
        if (msg == 'ok') {
            //$(obj).prev().css('visibility','hidden');
            //$(obj).css('visibility','hidden');
            $(obj).prev().remove(); 
            $(obj).remove(); 
        } else {
            console.log(msg);
        }
    }); 
}


function delall() {
    var img_file = '';

    $('#btn').html('deleting...');

    $('.mypic').each(function(){
        img_file += $(this).attr('src') + ','; 
    }); 

    $.post('del_all.php', {img_file:img_file}, function(msg) { 
        if ( msg == 'ok' ) {
            window.location = '/photo';
        } else {
            console.log(msg);
        }

    }); 

}

function mvimg(obj, img_file) {
    console.log(img_file);
    return;
    $(obj).css('visibility','hidden');
    $(obj).removeClass('mypic');
    $.get('mv_img.php', {img_file:img_file}, function(msg) {
        console.log(msg);
    }); 
}
</script>
<?=$ga?>
</html>
