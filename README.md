# tp5.1-swoole-rabbitMQ

## 一
安装 erlang 和 rabbitMQ

## 二
安装rabbitMQ可视化插件amqp

1.windows安装
> 去[amqp](https://pecl.php.net/package/amqp)下载对应的包，windows注意x86，x64，ts，nts等的区分  

2.linux安装，在docker中php-fpm容器内
> docker-compose exec php-fpm bash

> pecl search amqp  

> apt-get install librabbitmq-dev  

> pecl install amqp 1.9.3  

3.php.ini中加入
> extension=amqp.so


## 三
1.dos模式下进入目录rabbitMQ\sbin执行以下命令
> rabbitmq-service.bat stop  

> rabbitmq-service.bat install  

> rabbitmq-service.bat start  

2.输入网址打开[amqp](http://127.0.0.1:15672/#/)，用户:guest，密码:guest
3.在[amqp](http://127.0.0.1:15672/#/)中添加新用户，用户名:admin，密码:admin，vhost:/

## 四
安装composer依赖管理包，需开启php_sockets扩展
> composer require php-amqplib/php-amqplib  

## 五

1.去[Github](https://github.com/swoole/swoole-src)下载swoole扩展源码，编译安装
> phpize

> ./configure

> make install

2.pecl安装
> pecl install swoole


编译安装完后，开启swoole扩展，修改php.ini添加
> extension=swoole.so

## 六

测试首页请求后，进入public，运行如下代码监听rabbitmq队列
> php swoole.php

