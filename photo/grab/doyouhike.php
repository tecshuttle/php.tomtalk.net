<?php
set_time_limit(0);

/**
 * ץȡ��ҳ����
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
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);  //���ó�ʱ
    $data = curl_exec($curl); 
    curl_close($curl);

    return $data; 
}

/**
 * ץȡ��ҳ����
 */
function tom_get_site_content($site_url) { 
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)"); 
    curl_setopt($curl, CURLOPT_URL, $site_url); 
    curl_setopt($curl, CURLOPT_HEADER, FALSE); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);  //���ó�ʱΪ
    $data = curl_exec($curl); 
    
    curl_close($curl);
    return $data; 
}

$i = 0;
$pids = array();
$filename = date('Hs',time());

function grab_img_from_page($page_url) { 
    global $i;
    global $pids;
    global $filename;

    $web_content = tom_get_site_content($page_url);            //ץȡ��ҳ���� 
    $img_url = get_img_url($web_content); 
    foreach ($img_url as $img) {
        //�ж���һ����ȷ��ͼƬ��ַ
        if (! strstr($img, 'http://')) {
            continue;
        }

        $i++;
        $ext = substr($img, strlen($img)-4);
        $j = pcntl_fork();/// �����ӽ���
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
 * ȡ����ҳͼƬ
 */
function get_img_url($web_content) { 
    //<img class="attach" src="http://static.doyouhike.net/files/2012/07/31/d/dc7387ef6c5457552fdc98ba57760b92.jpg" alt='' border="0" onload='if (this.width > 800) this.width=800'/>

    //����������ʽ�õ�ͼƬ����
    $reg_tag = '/<img class=\"attach\" src=\"([^\']*jpg)\" alt/';
    $ret = preg_match_all($reg_tag, $web_content, $match_result);
    
    return $match_result[1]; 
}

/**
 * ȡ��ҳ������ӵ�URL
 */
function get_page_url($web_content) { 
    //����������ʽ�õ�ͼƬ����
    //$reg_tag = '/<a href=\"(.*html)\" target=.*<\/h3>/';

    //<a href="314215,0,0,0.html" class="topic">��������أ�������ʯ���ҹ�԰���������</a>
    $reg_tag = '/<a href=\"(.*html)\" class=\"topic\">/';
    $ret = preg_match_all($reg_tag, $web_content, $match_result);
    
    return $match_result[1]; 
}


function get_url_from_post($url) { 
    global $post_url;

    $web_content = tom_get_site_content($url);      //ץȡ��ҳ���� 
    $urls = get_page_url($web_content); 
    foreach ($urls as &$url) {
        $url = $post_url.$url;
    }

    return $urls; 
}

//echo tom_get_site_content($post_page_url); exit;

for ($page = 11; $page < 100; $page++) {
    $post_url = "http://www.doyouhike.net/forum/photo/$page/"; 
    $post_url = "http://www.doyouhike.net/forum/gear/$page/"; 
    echo $post_url."\n\n";

    $urls = get_url_from_post($post_url); 

    foreach ($urls as $url) { 
        grab_img_from_page($url);
    }
}

//end  file 
