<?php
function get_uid() {
    $uid = 0;

    if (isset($_COOKIE['uid'])) {
        $uid = $_COOKIE['uid']; 
    } else if ($_REQUEST['devMode'] === 'true') {
        $uid = 1; 
    } 

    return $uid;
} 

//end file
