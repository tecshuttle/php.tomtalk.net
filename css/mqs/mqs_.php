<?php

require_once(dirname(__FILE__) . "/mqs.class.php");

//echo dirname(__FILE__)."/mqs.class.php";exit;

$Accessid = 'ga717WxlLMfcBezG';
$AccessKey = 'HyYsmhnb4D2xjS1qQSlOdZn2XVrZq1';
$queueownerid = 'hyoc74x7nw';
$mqsurl = 'http://hyoc74x7nw.mqs-cn-hangzhou.aliyuncs.com/sendMail'; //杭州的地址


/*
$queue=new queue($Accessid,$AccessKey,$queueownerid,$mqsurl);
$queue->Createqueue($queueName,$parameter=array());        //创建消息队列
$queue->Setqueueattributes($queueName,$parameter=array());        //修改消息队列
$queue->Getqueueattributes($queueName);        //获取消息队列属性
$queue->Deletequeue($queueName);        //删除消息队列
$queue->ListQueue();                    //消息队列列表
*/


$mqs = new Message($Accessid, $AccessKey, $queueownerid, $mqsurl);
var_dump($mqs);

//exit;

$mqs->SendMessage('sendMail', '你好MQS'); //发送消息
exit;

//$msg=$mqs->ReceiveMessage($queueName,$waitseconds);    //接收消息
print_r($msg);


/*
$msg=$mqs->PeekMessage($queueName);        //接收消息不改变消息状态
print_r($msg);
$mqs->DeleteMessage($queueName,$ReceiptHandle);        //删除消息
$mqs->ChangeMessageVisibility($queueName,$ReceiptHandle,$VisibilityTimeout);         //修改消息状态
*/

//end file