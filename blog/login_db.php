<?php
require 'config.php';
require 'functions.php';

$name = $_REQUEST['name'];
$password = $_REQUEST['password'];
$return = $_REQUEST['return'];

$sql = "SELECT uid, password FROM users WHERE name='$name'";
$rows = mysqli_query($conn, $sql);
$user = mysqli_fetch_array($rows);

if ($user['password'] === md5($password)) {
    session_start();
    $_SESSION['name'] = $name;
    $_SESSION['uid'] = $user['uid'];

    $expire = time() + 30 * 86400; // 30 day
    setcookie('name', $name, $expire, '/');
    setcookie('uid', $user['uid'], $expire, '/');
    if ($return == '') {
        header('location:./');
    } else {
        header('location:' . $return);
    }
} else {
    header('location:./login.php');
}



//end file
