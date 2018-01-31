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
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 20);  //设置超时为5秒 
    $data = curl_exec($curl); 
    curl_close($curl);

    return $data; 
}

/**
 * 抓取网页内容
 */
function tom_get_site_content($site_url) { 
    //$data = file_get_contents($site_url); 
    //$data = file($site_url); 
    //$data = readfile($site_url); 
    
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)"); 
    curl_setopt($curl, CURLOPT_URL, $site_url); 
    curl_setopt($curl, CURLOPT_HEADER, FALSE); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 20);  //设置超时为5秒 
    $data = curl_exec($curl); 
    //var_dump($data);
    
    curl_close($curl);
    return $data; 
}


/**
 * 取得关键字
 */
function get_keywords($web_content) { 
    preg_match_all('/<meta(.*?)>/is', $web_content, $meta);
    for ($i=0; $i<count($meta[0]); $i++) {
        if (preg_match('/keywords/i', $meta[0][$i])) {
            preg_match_all('/content=\"(.*?)\"/is', $meta[0][$i], $keywords);
        }
    }

    if ( is_array($keywords)) {
        return $keywords[1]; 
    } else {
        return FALSE;
    }
}

/**
 * 对图片链接进行修正 
 */
function revise_site($site_list, $base_site) {
    foreach ($site_list as $site_item) {
        if (preg_match('/^http/', $site_item)) {
            $return_list[] = $site_item;
        } else {
            $return_list[] = $base_site . '/' . $site_item;
        }
    }
    return $return_list;
} 

/**
 * 排序并去除重复图片
 */
function img_sort($img_url_revised) {
    $img_unique = array_unique($img_url_revised);

    sort($img_unique);

    return $img_unique;
} 

$i = 0;
$pids = array();
function grab_img_from_page($page_url) { 
    global $i;
    global $pids;
    global $forum;
    if ($forum == 8) {
        $web_content = tom_get_site_content($page_url);            //抓取网页内容 
    } else {
        $web_content = get_site_content($page_url);            //抓取网页内容 
        if ( strlen($web_content) == 0) {
            $web_content = tom_get_site_content($page_url);            //抓取网页内容 
        }

        if ( strlen($web_content) == 0) {
            $web_content = file_get_contents($page_url);            //抓取网页内容 
        }
    } 

    $img_url = get_img_url($web_content); 

    $filename = mt_rand(0,99999);
    foreach ($img_url as $img) {
        $i++;
        $ext = substr($img, strlen($img)-4);
        $j = pcntl_fork();/// 产生子进程
        $pids[] = $j;
        if ( ! $j) { 
            $s=getData($img); 
            file_put_contents('img/'.$filename.$i.$ext, $s);
            //echo "{$img}\n";
            echo "$i ";
            exit;
        }

    } 

    if (count($pids) > 50) { 
        echo "\n\n";
        foreach ($pids as $pid) {
            pcntl_waitpid($pid, $status, WUNTRACED);
        }
        $pids = array();
    }
}

function  getData($url){
    return tom_get_site_content($url);
    ob_start();
    readfile($url);
    $data = ob_get_contents();
    ob_end_clean();
    return $data;
}


/**
 * 取得网页图片
 */
function get_img_url($web_content) { 
    global $forum;

    //利用正则表达式得到图片链接
    if ($forum == 8) {
        $reg_tag = '/<input type=\'image\' src=\'([^\']*jpg)\'.*?>/';
    } else {
        $reg_tag = '/<img src=\'([^\']*jpg)\' onclick/';
    }
    $ret = preg_match_all($reg_tag, $web_content, $match_result);
    
    return $match_result[1]; 
}

/**
 * 取得页面里，贴子的URL
 */
function get_page_url($web_content) { 
    //利用正则表达式得到图片链接
    $reg_tag = '/<h3><a href=\"(.*html)\" target=.*<\/h3>/';
    $ret = preg_match_all($reg_tag, $web_content, $match_result);
    
    return $match_result[1]; 
}


function get_page_url_from_post_page($post_page_url) { 
    global $forum;
    if ($forum ==8) {
        $web_content = tom_get_site_content($post_page_url);      //抓取网页内容 
    } else {
        $web_content = file_get_contents($post_page_url);      //抓取网页内容 
    }
    $urls = get_page_url($web_content); 

    foreach ($urls as &$url) {
        $url = 'http://c1521.amlong.info/'.$url;
    }

    return $urls; 
}

$post_page_url = 'http://c1521.biz.tm/thread0806.php?fid=8'; //自拍
$post_page_url = 'http://c1521.biz.tm/thread0806.php?fid=7'; //技术讨论区
$forum = 7;

//echo tom_get_site_content($post_page_url); exit;

for ($page = 1; $page < 50; $page++) {
    if ($forum == 8) {
        $post_page_url = 'http://c1521.biz.tm/thread0806.php?fid='.$forum.'&page='.$page; 
    } else {
        $post_page_url = 'http://cl.tedx.ee/thread0806.php?fid='.$forum.'&search=&page='.$page; 
        $post_page_url = 'http://c1521.amlong.info/thread0806.php?fid='.$forum.'&search=&page='.$page; 
    }
    echo $post_page_url."\n\n";
    $urls = get_page_url_from_post_page($post_page_url);//print_r($urls);continue;
    foreach ($urls as $url) { 
        grab_img_from_page($url);
    }
}

//end  file 
