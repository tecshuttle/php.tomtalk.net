<?php
$img_file = $_REQUEST['img_file'];
echo $img_file;
if (unlink(str_replace('thumb_','',$img_file))) echo ' 1';
if (unlink($img_file)) echo ' 1';
