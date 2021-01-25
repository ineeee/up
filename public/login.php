<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';
require ROOT . '/src/session.php';

$sess = new Session();
$page = [
	'name' => 'login',
	'session' => $sess->raw(),
	'logged in' => $sess->get('logged in'),

	'status' => 'needs login',
];

$post_user = $_POST['user'] ?? null;
$post_pass = $_POST['pass'] ?? null;

if ($post_user && $post_pass) {
	require ROOT . '/src/database.php';
	$db = new Database();
	$user_id = $db->check_password($post_user, $post_pass);

	if ($user_id !== null) {
		$user_info = $db->get_user_info($user_id);
		foreach ($user_info as $key => $value)
			$sess->set($key, $value);

		$sess->set('logged in', true);
		$sess->set('user_id', $user_id);

		$page['status'] = 'success';
		header('Location: index.php');
	} else {
		$page['status'] = 'wrong creds';
	}
}

echo_template('login', $page);
