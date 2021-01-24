<?php

function sess_start(): ?array {
	session_start();

	return $_SESSION;
}

function echo_template(string $name, array $page=[]) {
	include ROOT . "/templates/$name.php";
}
