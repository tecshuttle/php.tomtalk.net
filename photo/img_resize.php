<?php

function img_thumb($fold, $orig_img) {

    if ( ! file_exists($fold.'/'.$orig_img) ) { 
        //echo "$orig_img not exist! ";
        return false;
    }

    if (file_exists($fold.'/thumb_'.$orig_img) ) {
        //echo "thumb exist! ";
        return false;
    }

    $size        = getimagesize($fold.'/'.$orig_img);
    if (! $size) {
        return;
    }

    $thumbWidth  = $size[0]/4;
    $thumbHeight = $size[1]/4; 

    $source = imagecreatefromjpeg($fold.'/'.$orig_img);

    $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
    imagecopyresampled($thumb,$source,0,0,0,0,$thumbWidth,$thumbHeight,$size[0],$size[1]);

    imagejpeg($thumb, $fold.'/thumb_'.$orig_img);
    imagedestroy($thumb);//Release the image source
    imagedestroy($source);
}

function img_big($fold, $orig_img) {
    define('SIZE', 800);

    if ( ! file_exists($fold.'/'.$orig_img) ) { 
        //echo "$orig_img not exist! ";
        return false;
    }

    if (file_exists($fold.'/big_'.$orig_img) ) {
        //echo "thumb exist! ";
        return false;
    }

    $size = getimagesize($fold.'/'.$orig_img);
    if (! $size) {
        return;
    }

    if ($size[0] <= SIZE) {
        $Width  = $size[0];
        $Height = $size[1]; 
    } else { 
        $rate = SIZE / $size[0];
        $Width  = $size[0] * $rate;
        $Height = $size[1] * $rate; 
    }

    $source = imagecreatefromjpeg($fold.'/'.$orig_img);

    $thumb = imagecreatetruecolor($Width, $Height);
    imagecopyresampled($thumb,$source,0,0,0,0,$Width,$Height,$size[0],$size[1]);

    imagejpeg($thumb, $fold.'/big_'.$orig_img);
    imagedestroy($thumb);//Release the image source
    imagedestroy($source);
}

$fold = $argv[1] ? $argv[1] :'img2';
$dir=opendir($fold);

while ($fileName=readdir($dir)) {
    if ($fileName!='.' && $fileName!='..' ) {
        if (! strstr($fileName, 'thumb_') AND ! strstr($fileName, 'big_')) {
            img_thumb($fold, $fileName);
            img_big($fold, $fileName);
            echo $fileName . "\n";
        }
    } 
}


//end file
