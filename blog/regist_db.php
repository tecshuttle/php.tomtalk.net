<?php    
require 'config.php'; 
require 'functions.php'; 

$name     = $_REQUEST['name']; 
$password = md5($_REQUEST['password']); 

$sql  = "INSERT INTO users (name,password) VALUES ('$name','$password')";
$rows = mysql_query($sql); 

session_start();

$_SESSION['uid']  = mysql_insert_id();
$_SESSION['name'] = $name;

header('location:./');

//end file
