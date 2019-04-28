<?php
namespace app\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * 发布者
 */
class Publisher
{
	private $connection;
	private $channel;
	private $queueName;

	public function __construct($queue_name = 'hello')
	{
		// 连接服务器
		$this->connection = new AMQPStreamConnection('127.0.0.1', 5672, 'admin', 'admin');
		// 创建信道
		$this->channel = $this->connection->channel();
		$this->queueName = $queue_name;
		// 创建队列
		$this->channel->queue_declare($queue_name, false, false, false, false);
	}

	public function push($msg = 'Hello World!')
	{
		// $msg = new AMQPMessage('Hello World!');
		$msg = new AMQPMessage($msg);
		$channel->basic_publish($msg, '', $this->queueName);

		echo " [x] Sent '{$msg}'\n";

		$this->channel->close();
		$this->connection->close();
	}
}

		