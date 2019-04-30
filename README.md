# tp5.1-swoole-rabbitMQ

## 第一步
安装 erlang 和 rabbitMQ

## 第二步
安装rabbitMQ可视化插件amqp

###1.windows安装
> 去[amqp](https://pecl.php.net/package/amqp)下载对应的包，windows注意x86，x64，ts，nts等的区分  

###2. linux安装，在docker中php-fpm容器内
> docker-compose exec php-fpm bash  
> pecl search amqp  
> apt-get install librabbitmq-dev  
> pecl install amqp 1.9.3  
> php.ini中加入extension=amqp.so


## 第三步
###1. dos模式下进入目录rabbitMQ\sbin执行以下命令
> rabbitmq-service.bat stop  

> rabbitmq-service.bat install  

> rabbitmq-service.bat start  

###2. 输入网址打开[amqp](http://127.0.0.1:15672/#/)，用户guest，密码guest
###3. 在[amqp](http://127.0.0.1:15672/#/)中添加新用户，用户名：admin，密码admin

## 第四步
安装composer依赖管理包，需开启php_sockets扩展
> composer require php-amqplib/php-amqplib  

## 