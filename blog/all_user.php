<?php
require 'config.php'; 
require 'functions.php'; 

require('./templates/class_template.php');    
$path = './templates/';    
$tpl = & new Template($path);    

session_start(); 

$sql  = "SELECT * FROM users";

$users = mysql_query($sql, $conn);

$tpl->set('users', $users);    

$tpl->set('ga', $ga);  //google analytics   
echo $tpl->fetch('all_user.tpl.php');    

//end file
