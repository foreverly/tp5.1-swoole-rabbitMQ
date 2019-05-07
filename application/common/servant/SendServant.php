<?php
namespace app\common\servant;

use Swoole\Process\Pool;
use app\helper\send\SendHandle;
use app\common\tool\RabbitMQTool;

class SendServant implements BaseServant
{
	function workStart(Pool $pool, int $workerId)
    {
        $rabbitMq = RabbitMQTool::instance('send_msg');
        swoole_timer_tick(200, function () use (&$rabbitMq){            
            $no_ack = false;
            $callBack = function ($msg) {

                $rData = json_decode($msg->body, true);
                $start = microtime(true);
                $res = $this->handleTask($rData);
                $end = microtime(true);

                // 手动确认
                if ($res) {
                    // 发送成功
                    echo '发送成功！' . json_encode($rData) . ', 耗时:'. round($end - $start, 3).'秒'.PHP_EOL;
                    // 确认
                    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                } else {
                    echo '发送失败！' .PHP_EOL;
                    // 发送失败，消息重新进入队列
                    $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                }
            };

            $rabbitMq->channel->basic_qos(null, 1, null);
            $rabbitMq->channel->basic_consume($rabbitMq->mqConf['queue_name'], '', false, $no_ack, false, false, $callBack);

            while (count($rabbitMq->channel->callbacks)) {
                $rabbitMq->channel->wait();
            }
            $rabbitMq->closeConn();
        });
    }

    public function handleTask($data)
    {
        if (!isset($data['sendClass'])) {
            return false;
        }

        $sendClass = unserialize($data['sendClass']);
        $sendHandle = new SendHandle($sendClass);

        return $sendHandle->send($data['data']);
    }

    /*
    * 获得当前实例
    */
    public static function getInstance(): BaseServant
    {
        return new self();
    }
}