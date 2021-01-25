<?php

class Config {
	function __construct() {
		$this->path = ROOT . '/config/config.json';
		$this->ready = false;
		$this->config = [];

		if (!is_readable($this->path))
			return;

		$file = file_get_contents($this->path);

		if ($file === false)
			return;

		$json = json_decode($file, true);

		if ($json === null)
			return;

		$this->config = $json;
		$this->ready = true;
	}

	function get(string $key) {
		if ($this->config[$key] === null)
			throw new Exception('missing required config variable ' . $key);

		return $this->config[$key];
	}

	function set(string $key, $value) {
		// doesn't make sense
		$this->config[$key] = $value;
	}
}
