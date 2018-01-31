<?php
require 'config.php';
require 'functions.php';

require('./templates/class_template.php');
$path = './templates/';
$tpl = new Template($path);

$uid = $_REQUEST['uid'];

//�û���Ϣ
$sql = "SELECT * FROM users where uid = $uid";
$rows = mysqli_query($conn, $sql);
$user = mysqli_fetch_array($rows);
$tpl->set('user', $user);

//����������
$sql = "SELECT c.cid, c.title, t.second FROM reading_time as t left join contents as c on (t.cid = c.cid) where t.uid = $uid";
$rows = mysqli_query($conn, $sql);
$tpl->set('read_blog_list', $rows);

$tpl->set('ga', $ga);  //google analytics   
echo $tpl->fetch('user.tpl.php');

//end file
