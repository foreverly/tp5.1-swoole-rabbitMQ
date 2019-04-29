<?php
namespace app\RabbitMQ; 

/**
 * SendMessage
 */
class SendMessage
{
    
    private $connection;
    private $channel;
    private $queueName;

    public function __construct()
    {
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
        // 连接服务器
        $this->connection = new \AMQPConnection($conn);
        if(!$this->connection->connect()) {
            die("Cannot connect to the broker!\n");
        }

        // 创建信道
        $this->channel = new \AMQPChannel($this->connection);
    }

    public function push($data = [], $routingKey = 'key_1', $exchangeName = 'ex1', $exchangeType = AMQP_EX_TYPE_DIRECT)
    {
        // 用来绑定交换机和队列
        $routingKey = 'key_1';

        $ex = new \AMQPExchange($this->channel);
        //  交换机名称
        $exchangeName = 'ex1';
        $ex->setName($exchangeName);

        // 设置交换机类型 AMQP_EX_TYPE_DIRECT
        $ex->setType(AMQP_EX_TYPE_DIRECT);
        // 设置交换机是否持久化消息
        $ex->setFlags(AMQP_DURABLE);
        $ex->declareExchange();

        // 消息入列
        return $ex->publish(json_encode($data) , $routingKey );
        // echo "Send Message:".$ex->publish(json_encode($data) , $routingKey )."\n";
    }
}