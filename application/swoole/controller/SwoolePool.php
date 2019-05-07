<?php
namespace app\swoole\controller;

use Swoole\Process\Pool;
use think\console\Input;
use think\console\Output;
use think\console\Command;
use app\common\servant\SendServant;

class SwoolePool extends Command
{
    private $pool;
    // 工作者实例对象
    private $servantInstanceList = [];
    // 配置服务
    private static $config = [];

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        // 初始化线程数
        self::$config = [
            SendServant::class => 2,
        ];

        $workerNum = count(self::$config);

        //初始化swoole进程池
        $this->pool = new Pool($workerNum);

        //绑定方法
        $this->pool->on("WorkerStart", [$this, 'onWorkerStart']);
        $this->pool->on("WorkerStop", [$this, 'onWorkerStop']);

        $this->pool->start();
    }

    protected function configure()
    {
        $this->setName('SwooleMainProcess')->setDescription('生产者消费者工作的主线程');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('execute success');
        $this->pool->start();
    }
    /**
     * 线程具体工作内容
     * @param $pool 线程池对象
     * @param $workerId 线程id
     */
    function onWorkerStart(Pool $pool, int $workerId)
    {
        $servant = self::getServant($workerId);
        //实例队列
        $this->servantInstanceList[$workerId] = $servant;
        $servant->workStart($pool, $workerId);
    }

    /**
     * 线程停止工作
     * @param $pool 线程池对象
     * @param $workerId 线程id
     */
    function onWorkerStop(Pool $pool, int $workerId)
    {
        $this->output->writeln("Worker#{$workerId} is stopped\n");
        //删除内存里面的对象
        unset($this->servantInstanceList[$workerId]);
    }

    /**
     * @param int $workerId 工作者下标
     * @return void
     */
    public static function getServant($workerId)
    {
        foreach (self::$config as $k => $v) {
            // var_dump($k);exit;
            if ($workerId < $v) {
                return call_user_func([$k, 'getInstance']);
            }
            $workerId -= $v;
        }
        return null;
    }

    /**
     * 获取工作者总数量
     */
    public static function getCount(): int
    {
        $count = 0;
        foreach (self::$config as $k => $v) {
            $count += $v;
        }
        return $count;
    }
}