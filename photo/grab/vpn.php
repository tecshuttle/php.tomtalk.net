<?php
set_time_limit(0);

/**
 * 抓取网页内容
 */
function get_site_content($site_url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $site_url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
    curl_setopt($curl, CURLOPT_UNRESTRICTED_AUTH, 1);



     //curl_setopt($curl, CURLOPT_HEADER, FALSE);
     curl_setopt($curl, CURLOPT_TIMEOUT, 20);  //设置超时为5秒 
     $data = curl_exec($curl);
     curl_close($curl);
 
     return $data;
}

/**
 * 抓取网页内容
 */
function tom_get_site_content($site_url) { 
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)"); 
    curl_setopt($curl, CURLOPT_URL, $site_url); 
    curl_setopt($curl, CURLOPT_HEADER, FALSE); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 20);  //设置超时为5秒 
    $data = curl_exec($curl); 
    
    curl_close($curl);
    return $data; 
} 

$url = $_REQUEST['url'];//'http://c1521.biz.tm/thread0806.php?fid=8'; //自拍
$url = 'http://c1521.biz.tm/thread0806.php?fid=7'; 
$url = 'http://x2.sha7.info/index.php?u=80058';
$url = 'http://cl.tedx.ee/thread0806.php?fid=7&search=&page=1';
$url = 'http://c1521.amlong.info/htm_data/7/1211/831281.html';

print_r( get_site_content($url)); 
//print_r( tom_get_site_content($url)); 
//print_r( file_get_contents($url)); 


//end  file 
