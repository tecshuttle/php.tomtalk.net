<?php 
if (! isset($_REQUEST['op'])) {
    echo 'op is not set';
    exit;
}

$op  = $_REQUEST['op'];

require '../blog/config.php';

if ($op == 'add') { 
    $pids = $_REQUEST['pids'];
    $pids = substr($pids, 0 , strlen($pids) -1);
    $pids = explode(',', $pids);
    if (! isset($_REQUEST['tag'])) {
        echo 'tag is not set';
        exit;
    }

    $tag  = $_POST['tag'];
    if ($tag=='') {
        echo 'tag is empty';
        exit;
    }

    $sql = "SELECT * FROM tags WHERE tag = '$tag'";
    $rows = mysql_query($sql, $conn);
    if (mysql_num_rows($rows) ==0) {
        $sql = "insert into tags (tag) values ('$tag')";
        mysql_query($sql, $conn);
        $tag_id = mysql_insert_id(); 
    } else {
        $row = mysql_fetch_array($rows);
        $tag_id = $row['id']; 
    }

    foreach ($pids as $pid) {
        $sql = "insert into tagged (module,tag_id, rec_id) values ('photo',$tag_id, $pid)";
        mysql_query($sql, $conn); 
    } 

    echo 'ok';
} if ($op == 'remove') {
    $pids = $_REQUEST['pids'];
    $pids = substr($pids, 0 , strlen($pids) -1);

    $tag_id = $_REQUEST['tag_id'];

    $sql = "delete from tagged where module='photo' and tag_id = $tag_id and rec_id in ($pids)";
    //echo $sql;
    mysql_query($sql, $conn); 

    echo 'ok';
}


//end file
