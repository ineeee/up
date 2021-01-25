<?php

class Session {
	function __construct() {
		session_start();
	}

	function get(string $name) {
		return $_SESSION[$name] ?? null;
	}

	function set(string $name, $value) {
		$_SESSION[$name] = $value;
	}

	function unset(string $name) {
		unset($_SESSION[$name]);
	}

	function raw(): array {
		$sess = $_SESSION ?? [];

		// patch all strings to be html-friendly
		foreach ($sess as $key => &$value)
			if (gettype($value) === 'string')
				$value = htmlspecialchars($value);

		return $sess;
	}
}
