<?php

class Database {
	function __construct() {
		$this->db = new SQLite3(ROOT . '/config/sqlite3.db');

		$this->db->query('
			CREATE TABLE IF NOT EXISTS users (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				user TEXT,
				pass TEXT,
				api TEXT,
				invited_by INTEGER
			);
		');

		$this->db->query('
			CREATE TABLE IF NOT EXISTS invites (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				user_id INTEGER,
				key TEXT,
				timestamp INTEGER
			);
		');
	}

	function query(string $sql): SQLite3Result {
		return $this->db->query($sql);
	}

	function needs_setup(): bool {
		// count if there are any invites
		$egg = $this->db->query('
			SELECT COUNT(*) AS count FROM invites
		');

		$count = $egg->fetchArray(SQLITE3_ASSOC)['count'];

		if ($count === 0)
			return true;
		else
			return false;
	}

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

	function check_password(string $user, string $pass): bool {
		$stmt = $this->db->prepare('
			SELECT pass FROM users WHERE user = :user LIMIT 1
		');
		$stmt->bindParam(':user', $user, SQLITE3_TEXT);
		$result = $stmt->execute();

		$data = $result->fetchArray(SQLITE3_ASSOC);

		if ($data === false)
			return false;

		$hash = $data['pass'] ?? null;

		if ($hash === null) // why would it ever be null?
			return false;

		return password_verify($pass, $hash);
	}

	function create_user(string $user, string $pass, ?int $invited_by) {
		$hash = password_hash($pass, PASSWORD_DEFAULT);
		$api_key = sha1(microtime() . 'hunter2');

		$stmt = $this->db->prepare('
			INSERT INTO users (user, pass, api, invited_by) VALUES (:user, :pass, :api, :invited_by)
		');
		$stmt->bindParam(':user', $user, SQLITE3_TEXT);
		$stmt->bindParam(':pass', $hash, SQLITE3_TEXT);
		$stmt->bindParam(':api', $api_key, SQLITE3_TEXT);
		$stmt->bindParam(':invited_by', $invited_by, SQLITE3_INTEGER);
		$stmt->execute();
	}

	function get_user_info(string $user): ?array {
		$user_info = [
			'id' => 0,
			'name' => '',
			'invited_by' => null,
		];

		$stmt = $this->db->prepare('
			SELECT id, api, invited_by FROM users WHERE user = :user
		');
		$stmt->bindParam(':user', $user, SQLITE3_TEXT);
		$result = $stmt->execute();

		$data = $result->fetchArray(SQLITE3_ASSOC);

		if ($data === false)
			return null;

		$user_info['id'] = $data['id'];
		$user_info['name'] = $user;
		$user_info['invited_by'] = $data['invited_by'];

		return $user_info;
	}
}
