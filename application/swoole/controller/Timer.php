<?php
namespace app\swoole\controller;

use app\common\tool\RabbitMQTool;
use app\helper\send\SendHandle;

/**
 * Timer
 */
class Timer
{
	private $serv;

	public function __construct()
	{            
        // while (true) {
        //     if ($data = RabbitMQTool::instance('send_msg')->rMq(1)) {
        //         echo $this->handleTask($data[0]);
        //     }else{
        //         echo 'empty';
        //     }
        // }

        $this->serv = new swoole_server("0.0.0.0", 9501);

        $this->serv->set(array(
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode'=> 1 ,
        ));

        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));                                                                                                                                                                                                                                                                                                                                                                                                      
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));

        $this->serv->start();
	}

	public function onWorkerStart( $serv , $worker_id) {
        // 在Worker进程开启时绑定定时器
        echo "onWorkerStart\n";
                
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

    public function onConnect( $serv, $fd, $from_id ) {
        echo "Client {$fd} connect\n";
    }

    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        echo "Get Message From Client {$fd}:{$data}\n";
    }
    
    public function onClose( $serv, $fd, $from_id ) {
        echo "Client {$fd} close connection\n";
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
}

?>