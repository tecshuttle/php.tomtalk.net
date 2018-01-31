<?php
session_start();

$batchDB['host'] = '192.168.10.10';
$batchDB['user'] = 'homestead';
$batchDB['pwd'] = ($_SERVER['HTTP_HOST'] == 'memo.zenho.com' ? '123' : 'secret');

$conn = mysqli_connect($batchDB['host'], $batchDB['user'], $batchDB['pwd']) OR die(1);
mysqli_select_db($conn, 'tomtalk') OR die(1);
mysqli_query($conn, "set character set 'utf8'");

//谷歌分析代码，如果不是主站的访问，不统计。
//例如，从dev.tomtalk.net的访问，不谷歌分析统计。
$ga = <<< ga
<script type="text/javascript"> 
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-29827677-1']);
_gaq.push(['_trackPageview']);

(function () {
  var ga = document.createElement('script');
  ga.type = 'text/javascript';
  ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(ga, s);
})();
</script> 

ga;

if ($_SERVER['HTTP_HOST'] != 'www.tomtalk.net') {
    $ga = '';
}

//初始化淘宝jssdk
//tomtalk.net
$app_key = '21277274'; /*填写appkey */
$secret = '017d33be3926cb8afec7b7c1a13e4269'; /*填入Appsecret'*/

//tuji360.com
$app_key = '21195713'; /*填写appkey */
$secret = '56bfe0f05a3bd942f5bbba7925421d06';/*填入Appsecret'*/
//end file 
