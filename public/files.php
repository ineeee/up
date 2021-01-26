<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';
require ROOT . '/src/database.php';
require ROOT . '/src/session.php';

$sess = new Session();

if (!$sess->get('logged in')) {
	header('Location: login.php');
	exit;
}

$db = new Database();

$files = $db->get_files($sess->get('id'));

$page = [
	'name' => 'files',
	'session' => $sess->raw(),
	'logged in' => true,

	'files' => $files,
];

echo_template('user/files', $page);
