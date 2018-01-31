<?php    
require 'config.php'; 
require 'functions.php'; 

require('./templates/class_template.php');    
$path = './templates/';    
$tpl = & new Template($path);    

$tpl->set('ga', $ga);  //google analytics   

session_start(); 
$tag = '';
if (strstr($_REQUEST['title'], 'tag/')) {
    $uri = explode('/',$_REQUEST['title']);
    $tag = $uri[1]; 
    $tpl->set('tag', $tag);    
    $_REQUEST['title'] = '';
} 


if ( $_REQUEST['title'] == '') {

    //blog list 
    $perpage = 20;
    $page = $_REQUEST['pg'] ? $_REQUEST['pg'] : 1;

    if ($tag == '') {
        $sql  = "SELECT cid, score, u.name, title, FROM_UNIXTIME(c.created) AS created "
              . "FROM contents AS c LEFT JOIN users AS u ON (c.uid=u.uid) WHERE  is_tagged = 'no' "
              . "ORDER BY score DESC, created DESC" . build_limit($page, $perpage); 
        //Sum of blog
        $sql_t  = "SELECT count(*) as total FROM contents AS c LEFT JOIN users AS u ON (c.uid=u.uid) WHERE  is_tagged = 'no'";
        $rows  = mysql_query($sql_t, $conn);
        $total = mysql_fetch_array($rows); 
    } else {
        $sql = "SELECT cid, score, title, FROM_UNIXTIME(created) AS created FROM contents "
             . "WHERE cid IN (SELECT rec_id FROM tagged  WHERE module = 'blog' AND tag_id IN "
             . "(SELECT id FROM tags WHERE module='blog' AND tag='$tag' )) ORDER BY score DESC, created DESC ". build_limit($page, $perpage);
        //Sum of blog
        $sql_t = "SELECT count(*) as total FROM contents "
             . "WHERE cid IN (SELECT rec_id FROM tagged  WHERE module = 'blog' AND tag_id IN "
             . "(SELECT id FROM tags WHERE module='blog' AND tag='$tag' )) ";
        $rows  = mysql_query($sql_t, $conn);
        $total = mysql_fetch_array($rows); 
    }

    $rows = mysql_query($sql, $conn); 

    $url  = '?pg=__page__';

    $pagebar = build_pagebar($total['total'], $perpage, $page, $url); 

    $tpl->set('title', 'tomtalk');    
    $tpl->set('total', $total);    

    $tpl->set('rows', $rows);    
    $tpl->set('pagebar', $pagebar);    

    $sql   = "SELECT tags.id,tags.tag, COUNT(tags.id) AS count FROM tagged LEFT JOIN tags ON (tagged.tag_id=tags.id) "
           . "WHERE tagged.module = 'blog' GROUP BY tags.id ORDER BY count DESC"; 
    $rows  = mysql_query($sql, $conn);
    $tpl->set('tags', $rows);    

    echo $tpl->fetch('index.tpl.php');    
} else { 
    require_once("wiky.inc.php");

    $wiky = new wiky; 
    $sql  = "SELECT * FROM contents Where title='" . $_REQUEST['title'] . "'";
    $rows = mysql_query($sql, $conn); 
    $blog = mysql_fetch_array($rows); 

    $blog['text'] = $wiky->parse($blog['text']);    
    $lang_js = array( 'css'=>'Css','plain'=>'Plain','sql'=>'Sql','html'=>'Xml','php'=>'Php', 'bash'=>'Bash', 'js'=>'JScript');
    foreach ($wiky->lang as &$lang) {
        $lang = $lang_js[$lang]; 
    }

    $tpl->set('lang_js', $wiky->lang);    
    $tpl->set('blog', $blog);    

    //$tpl->set('blog_keyword', get_blog_keyword($blog['text']));   //取文章内的关键字 

    $desc = strip_tags(mysubstr($blog['text'], 0, 200));       
    $desc = preg_replace('/((\s)*(\n)+(\s)*)/i','',$desc);   //去除换行、空行
    $tpl->set('desc', $desc);    

    echo $tpl->fetch('blog.tpl.php');    
}

function get_blog_keyword($str) {
    require_once "lib_splitword_full.php";

    $sp = new SplitWord();
    $words = $sp->SplitRMM($str);
    $sp->Clear();

    $words_array = explode(' ', $words);
    $key_word = array();
    $del_word = array('的','是');

    foreach ($words_array as $key => $word) {

        if (in_array($word, $del_word)) {
            continue;
        }

        if (strlen($word)< 6) {
            continue;
        } 

        if (isset($key_word[$word])) {
            $key_word[$word] ++; 
        } else {
            $key_word[$word] = 1; 
        }
    }
    arsort($key_word);
    return ($key_word);
}
//end file
