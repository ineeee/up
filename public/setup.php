<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';
require ROOT . '/src/session.php';
require ROOT . '/src/database.php';
require ROOT . '/src/config.php';

$config = new Config();
$db = new Database();
$sess = new Session();

$page = [
	'name' => 'setup',
	'session' => $sess,
	'logged in' => false,
	'needs setup' => false,
	'invite key' => null,
];

if ($db->needs_setup()) {
	// null means "invited by no one"
	$key = $db->create_invite_key(null);

	$page['needs setup'] = true;
	$page['invite key'] = $key;
	$page['config'] = $config;
}

echo_template('setup', $page);
