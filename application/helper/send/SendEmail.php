<?php
namespace app\help\send;

/**
 * 邮件发送
 */
class SendEmail implements SendInterface 
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