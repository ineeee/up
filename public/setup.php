<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';
require ROOT . '/src/database.php';

// $config = load_config();
$db = new Database();
$sess = sess_start();

$page = [
	'name' => 'setup',
	'needs setup' => false,
	'invite key' => null,
];

if ($db->needs_setup()) {
	// null means "invited by no one"
	$key = $db->create_invite_key(null);

	$page['needs setup'] = true;
	$page['invite key'] = $key;
}

echo_template('setup', $page);
