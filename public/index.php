<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';

echo_template('home', $page);
