<?php
namespace app\common\config;

/**
 * 通用配置
 */
class SelfConfig
{
    
    function __construct()
    {
        # code...
    }

    public static function getConfig($sourceKey)
    {
        $key = '';
        if ($sourceKey) {
            $key = explode('.', $sourceKey)[1];
        }

        return self::AllConfig()[$key] ?? [];
    }

    public static function AllConfig()
    {
        return [           
            'rabbit_mq' => [
                'host' => '127.0.0.1',
                'port' => 5672,
                'user' => 'admin',
                'pwd' => 'admin',
                'vhost' => '/',
                'rabbit_mq_queue' => [
                    'test' => [
                        'exchange_name' => 'ex_test', // 交换机名称
                        'queue_name' => 'que_test', // 队列名称
                        'process_num' => 3, // 默认单台机器的进程数量
                        'deal_num' => '50', // 单次处理数量
                        'consumer' => 'DealTest' // 消费地址
                    ],                    
                    'send_msg' => [
                        'exchange_name' => 'send_test', // 交换机名称
                        'queue_name' => 'send_test', // 队列名称
                        'process_num' => 3, // 默认单台机器的进程数量
                        'deal_num' => '50', // 单次处理数量
                        'consumer' => 'DealTest' // 消费地址
                    ]
                ]
            ]
        ];
    }
}    