<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);

if (PHP_SAPI == 'cli-server') {
	// To help the built-in PHP dev server, check if the request was actually for
	// something which should probably be served as a static file
	
	$url  = parse_url($_SERVER['REQUEST_URI']);
	
	$file = __DIR__ . $url['path'];

	if (is_file($file)) {
		return false;
	}
}

if (defined('ROOT_PATH') == FALSE) {
	$spl = new SplFileInfo(__DIR__ . '/..');

	define('ROOT_PATH', $spl->getRealPath());
}

require_once ROOT_PATH . '/vendor/autoload.php';

// session_start();

require_once ROOT_PATH . '/app/bootstrap.php';