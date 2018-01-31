<?php 
if (! isset($_REQUEST['op'])) {
    echo 'op is not set';
    exit;
}

$op  = $_REQUEST['op'];

require '../blog/config.php';

if ($op == 'del') { 
    $id = $_REQUEST['id']; 

    //删除图片
    $sql = "select * from photos where id = $id";
    $rows = mysql_query($sql, $conn);
    $img = mysql_fetch_array($rows); 
    unlink('/home/wwwroot/photo/'.$img['path'].'/'.$img['file']); 
    unlink('/home/wwwroot/photo/'.$img['path'].'/thumb_'.$img['file']); 
    unlink('/home/wwwroot/photo/'.$img['path'].'/big_'.$img['file']); 

    //删除图片记录
    $sql = "delete from photos where id = $id";
    mysql_query($sql, $conn);

    //删除图片tag
    $sql = "delete from tagged where module ='photo' and rec_id = $id";
    mysql_query($sql, $conn); 

    echo 'ok';
}


//end file
