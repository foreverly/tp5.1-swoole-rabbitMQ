<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'think\\worker\\' => array($vendorDir . '/topthink/think-worker/src'),
    'think\\mongo\\' => array($vendorDir . '/topthink/think-mongo/src'),
    'think\\migration\\' => array($vendorDir . '/topthink/think-migration/src'),
    'think\\helper\\' => array($vendorDir . '/topthink/think-helper/src'),
    'think\\composer\\' => array($vendorDir . '/topthink/think-installer/src'),
    'think\\captcha\\' => array($vendorDir . '/topthink/think-captcha/src'),
    'think\\' => array($vendorDir . '/topthink/think-image/src', $vendorDir . '/topthink/think-queue/src'),
    'jayazhao\\' => array($vendorDir . '/jayazhao/think-queue-rabbitmq/src'),
    'app\\' => array($baseDir . '/application'),
    'Workerman\\' => array($vendorDir . '/workerman/workerman'),
    'PhpAmqpLib\\' => array($vendorDir . '/php-amqplib/php-amqplib/PhpAmqpLib'),
    'Phinx\\' => array($vendorDir . '/topthink/think-migration/phinx/src/Phinx'),
    'Interop\\Queue\\' => array($vendorDir . '/queue-interop/queue-interop/src'),
    'Interop\\Amqp\\' => array($vendorDir . '/queue-interop/amqp-interop/src'),
    'GatewayWorker\\' => array($vendorDir . '/workerman/gateway-worker/src'),
    'Enqueue\\Dsn\\' => array($vendorDir . '/enqueue/dsn'),
    'Enqueue\\AmqpTools\\' => array($vendorDir . '/enqueue/amqp-tools'),
    'Enqueue\\AmqpLib\\' => array($vendorDir . '/enqueue/amqp-lib'),
);
