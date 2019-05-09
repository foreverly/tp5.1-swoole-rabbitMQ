<?php
namespace app\helper\send;

/**
 * 邮件发送
 */
class SendHandle
{	
	public function __construct($sendClass)
	{
		if($sendClass instanceof SendInterface){
	        $this->sendClass = $sendClass;
	    }else{
	        throw new \think\Exception('the object not implements SendInterface', 100006);
	    }
		
	}

	public function send($data)
	{
		return $this->sendClass->send($data);
	}
}