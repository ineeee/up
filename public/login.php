<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';

if (isset($_POST['user']) && isset($_POST['pass'])) {
	$post_user = $_POST['user'];
	$post_pass = $_POST['pass'];

	session_start();
	// mysql??
	$_SESSION['logged in'] = true;
	header('Location: index.php');
}

echo_template('login-form');
