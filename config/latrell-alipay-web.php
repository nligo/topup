<?php
return [

	// 安全检验码，以数字和字母组成的32位字符。
	'key' => '4un8cre2h3jcjxt32qdqs0cf8y05uz6v',

	//签名方式
	'sign_type' => 'MD5',

	// 服务器异步通知页面路径。
	'notify_url' => 'http://192.168.1.24:8888/order/notify',

	// 页面跳转同步通知页面路径。
	'return_url' => 'http://192.168.1.24:8888/order/return'
];