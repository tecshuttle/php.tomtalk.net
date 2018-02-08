<?php
include 'auth.php';
include 'blog/config.php';

header("Access-Control-Allow-Origin: *");
mysqli_select_db($conn, 'memorize') OR die(1);

$uid = get_uid();

// get user all types 
$t_sql = "SELECT id, name, color FROM item_type WHERE uid = $uid AND priority !=0";
$type_rows = mysqli_query($conn, $t_sql);

$type = array();
while ($row = mysqli_fetch_array($type_rows)) {
    array_push($type, array('type_id' => $row['id'], 'type' => $row['name'], 'color' => $row['color']));
}

// get user used types
$q_sql = "SELECT COUNT(1) AS count, q.type_id, t.name AS type, t.color FROM questions AS q LEFT JOIN item_type AS t ON (q.type_id = t.id) "
    . "WHERE q.uid = $uid AND t.uid = $uid AND t.priority !=0 GROUP BY q.type_id ORDER BY count DESC";
$question_rows = mysqli_query($conn, $q_sql);


$data = array();
while ($row = mysqli_fetch_array($question_rows)) {
    array_push($data, $row);
}

// add unused type into data array
foreach ($type as $t) {

    $used = false;

    foreach ($data as &$d) {
        if ($d['type_id'] === $t['type_id']) {
            $used = true;
        }
    }

    if (!$used) {
        $t['count'] = 0;
        array_push($data, $t);
    }
}

header("Access-Control-Allow-Origin: *");

echo json_encode($data);

//end file
