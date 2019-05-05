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
        // var_dump(RabbitMQTool::instance('test')->rMq());exit;

        // if ($data = RabbitMQTool::instance('send_msg')->rMq(1)) {
        //     echo $this->handleTask($data[0]);
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
        
        swoole_timer_tick(200, function () use ($this){
            $data = RabbitMQTool::instance('send_msg')->rMq(1);
            if ($data) {
                // 处理发送任务
                $start = microtime(true);
                $res = $this->handleTask($data[0]);
                $end = microtime(true);
                // 发送成功
                if(!$res){
                    echo '发送成功！' . json_encode($data[0]) . ', 耗时:'. round($end - $start, 3).'秒'.PHP_EOL;
                }
                // 发送失败，把发送失败的加入到失败队列中
                else{
                    RabbitMQTool::instance('send_msg_error')->rMq($data[0]);
                }
            } else {
                echo 'queue is empty';
            }
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