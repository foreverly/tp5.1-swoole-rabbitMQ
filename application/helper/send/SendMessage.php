<?php
namespace app\helper\send;

/**
 * 短信发送
 */
class SendMessage implements SendInterface 
{
	
	function __construct(array $config)
	{
		// setting message
	}

	public function send($data)
	{
		// to do 
		$pathname = __DIR__ . '/../../../runtime/temp/';
		$filename = $pathname . 'test_send_msg.log';

		$log = "手机号：{$data['mobile']}，发送内容：{$data['content']}\r\n";
		// sleep(2);
		file_put_contents($filename, $log, FILE_APPEND);

		return true;
	}
}