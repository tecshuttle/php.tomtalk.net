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

//end file
