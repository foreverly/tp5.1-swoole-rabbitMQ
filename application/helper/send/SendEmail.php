<?php
namespace app\helper\send;

/**
 * 邮件发送
 */
class SendEmail implements SendInterface 
{
	
	function __construct(array $config)
	{
		# code...
	}

	public function send($data)
	{
		// to do 
		$pathname = __DIR__ . '/../../../runtime/temp/';
		$filename = $pathname . 'test_send_eamil.log';

		$log = "邮箱：{$data['email']}，发送内容：{$data['content']}\r\n";
		
		file_put_contents($filename, $log, FILE_APPEND);

		return true;
	}
}