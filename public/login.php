<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';
require ROOT . '/src/session.php';

$sess = new Session();
$page = [
	'name' => 'login',
	'status' => 'needs login',
	'session' => $sess->raw()
];

$post_user = $_POST['user'] ?? null;
$post_pass = $_POST['pass'] ?? null;

if ($post_user && $post_pass) {
	require ROOT . '/src/database.php';
	$db = new Database();

	if ($db->check_password($post_user, $post_pass) === true) {
		$user_info = $db->get_user_info($post_user);

		$sess->set('logged in', true);
		$sess->set('user', $post_user);
		$page['status'] = 'success';

		header('Location: index.php');
	} else {
		$page['status'] = 'wrong creds';
	}
}

echo_template('login-form', $page);
