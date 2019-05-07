<?php
namespace app\index\model;

class echartsModel
{

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

	public static function getData($num = 50)
	{
		$data = [];
		for ($i=0; $i < $num; $i++) { 
			$data[] = [

			];
		}

		return $data
	}
}