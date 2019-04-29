# tp5.1-swoole-rabbitMQ

## 第一步
安装 erlang 和 rabbitMQ

## 第二步
安装rabbitMQ可视化插件amqp，去[amqp](https://pecl.php.net/package/amqp)下载对应的包，windows注意x86，x64，ts，nts等的区分

## 第三步
dos模式下进入目录rabbitMQ\sbin执行以下命令
> rabbitmq-service.bat stop

> rabbitmq-service.bat install

> rabbitmq-service.bat start

输入网址打开[amqp](http://127.0.0.1:15672/#/)，用户guest，密码guest

## 第四步
安装composer依赖管理包，需开启php_sockets扩展
> composer require php-amqplib/php-amqplib

## 