<?php
namespace app\common\servant;

use Swoole\Process\Pool;

interface BaseServant
{
    /**
     * workStart
     *
     * @param Pool $pool
     * @param integer $workerId
     * @return void
     */
    public function workStart(Pool $pool, int $workerId);
    /**
     * getInstance
     *
     * @return BaseServant
     */
    public static function getInstance(): BaseServant;
}