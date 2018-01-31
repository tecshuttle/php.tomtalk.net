<?php
require 'config.php';
require 'functions.php';

if (isset($_REQUEST['cid'])) {
    $cid = $_REQUEST['cid'];
    $sql = "delete FROM contents Where cid='$cid'";
    mysql_query($sql, $conn);
}

header('Location: /blog');

//end file