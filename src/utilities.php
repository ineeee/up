<?php

function echo_template(string $name, array $data=[], array $hack=[]) {
	extract($hack); // ;)

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

	// expire key after 1 hour
	$timediff = time() - $key['timestamp'];
	if ($timediff > 1 * 3600)
		return false;

	return true;
}

function try_get_filetype(string $filename) {
	$magic = [
		'png' => [ 4, '89504E47' ],
		'gif' => [ 4, '47494638' ],
		'jpg' => [ 2, 'FFD8' ]
	];

	// try to open the uploaded file
	$handle = fopen($filename, 'rb');

	// cant read file? fuck it
	if ($handle === false)
		return null;

	foreach ($magic as $ext => $data) {
		$length = $data[0];
		$bytes = $data[1];

		// read the first $length bytes of $handle
		fseek($handle, 0);
		$binary = fread($handle, $length);

		// compare the known magic bytes against the uploaded file
		if ($bytes === strtoupper(bin2hex($binary)))
			return $ext;
	}

	return null;
}

function abort(int $code=500, string $message) {
	http_response_code($code);
	?>
	<meta charset="utf-8">
	<title>Error <?= $code ?></title>
	<style> body { font-size: 1.8em; } </style>

	<p>Error <?= $code ?>: <?= htmlspecialchars($message) ?></p>
	<?php
	exit();
}
