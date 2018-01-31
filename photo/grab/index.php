<!doctype html>
<html>
<head>
<title>photo</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>

<body>
<?php 

$fold = $_REQUEST['dir'] ? $_REQUEST['dir'] : 'img2';
$dir = opendir($fold);
$i=1;
while ($fileName=readdir($dir)) {
    if ($fileName!='.' && $fileName!='..' ) {
        if (strstr($fileName, 'thumb_')) {
            if ($i++ >250 and $fold == 'img2' ) break;

            if ($fold == 'img9') {
                echo "<a href='$fold/".str_replace('thumb_','',$fileName)."'><img style=\"max-height:200px;\" src='$fold/$fileName' /></a>";
            } else if ($fold == 'imga') {
                echo "<a href='$fold/".str_replace('thumb_','',$fileName)."'><img src='$fold/$fileName' /></a>";
                echo '<button onclick="delimg(this,\''.$fold.'/'.$fileName.'\');">del</button>'; 
            } else {
                echo "<img class='mypic' alt='img' style=\"max-height:180px;\" ";
                echo "onclick=\"mvimg(this,'".str_replace('thumb_','',$fileName)."');\" ";
                echo " src='$fold/$fileName' />";
            }
        }
    } 
}

closedir($dir);

if ($fold == 'img2') {
   echo '<button style="float:right;position:fixed;right:30px;bottom:30px;width:100px;height:50px;" onclick="delall();" id="btn">del all</button>';
}
?>

</body>
<script type='text/javascript' src='/blog/js/jquery.min.js'></script>
<script>
function delimg(obj, img_file) {
    console.log(img_file);

    $.get('del_img.php', {img_file:img_file}, function(msg) {
        console.log(msg);
        $(obj).css('visibility','hidden');
        $(obj).prev().css('visibility','hidden');
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
            window.location = '/photo/grab';
        } else {
            console.log(msg);
        }

    }); 

}

function mvimg(obj, img_file) {
    $.get('mv_img.php', {img_file:img_file}, function(msg) {
        console.log(msg);
        $(obj).css('visibility','hidden');
        $(obj).removeClass('mypic');
    }); 
}
</script>
</html>
