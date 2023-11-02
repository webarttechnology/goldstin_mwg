<?php

class EazyCFCaptchaAutoloader
{
	public function __construct()
	{
		spl_autoload_register(array($this, 'load_class'));
	}
	
	public static function register()
	{
		new EazyCFCaptchaAutoloader();
	}
	
	public function load_class($class_name)
	{
		if(strpos($class_name, 'TK\EazyCFCaptcha\\') !== 0) {
			return;
		}

		$file = str_replace('\\', '/', str_replace( 'TK\EazyCFCaptcha\\', dirname(__FILE__) . '/', $class_name)).'.php';
		if(file_exists($file))
		{
			require_once($file);
		}
	}
}

EazyCFCaptchaAutoloader::register();
