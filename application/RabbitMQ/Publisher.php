<?php
namespace app\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
// require_once __DIR__ . '/../../vendor/autoload.php';

class Publisher
{
    const exchange = 'router';
    const queue = 'msgs';
    const HOST = '127.0.0.1';
    const PORT = '5672';
    const USER = 'admin';
    const PASS = 'admin';
    const VHOST = '/';

    public static  function pushMessage($data)
    {
        $connection = new AMQPStreamConnection(self::HOST, self::PORT, self::USER, self::PASS, self::VHOST);
        $channel = $connection->channel();

        $channel->queue_declare(self::queue, false, true, false, false); // 第三个参数为true代表开启队列持久化
        $channel->exchange_declare(self::exchange, 'direct', false, true, false);
        $channel->queue_bind(self::queue, self::exchange);

        $messageBody = $data;
        $message = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)); // message持久化

        $channel->basic_publish($message, self::exchange);
        $channel->close();
        $connection->close();

        return "ok";
    }
}

$p = new Publisher();
echo $p->pushMessage(json_encode(['mobile' => '1388888888']));


		