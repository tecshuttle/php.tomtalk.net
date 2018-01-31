<?php
//$output = array();
//
//exec("df -h", $output);
//
//echo "<pre>";
//foreach ($output as $k=>$line) {
//    echo $line. "\n";
//}
//echo "</pre>";

$lines = file('disk_usage.log');

//print_r($lines);

echo json_encode($lines); 

//end file
