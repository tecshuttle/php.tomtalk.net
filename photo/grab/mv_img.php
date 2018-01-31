<?php
$img_file = $_REQUEST['img_file'];
echo $img_file;
copy('img2/'.$img_file, 'img9/'.$img_file);
unlink('img2/'.$img_file);
unlink('img2/thumb_'.$img_file);
