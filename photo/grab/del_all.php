<?php
$img_file = $_REQUEST['img_file'];
$img_file = substr($img_file,0,strlen($img_file)-1);
$img_file = explode(',',$img_file);

foreach ($img_file as $img) {
    if (! unlink($img)) echo $img;
    if (! unlink(str_replace('thumb_','',$img))) echo $img;
}

echo 'ok';
