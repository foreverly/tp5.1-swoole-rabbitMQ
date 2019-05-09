<?php
// [ SWOOLE应用入口文件 ]
namespace think;

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 执行应用并响应
Container::get('app')->bind('swoole/Timer')->run()->send();
