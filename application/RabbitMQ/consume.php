<?php
$conn = [
    // Rabbitmq 服务地址
    'host' => '127.0.0.1',
    // Rabbitmq 服务端口
    'port' => '5672',
    // Rabbitmq 帐号
    'login' => 'admin',
    // Rabbitmq 密码
    'password' => 'admin',
    'vhost'=>'/'
];


//创建连接和channel
$conn = new AMQPConnection($conn);
if(!$conn->connect()) {
    die("Cannot connect to the broker!\n");
}
$channel = new AMQPChannel($conn);
$exchangeName = 'ex1';

//创建交换机
$ex = new AMQPExchange($channel);
$ex->setName($exchangeName);

$ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型
$ex->setFlags(AMQP_DURABLE); //持久化
$ex->declareExchange();

//  创建队列
$queueName = 'queue1';
$q = new AMQPQueue($channel);
$q->setName($queueName);
$q->setFlags(AMQP_DURABLE);
$q->declareQueue();

// 用于绑定队列和交换机，跟 send.php 中的一致。
$routingKey = 'key_1';
$q->bind($exchangeName,  $routingKey);

//接收消息
$q->consume(function ($envelope, $queue) {
    $msg = $envelope->getBody();
    echo $msg."\n"; //处理消息
}, AMQP_AUTOACK);

$conn->disconnect();