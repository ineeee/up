<?php

declare(strict_types=1);
define('ROOT', '/www/up');

require ROOT . '/src/utilities.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST')
	abort(405, 'method not allowed. only POST accepted');

require ROOT . '/src/config.php';
require ROOT . '/src/database.php';

$db = new Database();
$config = new Config();
$headers = getallheaders();

if ($headers === false)
	abort(500, 'cant read request headers');

$request_key = null;
foreach ($headers as $key => $value) {
	if (strtolower($key) === 'authorization') {
		$request_key = $value;
		break;
	}
}

if ($request_key === null)
	abort(401, 'need auth key');

$user_info = $db->get_api_key_owner($request_key);

if ($user_info === null)
	abort(401, 'need auth key');

if (!isset($_FILES) || empty($_FILES) || !array_key_exists('file', $_FILES))
	abort(400, 'no file uploaded');

$file = $_FILES['file'];

if (!array_key_exists('size', $file) || !isset($file['size']))
	abort(411, 'file has no size');

if ($file['size'] > $config->get('max file size'))
	abort(413, 'file too large');

$orig_name = $file['name'] ?? 'Untitled';
$hash_name = random_string($config->get('file slug length'));

// try to put an extension on it, check mime type
if ($file['size'] > 64) {
	$ext = try_get_filetype($file['tmp_name']);

	if ($ext !== null) {
		$hash_name .= '.' . $ext;
	}
}

$dir = ROOT . '/public/' . $config->get('upload dir');

if (!is_writable($dir))
	abort(500, 'upload dir is not writable');

$target_path = $dir . '/' . $hash_name;

$success = move_uploaded_file($file['tmp_name'], $target_path);

if ($success !== true) {
	abort(500, 'unknown error while writing the file');
}

$db->register_file($user_info['id'], $hash_name, $orig_name);

echo $config->get('public url') . $config->get('upload dir') . '/' . $hash_name;
