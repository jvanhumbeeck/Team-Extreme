<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function addCookie($name, $value)
{
	setcookie($name, $value, 2147483647, "/");
}

function removeCookie($name)
{
	setcookie($name, "", time() - 3600, "/");
}

function editCookie($name, $newvalue)
{
	addCookie($name, $newvalue);
}

function hasCookie($name)
{
	if(isset($_COOKIE[$name])) {
		return true;
	}else {
		return false;
	}
}

function getCookie($name)
{
	return $_COOKIE[$name];
}

?>