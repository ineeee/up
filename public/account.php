<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';
require ROOT . '/src/session.php';

$sess = new Session();

if (!$sess->get('logged in')) {
	header('Location: login.php');
	exit;
}

require ROOT . '/src/database.php';
require ROOT . '/src/config.php';

$config = new Config();
$db = new Database();
$page = [
	'name' => 'account',
	'session' => $sess->raw(),
	'logged in' => true,
	'status' => null,
];

$post_action = $_POST['action'] ?? null;
if ($post_action) {
	switch ($post_action) {
	case 'create invite':
		$page['status'] = 'created invite';
		$page['new invite key'] = $db->create_invite_key($sess->get('id'));
		break;

	case 'change password':
		$new_pass = $_POST['pass'] ?? null;

		if (!$new_pass || strlen($new_pass) < 8) {
			$page['status'] = 'new pass too short';
		} else {
			// TODO actually change it
			$page['status'] = 'pass changed';
		}

		break;

	// change username,
	// change password,
	// etc

	default:
		break;
	}
}

$page['space left'] = round(disk_free_space(ROOT) / 1024 / 1024, 2) . ' mb';

echo_template('user/account', $page, [
	'config' => $config,
	'user' => $db->get_user_info($sess->get('id')),
	'user_invites' => $db->get_user_invites($sess->get('id')),
]);
