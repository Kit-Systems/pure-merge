<?php
private function login()
	{
		$client = new WooppaySoapClient('https://www.test.wooppay.com/api/wsdl');
		$login_request = new CoreLoginRequest();
		$login_request->username = 'test_merch';
		$login_request->password = 'A12345678a';
		return $client->login($login_request) ? $client : false;
	}
?>