<?php
require_once('mqs.sdk.class.php');
$mqs = new mqs(
    array(
        'accessKeyId' => '',
        'accessKeySecret' => '',
        'accessOwnerId' => '',
        'accessQueue' => '',
        'accessRegion' => ''
    )
);


// Push 大约10个每秒
/*
for ($i = 1; $i < 100; $i++) {
    $do = $mqs->sendMessage(
        array(
            'MessageBody' => 'message ' . $i,
            'DelaySeconds' => 0,
            'Priority' => 8
        )
    );
}
*/


// Read    读取
for ($i = 1; $i <= 30; $i++) {
    $read = $mqs->receiveMessage();
    $mqs->dropMessage(
        array(
            'ReceiptHandle' => $read['Message']['ReceiptHandle']
        )
    );

    echo $i . ' ' . $read['Message']['MessageBody'] . '<br/>';
}
/*
// Delete  移除
$do = $mqs->dropMessage(
    array(
        //'ReceiptHandle' => $read['Message']['ReceiptHandle']
        'ReceiptHandle' => '1-ODU4OTkzNDU5NS0xNDA1ODQ5Mjc5LTItOA=='
    )
);
print_r( $do );
echo "\r\n";
echo "\r\n";
echo "\r\n";

*/
