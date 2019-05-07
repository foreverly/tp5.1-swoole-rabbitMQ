<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use Redis;

class Echarts extends Controller
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

    public function index()
    {
        
        $this->assign('domain','tp-dev.com');

        //渲染模板
        return $this->fetch();
    }

    public function getData()
    {
        $data = [];
        for ($i=0; $i < 50; $i++) { 
            # code...
        }

        return $data;
    }
}
