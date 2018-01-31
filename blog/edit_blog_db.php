<?php
require 'config.php';
require 'functions.php';

$cid = $_REQUEST['cid'];
$title = $_REQUEST['title'];
$content = $_REQUEST['content'];
$tag = $_REQUEST['tag'];
if (empty($title)) {
    exit('文章标题不能为空');
}

$is_tagged = ($tag == '' ? 'no' : 'yes');


session_start();
$uid = $_SESSION['uid'];

if ($cid == '') {
    $created = time();
    $sql = "INSERT INTO contents (uid, title, text, created, score, is_tagged) VALUES ($uid, '$title','$content', $created, 9999, '$is_tagged')";
    mysql_query($sql, $conn);
    $cid = mysql_insert_id();
} else {
    $modified = time();
    $sql = "update contents set title = '$title', text = '" . mysql_real_escape_string($content) . "', is_tagged = '$is_tagged', modified = $modified WHERE cid = $cid";
    mysql_query($sql, $conn);
}

$sql = "DELETE FROM tagged WHERE module = 'blog' AND rec_id = $cid";
mysql_query($sql, $conn);

if ($cid != '') {
    $sql = "INSERT INTO tagged (tag_id,module,rec_id) VALUES ($tag,'blog',$cid)";
    mysql_query($sql, $conn);
}

header('location:./blog.php?title=' . $title);

//end file
