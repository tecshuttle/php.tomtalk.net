<?php    
require 'config.php'; 
require 'functions.php'; 

require('./templates/class_template.php');    
$path = './templates/';    
$tpl = & new Template($path);    
if ( isset($_REQUEST['cid'])) {
    $cid = $_REQUEST['cid']; 
    $sql  = "SELECT * FROM contents Where cid='$cid'";
    $rows = mysql_query($sql, $conn); 
    $blog = mysql_fetch_array($rows); 
    $tpl->set('blog', $blog);    

    $sql  = "SELECT * FROM tags WHERE module='blog'";
    $rows = mysql_query($sql, $conn); 
    $tpl->set('tags', $rows);    

    $sql  = "SELECT * FROM tagged WHERE rec_id = $cid AND module='blog'";
    $rows = mysql_query($sql, $conn); 
    $row = mysql_fetch_array($rows); 
    $tpl->set('blog_tag', $row);    
}  

echo $tpl->fetch('edit_blog.tpl.php');    

//end file
