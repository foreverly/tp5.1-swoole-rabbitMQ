<?php
namespace app\help\send;

/**
 * 短信发送
 */
class SendMessage implements SendInterface 
{
	
	function __construct($config)
	{
		# code...
	}

	public function send()
	{
		// to do 
		sleep(1);
		return true;
	}
}