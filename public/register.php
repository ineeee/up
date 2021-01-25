<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';
require ROOT . '/src/session.php';
require ROOT . '/src/database.php';

$db = new Database();
$sess = new Session();

$page = [
	'name' => 'register',
	'status' => 'no key',
	'user' => null,
	'logged in' => $sess->get('logged in')
];

// this file.



// this file is a disaster.
// sorry.



if (isset($_GET['key']) && strlen($_GET['key']) > 1) {
	$given_key = $_GET['key'];
	$key = $db->get_invite_info($given_key);

	$page['status'] = 'invalid key';
	$page['key'] = $given_key;

	if ($key !== null) {
		$timediff = time() - $key['timestamp'];

		// expire key after 24 hours
		// TODO use config
		if ($timediff > 24 * 3600) {
			$page['status'] = 'expired key';
		} else {
			$page['status'] = 'valid key';
		}

		// show the user name of whoever created the invite key
		if ($key['user_id'] === null)
			$page['user'] = 'the setup wizard';
		else
			$page['user'] = htmlspecialchars($key['user']);
	}

} else if (isset($_POST['key'])) {
	$user = $_POST['user'] ?? false;
	$pass = $_POST['pass'] ?? false;
	$given_key = $_POST['key'] ?? false;

	if ($user === false || $pass === false || $given_key === false) {
		$page['status'] = 'missing stuff';
	} else if (strlen($user) < 2 || strlen($pass) < 8) {
		// should give the user a different message but im lazy
		// "your nick/password doesn't meet the min requirements"?
		$page['status'] = 'missing stuff';
	} else {
		// check if the key is valid and get $user_id of who created it
		$key = $db->get_invite_info($given_key);

		if ($key === null) {
			$page['status'] = 'invalid key';
		} else {
			$timediff = time() - $key['timestamp'];

			// expire key after 24 hours
			// TODO use config
			if ($timediff > 24 * 3600) {
				$page['status'] = 'expired key';
			} else {
				if ($db->user_exists($user)) {
					$page['status'] = 'name taken';
				} else {
					$db->create_user($user, $pass, $key['user_id']);
					$page['status'] = 'success';
				}
			}
		}
	}
}

echo_template('register', $page);
