<?php
namespace app\common\tool;

use app\common\config\SelfConfig;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQTool {

    /**
     * RabbitMq 工具
     */

    public $channel;

    public $mqConf;

    public function __construct($mqName)
    {        
        // 获取rabbitmq所有配置
        $rabbitMqConf = SelfConfig::getConfig('Source.rabbit_mq');        
        if (!isset($rabbitMqConf['rabbit_mq_queue'])) {
            die('没有定义Source.rabbit_mq');
        }

        //建立生产者与mq之间的连接
        $this->conn = new AMQPStreamConnection(
            $rabbitMqConf['host'], $rabbitMqConf['port'], $rabbitMqConf['user'], $rabbitMqConf['pwd'], $rabbitMqConf['vhost']
        );
        // var_dump($rabbitMqConf['rabbit_mq_queue']);exit;
        $channal = $this->conn->channel();
        if (!isset($rabbitMqConf['rabbit_mq_queue'][$mqName])) {
            die('没有定义'.$mqName);
        }

        // 获取具体mq配置信息
        $mqConf = $rabbitMqConf['rabbit_mq_queue'][$mqName];
        $this->mqConf = $mqConf;

        // 声明初始化交换机，第三个参数为true表开启持久化
        $channal->exchange_declare($mqConf['exchange_name'], 'direct', false, true, false);

        // 声明初始化一条队列
        $channal->queue_declare($mqConf['queue_name'], false, true, false, false);

        // 交换机队列绑定
        $channal->queue_bind($mqConf['queue_name'], $mqConf['exchange_name']);
        $this->channel = $channal;
    }

    /**
     * @param $mqName
     * @return RabbitMQTool
     * Description: 返回当前实例
     */
    public static function instance($mqName) {
        return new RabbitMQTool($mqName);
    }

    /**
     * @param $data
     * Description: 写mq
     * @return bool
     */
    public function wMq($data) {

        try {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            $msg = new AMQPMessage($data, ['content_type' => 'text/plain', 'delivery_mode' => 2]);
            $this->channel->basic_publish($msg, $this->mqConf['exchange_name']);
        } catch (\Throwable $e) {
            $this->closeConn();
            return false;
        }

        $this->closeConn();

        return true;
    }

    /**
     * @param int $num
     * @return array
     * Description:
     * @throws \ErrorException
     */
    public function rMq($num = 1, $autoAck = false) {

        $rData = [];        
        $callBack = function ($msg) use (&$rData, $autoAck){
            $rData[] = json_decode($msg->body, true);

            if (!$autoAck) {
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            }
        };

        for ($i=0;$i<$num;$i++) {
            $this->channel->basic_consume($this->mqConf['queue_name'], '', false, $autoAck, false, false, $callBack);
        }

        $this->channel->wait();
        $this->closeConn();

        return $rData;
    }

    /**
     * Description: 关闭连接
     */
    public function closeConn() {
        $this->channel->close();
        $this->conn->close();
    }

}