<?php

function echo_template(string $name, array $data=[]) {
	$default_data = [
		'template' => $name,
		'name' => 'unknown'
	];

	$page = array_merge($default_data, $data);

	include ROOT . "/templates/$name.php";
}

function random_string($length=24): string {
	$space = '0123456789abcdefghkmpqrstxyz';
	$key = '';
	$max = strlen($space) - 1;

	for ($i = 0; $i < $length; $i++)
		$key .= $space[random_int(0, $max)];

	return $key;
}

function is_key_valid(Database &$db, ?array $key): bool {
	if ($key === null)
		return false;

	if (strlen($key['key']) < 24)
		return false;

	// expire key after 24 hours
	$timediff = time() - $key['timestamp'];
	if ($timediff > 24 * 3600)
		return false;

	return true;
}