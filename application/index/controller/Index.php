<?php
namespace app\index\controller;

// use app\common\tool\RabbitMQTool;
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

    public function sendMessage()
    {
    	$mobile = $this->request->post('mobile');
        $content = $this->request->post('content');
        $times = (int)$this->request->post('times', 1);
        $pdata = $this->request->post();

        for ($i=0; $i < $times; $i++) { 
        	$data = [
        		'type' => 'MSG',
        		'data' => [
        			'mobile' => $mobile,
        			'content' => $content
        		]
        	];

            // 写入队列
            RabbitMQTool::instance('test')->wMq($data);
        }

        return ajaxSuccess([]);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
