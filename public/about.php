<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';
require ROOT . '/src/session.php';

$sess = new Session();
$page = [
	'name' => 'about',
	'session' => $sess->raw(),
	'logged in' => $sess->get('logged in')
];

echo_template('about', $page);
