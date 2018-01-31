<?php
require 'config.php'; 
require 'functions.php'; 

$name = $_POST['name'];

$sql = "SELECT * FROM users WHERE name = '$name'";
$rows = mysql_query($sql, $conn);
if (mysql_num_rows($rows) == 0) {
    echo 'true';
} else {
    echo 'false';
}

//end file
