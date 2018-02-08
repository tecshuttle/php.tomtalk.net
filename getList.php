<?php
include 'auth.php';
include 'blog/config.php';

header("Access-Control-Allow-Origin: *");
mysqli_select_db($conn, 'memorize') OR die(1);

$uid = get_uid();

$item_type = $_REQUEST['item_type'];

$today = date("Y-m-d", time());
$week_before = time() - (7 * 24 * 3600);

if ($item_type == 'active') {
    $sql = "SELECT t.name AS type, t.priority, q.* FROM questions AS q LEFT JOIN item_type AS t ON (q.type_id = t.id) "
        . "WHERE q.uid = $uid AND ((t.priority = 0 AND next_play_date <= '$today') OR (t.priority > 0 AND mtime > $week_before)) "
        . "ORDER BY t.priority ASC, id ASC";

} else if ($item_type == 'archive') {
    $sql = "SELECT t.name AS type, t.priority, q.* FROM questions AS q LEFT JOIN item_type AS t ON (q.type_id = t.id) "
        . "WHERE q.uid = $uid AND ((t.priority = 0 AND next_play_date > '$today') OR (t.priority > 0 AND mtime <= $week_before)) "
        . "ORDER BY t.priority ASC, id ASC";
} else if ($item_type == 'search') {
    $keyword = $_REQUEST['keyword'];
    $sql = "SELECT t.name AS type, t.priority, q.* FROM questions AS q LEFT JOIN item_type AS t ON (q.type_id = t.id) "
        . "WHERE q.uid = $uid AND (q.question LIKE '%$keyword%' OR q.answer LIKE '%$keyword%') ";
} else {
    $sql = "SELECT t.name AS type, t.priority, q.* FROM questions AS q LEFT JOIN item_type AS t ON (q.type_id = t.id) "
        . "WHERE t.name = '$item_type' AND q.uid = $uid";
}


$rows = mysqli_query($conn, $sql);

$data = array();
while ($row = mysqli_fetch_array($rows)) {
    array_push($data, $row);
}

echo json_encode($data);

//end file
