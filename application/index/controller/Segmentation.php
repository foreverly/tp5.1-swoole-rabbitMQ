<?php
namespace app\index\controller;

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\JiebaAnalyse;
use think\Controller;
use think\Request;
use Redis;

class Segmentation extends Controller
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

    public function cut()
    {
        $content = $this->request->post('content', '');

        return ajaxSuccess($this->getTop($content));
    }

    /*
    * 搜索引擎模式，较为精准
    */
    public function doCutForSearch($text)
    {
        ini_set('memory_limit', '1024M');        
        Jieba::init();
        Finalseg::init();

        return Jieba::cut_for_search($text);
    }

    /*
    * 普通分词
    */ 
    public function doCut($text)
    {
        ini_set('memory_limit', '1024M');        
        Jieba::init();
        Finalseg::init();

        return Jieba::cut($text);
    }

    /*
    * 提取权重高的词
    */ 
    public function getTop($content, $top_k = 10)
    {
        ini_set('memory_limit', '1024M');        
        Jieba::init(array('dict'=>'small'));
        Finalseg::init();
        JiebaAnalyse::init();

        return JiebaAnalyse::extractTags($content, $top_k);
    }
}
