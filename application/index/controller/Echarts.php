<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\echartsModel;
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
        $this->assign('echarts', (new echartsModel())->getData());

        //渲染模板
        return $this->fetch();
    }

    public function getData()
    {
        return ajaxSuccess((new echartsModel())->getData());
    }
}
