<?php
namespace app\index\model;

class echartsModel
{

	private $channels = [
		1 => '邮件营销1', 
		2 => '联盟广告2', 
		3 => '视频广告3', 
		4 => '直接访问4', 
		5 => '搜索引擎5'
	];

	public function __set($name, $value)
	{
		$this->$name = $value;
	}

	public function __get($name)
	{
		if (!isset($this->$name)) {
			$this->$name = NULL;
		}
	}

	public function getData($num = 50)
	{
		$data = [];
		foreach ($this->channels as $channel_id => $channel) {
			$data[] = [
				'name' => $channel,
				'data' => $this->getChannelData($channel_id)
			];
		}

		return $data;
	}

	public function getChannelData($channel_id)
	{
		$data = [];
		for ($i=0; $i < 24; $i++) { 
			$data[] = [
				'date' => $i . ':00',
				'num' => random_int(5000, 10000)
			];
		}

		return $data;
	}
}