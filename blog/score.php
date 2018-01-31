<?php
require 'config.php'; 
require 'functions.php'; 

$sql  = 'SELECT t.cid, c.created,COUNT(t.uid) AS readerNum, SUM(second) AS readingTime FROM reading_time AS t '
      . 'LEFT JOIN contents AS c ON (t.cid = c.cid) GROUP BY cid'; 

$rows = mysql_query($sql, $conn); 

$now = time();

while ($blog = mysql_fetch_array($rows)) { 
    $cid = $blog['cid'];
    $readerNum = $blog['readerNum'];
    $readingTime = $blog['readingTime'];
    
    $bonus = (100 - log(($now - $blog['created'])/60/60)*10);
    $score = ceil(($readerNum * 10) + ($readingTime / 60) + $bonus);

    $sql  = "UPDATE contents SET readerNum = $readerNum, readingTime = $readingTime, score = $score WHERE cid = $cid"; 
    echo '<p>'. $sql;
    mysql_query($sql, $conn); 
} 

//end file
