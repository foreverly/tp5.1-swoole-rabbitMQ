<?php
namespace app\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

use think\Controller;
use think\Log;
use think\Request;
use think\Db;

class MessageConsume extends Controller
{
    const exchange = 'router';
    const queue = 'msgs';
    const consumerTag = 'consumer';
    const HOST = '127.0.0.1';
    const PORT = '5672';
    const USER = 'admin';
    const PASS = 'admin';
    const VHOST = '/';

    function shutdown($channel, $connection)
    {
        $channel->close();
        $connection->close();
        write_log("closed",3);
    }

    function process_message($message)
    {
        if ($message->body !== 'quit') {
            $obj = json_decode($message->body);
            if (!isset($obj->id)) {
                echo 'error data\n';
                write_log("error data:" . $message->body, 2);
            } else {
                try {
                    write_log("data:" . json_encode($message));
                } catch (\Think\Exception  $e) {
                    write_log($e->getMessage(), 2);
                    write_log(json_encode($message), 2);
                } catch (\PDOException $pe) {
                    write_log($pe->getMessage(), 2);
                    write_log(json_encode($message), 2);
                }
            }
        }
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        // Send a message with the string "quit" to cancel the consumer.
        if ($message->body === 'quit') {
            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
        }
    }

    /**
     * 启动
     *
     * @return \think\Response
     */
    public function start()
    {
        $connection = new AMQPStreamConnection(self::HOST, self::PORT, self::USER, self::PASS, self::VHOST);
        $channel = $connection->channel();
        $channel->queue_declare(self::queue, false, true, false, false);
        $channel->exchange_declare(self::exchange, 'direct', false, true, false);
        $channel->queue_bind(self::queue, self::exchange);
        $channel->basic_consume(self::queue, self::consumerTag, false, false, false, false, array($this, 'process_message'));

        register_shutdown_function(array($this, 'shutdown'), $channel, $connection);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
        write_log("starting",3);
    }

}