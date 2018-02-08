<?php
include 'blog/config.php';
mysqli_select_db($conn, 'memorize') OR die(1);

//只插入偶数记录
mysqli_query($conn, "SET @@auto_increment_offset=2");
mysqli_query($conn, "SET @@auto_increment_increment=2");

if (isset($_COOKIE['uid'])) {
    $uid = $_COOKIE['uid'];
} else {
    if ($_REQUEST['devMode'] === 'true') {
        $uid = 1;
    } else {
        echo json_encode(array('ret_code' => -1, 'msg' => 'no login'));
        exit;
    }
}

//获取post参数
$id = $_REQUEST['id'];
$type_id = $_REQUEST['type_id'];
$question = $_REQUEST['question'];
$answer = $_REQUEST['answer'];
$explain = $_REQUEST['explain'];
$sync_state = $_REQUEST['sync_state'];
$mtime = isset($_REQUEST['mtime']) ? $_REQUEST['mtime'] : '';

//id为0则新增记录
$today = date('Y-m-d', time());
$time = time();

if (!empty($mtime)) {
    $sql = "UPDATE questions SET mtime = $mtime, sync_state = 'modify' WHERE `id` = $id AND uid = $uid";
} else if ($id == 0) {
    $sql = "INSERT INTO questions (uid, question, answer, `explain`, type_id, next_play_date, mtime) "
        . "VALUES ($uid, '$question', '$answer', '$explain', $type_id, '$today', $time)";
} else {
    $sql = "UPDATE questions SET "
        . "question = '$question', answer = '$answer', `explain` = '$explain', type_id = $type_id, mtime = $time "
        . ($sync_state == 'add' ? '' : " , sync_state = 'modify' ")
        . "WHERE `id` = $id AND uid = $uid";
}

$db_result = mysqli_query($conn, $sql);

$rec_id = mysqli_insert_id($conn);

$result = array(
    'sql' => $sql,
    'id' => ($rec_id == 0 ? $id : $rec_id),
    'ret' => true
);

header("Access-Control-Allow-Origin: *");

echo json_encode($result);

//end file
