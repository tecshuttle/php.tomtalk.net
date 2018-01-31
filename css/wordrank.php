<html>
<head>
    <meta http-equiv=Content-Type content="text/html;charset=utf-8">
</head>
<body>

<?php
$start = microtime(true); //开始计时

//$reg_word = '/\w+(-\r?\n?\w+)?/';
$reg_word = '/[a-zA-Z]{4,}+(-\r?\n?\w+)?/';

//测试正测取值范围，去除包含数字的词
if (false) {
    $lines = file('test_word.txt');

    $new_words = array();

    foreach ($lines as $line) {
        if (preg_match_all($reg_word, $line, $matches)) {
            $words = $matches[0];

            foreach ($words as $word) {
                $word = strtolower($word);

                if (isset($old_words[$word])) {
                    continue;
                }

                if (isset($new_words[$word])) {
                    $new_words[$word]++;
                } else {
                    $new_words[$word] = 1;
                }
            }
        }
    }

    print_r($new_words);
    exit;
}


//读取已熟知单词
$old_words = array();
$lines = file('old_word.txt');

foreach ($lines as $line) {
    if (preg_match_all($reg_word, $line, $matches)) {
        $words = $matches[0];

        foreach ($words as $word) {
            $word = strtolower($word);
            if (isset($old_words[$word])) {
                $old_words[$word]++;
            } else {
                $old_words[$word] = 1;
            }
        }
    }
}


//读取新单词
//$lines = file('wordtest.txt');
$lines = file('your_book.txt');

$new_words = array();
$i_words = 0;

foreach ($lines as $line) {
    if (preg_match_all($reg_word, $line, $matches)) {
        $words = $matches[0];

        foreach ($words as $word) {
            $i_words++;
            $word = strtolower($word);

            if (isset($old_words[$word])) {
                continue;
            }

            if (isset($new_words[$word])) {
                $new_words[$word]++;
            } else {
                $new_words[$word] = 1;
            }
        }
    }
}

arsort($new_words); //按单词出现频率降序排序

file_put_contents('wordrank_output.txt', var_export($new_words, true));

//生成单词表
$new_word_file = '';

foreach ($new_words as $word => $total) {
    $new_word_file .= "$word\n";
}

file_put_contents('new_word.txt', $new_word_file);

//显示摘要
echo '<p>在' . $i_words . '个词中，发现新单词' . count($new_words) . '个。';

echo '<p>耗时：' . number_format(microtime(true) - $start, 3) . '秒';

//end file