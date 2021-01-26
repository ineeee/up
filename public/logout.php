<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';
require ROOT . '/src/session.php';

$sess = new Session();

$sess->unset('logged in');
$sess->unset('id');
session_destroy();

header('Location: index.php');
