<?php

class Database {
	function __construct() {
		$this->db = new SQLite3(ROOT . '/config/sqlite3.db');

		// stores user accounts
		$this->db->query('
			CREATE TABLE IF NOT EXISTS users (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				user TEXT,
				pass TEXT,
				api_key TEXT,
				invited_by INTEGER
			);
		');

		// stores invites of each account
		// if user_id is NULL then the invite was created by setup.php
		$this->db->query('
			CREATE TABLE IF NOT EXISTS invites (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				user_id INTEGER,
				key TEXT,
				timestamp INTEGER
			);
		');

		// stores files of an user
		// no need to store size/creation date. the filesystem does that
		$this->db->query('
			CREATE TABLE IF NOT EXISTS files (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				user_id INTEGER,
				filename TEXT,
				description TEXT
			);
		');
	}

	function query(string $sql): SQLite3Result {
		return $this->db->query($sql);
	}

	// check if there are any invites (aka at least one user is invited)
	function needs_setup(): bool {
		// count if there are any invites
		$query = $this->db->query('
			SELECT COUNT(*) AS count FROM invites
		');

		$count = $query->fetchArray(SQLITE3_ASSOC)['count'];

		if ($count === 0)
			return true;
		else
			return false;
	}

	// insert a new invite key in `invites`
	function create_invite_key(?int $user_id): string {
		$key = random_string(24);
		$time = time();

		$stmt = $this->db->prepare('
			INSERT INTO invites (user_id, key, timestamp) VALUES (:id, :key, :ts)
		');
		$stmt->bindParam(':id', $user_id, SQLITE3_INTEGER);
		$stmt->bindParam(':key', $key, SQLITE3_TEXT);
		$stmt->bindParam(':ts', $time, SQLITE3_INTEGER);
		$stmt->execute();

		return $key;
	}

	// get all info on $key from `invites`
	function get_invite_info(string $key): ?array {
		$stmt = $this->db->prepare('
			SELECT invites.id, invites.key, invites.user_id, invites.timestamp, users.user FROM invites
			LEFT OUTER JOIN users ON users.id = invites.user_id
			WHERE key = :key
			LIMIT 1
		');
		$stmt->bindParam(':key', $key, SQLITE3_TEXT);
		$result = $stmt->execute();

		$key = $result->fetchArray(SQLITE3_ASSOC);

		if ($key === false)
			return null;

		return $key;
	}

	// check if `users.pass` matches $pass, then return `users.id`
	function check_password(string $user, string $pass): ?int {
		$stmt = $this->db->prepare('
			SELECT id, pass FROM users WHERE user = :user LIMIT 1
		');
		$stmt->bindParam(':user', $user, SQLITE3_TEXT);
		$result = $stmt->execute();

		$data = $result->fetchArray(SQLITE3_ASSOC);

		if ($data === false)
			return null;

		$hash = $data['pass'] ?? null;

		if ($hash === null) // why would it ever be null?
			return null;

		if (password_verify($pass, $hash) === true)
			return $data['id'];
		else
			return null;
	}

	// insert a new user into `users`
	function create_user(string $user, string $pass, ?int $invited_by) {
		$hash = password_hash($pass, PASSWORD_DEFAULT);
		$api_key = sha1(microtime() . 'hunter2');

		$stmt = $this->db->prepare('
			INSERT INTO users (user, pass, api_key, invited_by) VALUES (:user, :pass, :api_key, :invited_by)
		');
		$stmt->bindParam(':user', $user, SQLITE3_TEXT);
		$stmt->bindParam(':pass', $hash, SQLITE3_TEXT);
		$stmt->bindParam(':api_key', $api_key, SQLITE3_TEXT);
		$stmt->bindParam(':invited_by', $invited_by, SQLITE3_INTEGER);
		$stmt->execute();
	}

	// get all info of $user_id in `users`
	function get_user_info(int $user_id): ?array {
		$stmt = $this->db->prepare('
			SELECT user, api_key, invited_by FROM users WHERE id = :id
		');
		$stmt->bindParam(':id', $user_id, SQLITE3_INTEGER);
		$result = $stmt->execute();

		$data = $result->fetchArray(SQLITE3_ASSOC);

		if ($data === false)
			return null;

		return [
			'id' => $user_id,
			'user' => $data['user'],
			'api key' => $data['api_key'],
			'invited by' => $data['invited_by'],
		];
	}

	// find if $user exists in `users`
	function user_exists(string $user): bool {
		$stmt = $this->db->prepare('
			SELECT COUNT(*) AS count FROM users WHERE user = :user
		');
		$stmt->bindParam(':user', $user, SQLITE3_TEXT);
		$result = $stmt->execute();

		$data = $result->fetchArray(SQLITE3_ASSOC);

		if ($data['count'] === 0)
			return false;
		else
			return true;
	}
}
