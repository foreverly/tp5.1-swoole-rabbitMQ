<?php
namespace app\index\controller;

use app\RabbitMQ\SendMessage;
use think\Controller;
use think\Request;
use think\Queue;
use Redis;

class Index extends Controller
{
	protected $request;

    public function init()
    {
        $this->request = Request::instance();
    }

    public function redis()
    {
        $redis = new Redis(); 
        $redis->connect('127.0.0.1',6379);

        return $redis;
    }

    public function phpinfo()
    {
    	phpinfo();
    }

    public function info()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function index()
    {
        
        $this->assign('domain','tp-dev.com');

        //渲染模板
        return $this->fetch();
    }

    public function push()
    {
        
        $mobile = $this->request->post('mobile');
        $content = $this->request->post('content');
        $times = (int)$this->request->post('times', 1);
        $pdata = $this->request->post();

        // 1.当前任务将由哪个类来负责处理。 
	    //   当轮到该任务时，系统将生成一个该类的实例，并调用其 fire 方法
	    // to do 根据消息类型自动按照先定义好消息名称与消费者类名的映射关系调用类
	    $jobHandlerClassName  = 'application\job\Job1'; 
	    // 2.当前任务归属的队列名称，如果为新队列，会自动创建
	    $jobQueueName  	  = "testJobQueue"; 
	    // 3.当前任务所需的业务数据 . 不能为 resource 类型，其他类型最终将转化为json形式的字符串
	    //   ( jobData 为对象时，需要在先在此处手动序列化，否则只存储其public属性的键值对)
	    $jobData       	  = [ 'ts' => time(), 'bizId' => uniqid() , 'a' => 1 ] ;
	    // 4.将该任务推送到消息队列，等待对应的消费者去执行
	    $isPushed = Queue::push( $jobHandlerClassName , $jobData , $jobQueueName );	
	    // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
	    if( $isPushed !== false ){  
	        return ajaxSuccess([], date('Y-m-d H:i:s') . " a new Hello Job is Pushed to the MQ"."<br>");
	    }else{
	        return ajaxError('Oops, something went wrong.');
	    }
    }

    public function sendMessage()
    {
    	$mobile = $this->request->post('mobile');
        $content = $this->request->post('content');
        $times = (int)$this->request->post('times', 1);
        $pdata = $this->request->post();

        $sendMessage = new SendMessage();

        for ($i=0; $i < $times; $i++) { 
        	$data = [
        		'type' => 'MSG',
        		'data' => [
        			'mobile' => $mobile,
        			'content' => $content
        		]
        	];

        	$routingKey = 'key_1';
        	$exchangeName = 'ex1';

        	$sendMessage->push($data, $routingKey, $exchangeName);
        }

        return ajaxSuccess([]);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
