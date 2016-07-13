<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/7/2
 * Time: 20:50
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$cache = new \yii\caching\MemCache([
    'useMemcached' => false,
    'persistentId' => 'ydb',
    'servers' => [
        [
            'host' => '192.168.11.22',
            'port' => 11211,
            'weight' => 100,
        ],
    ],
]);

$client = new \cdcchen\yii\wechat\QyClient([
    'cache' => $cache,
]);

$corpId = 'wx4affdfc26d62c294';
$corpSecret = 'kFqqOSHMkERfuA8kX1eaw1tGAiLv7KWet5L_aYqSm7H4cxvEcx76u7WhkVM1UcSw';

//$token1 = $client->getDefaultToken($corpId, $corpSecret);
//$token2 = $client->getProviderToken($corpId, $corpSecret);
//var_dump($token1, $token2);
$ticket = $client->getJsApiTicket($corpId, $corpSecret);
var_dump($ticket);
