<?php
require 'config.php';
require 'functions.php';

$cid = $_REQUEST['cid'];
$sec = $_REQUEST['sec'];

$sql = "UPDATE contents SET readingTime = readingTime + $sec WHERE cid = $cid";

$query = mysql_query($sql);

echo json_encode(array(
    'success' => $query,
    'sql' => $sql
));

//end file