<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if (!function_exists("dd")) {
	function dd($data){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
}

if (!function_exists("ajaxSuccess")) {
	function ajaxSuccess(array $data, $msg){
		return [
			'status' => 'success',
			'code' => 200,
			'data' => $data,
			'msg' => $msg
		];
	}
}

if (!function_exists("ajaxError")) {
	function ajaxError($msg){
		return [
			'status' => 'error',
			'code' => -1,
			'msg' => $msg
		];
	}
}