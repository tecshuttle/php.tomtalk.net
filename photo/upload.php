<?php 
//上传文件类型列表
$uptypes=array('image/jpg',
    'image/jpeg',
    'image/png',
    'image/pjpeg',
    'image/gif',
    'image/bmp',
    'application/x-shockwave-flash',
    'image/x-png',
    'application/msword',
    'audio/x-ms-wma',
    'audio/mp3',
    'application/vnd.rn-realmedia',
    'application/x-zip-compressed',
    'application/octet-stream');

$max_file_size      = 5*1024*1024;                    // 上传文件大小限制, 单位BYTE
$path_parts         = pathinfo($_SERVER['PHP_SELF']); // 取得当前路径
$destination_folder = date('Ym', time());             // 上传文件路径
$imgpreview         = 1;                              // 是否生成预览图(1为生成,0为不生成);
$imgpreviewsize     = 1/4;                            // 缩略图比例

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //是否存在文件
    if (!is_uploaded_file($_FILES["upfile"]['tmp_name'])) {
        echo "<font color='red'>文件不存在！</font>"; exit;
    }

    $file = $_FILES["upfile"];

    //检查文件大小
    if($max_file_size < $file["size"]) {
        echo "<font color='red'>文件太大！</font>"; exit;
    }

    //检查文件类型
    if(!in_array($file["type"], $uptypes)) {
        echo "<font color='red'>不能上传此类型文件！</font>"; exit;
    }

    if ( ! file_exists($destination_folder)) {
        mkdir($destination_folder);
    }

    $filename    = $file["tmp_name"];
    $image_size  = getimagesize($filename);
    $pinfo       = pathinfo($file["name"]);
    $ftype       = $pinfo['extension'];

    $newfile = microtime();
    $_pos    = strpos($newfile, ' ');
    $newfile = substr($newfile, $_pos+1) . '_' . substr($newfile, 0, $_pos);

    $destination = $destination_folder .'/'. $newfile . '.' . $ftype;

    if(!move_uploaded_file ($filename, $destination)) {
        echo "<font color='red'>移动文件出错！</a>"; exit; 
    }

    $pinfo = pathinfo($destination);
    $fname = $pinfo['basename'];

    if($imgpreview==1) {
        $w =$image_size[0]*$imgpreviewsize;
        $h =$image_size[1]*$imgpreviewsize;

        echo "<a href='$destination' target='_blank'>"; 
        echo "<img src='$destination' width='$w' height='$h' border='0'>";
        echo '</a>';
    }

    require 'func_img.php'; 
    img_thumb($destination_folder, $newfile . '.' . $ftype);

    require '../blog/config.php'; 
    $sql  = "INSERT INTO photos (path,file) VALUES ('$destination_folder','$newfile.$ftype')";
    mysql_query($sql, $conn);
}
?>
<html>
<head>
<meta charset="utf-8">
<title>图片上传</title>
</head>

<body>
    <form enctype="multipart/form-data" method="post" name="upform">
    <input size="17" name=upfile type=file>
    <input type="submit" value="上传">
    </form> 
</body>
</html>
