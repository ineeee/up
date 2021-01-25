<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';
require ROOT . '/src/session.php';
require ROOT . '/src/config.php';

$sess = new Session();
$page = [
	'name' => 'home',
	'session' => $sess->raw(),
	'logged in' => $sess->get('logged in')
];

if ($sess->get('logged in') !== true) {
	echo_template('needs login', $page);
} else {
	$config = new Config();

	$page['uppy'] = [
		'endpoint' => '/api/upload.php',
		'max size' => $config->get('max file size')
	];

	echo_template('home', $page);
}
