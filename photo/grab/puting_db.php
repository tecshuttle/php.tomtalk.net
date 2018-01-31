<?php 
require '../../blog/config.php'; 

$fold = $_REQUEST['dir'] ? $_REQUEST['dir'] : 'img9';
$dir = opendir($fold);

while ($fileName=readdir($dir)) {
    if ($fileName!='.' && $fileName!='..' ) {
        if (! strstr($fileName, 'thumb_')) { 
            $path = "201212";
            $sql  = "INSERT INTO photos (path,file) VALUES ('$path','$fileName')";
            echo "$fileName ";
            mysql_query($sql, $conn);
        }
    } 
}

closedir($dir);


