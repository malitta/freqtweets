<?php

namespace Swiftlet\Models;

class Session extends \Swiftlet\Model
{

	function __construct(\Swiftlet\Interfaces\App $app)
	{
		session_start();
		parent::__construct($app);
	}

	public function get($var)
	{
		return (isset($_SESSION[$var])) ? $_SESSION[$var] : null;
	}

	public function set($var, $value)
	{
		$_SESSION[$var] = $value;
		return true;
	}
}
