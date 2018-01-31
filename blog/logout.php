<?php    

session_start();
session_destroy();

setcookie('name', '', time() -1, '/');
setcookie('uid', '', time() -1, '/');


if (isset($_REQUEST['return'])) {
    header('location:' . $_REQUEST['return']);
} else {
    header('location:./');
}

//end file
