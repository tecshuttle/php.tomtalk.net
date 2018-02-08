<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function print_a($val, $return = false)
{
    $out = "<pre style=\"background: #000; color: #ccc; font: 12px 'fixedsys'; text-align: left; width: 100%; padding: 5px\">\n";
    $out .= print_r($val, true);
    $out .= "</pre>\n";

    if ($return) {
        return $return;
    }

    echo $out;
}

function guid()
{
    if (function_exists('com_create_guid')) {
        $uuid = com_create_guid();
    } else {
        mt_srand((double)microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = chr(123) // "{"
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12)
            . chr(125);
        // "}"
    }

    $uuid = trim($uuid, '{}');
    $uuid = strtolower($uuid);

    return str_replace('-', '', $uuid);
}

function time_ms() {
    list($s1, $s2) = explode(' ', microtime());
    $time = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);

    return $time;
}

//end file