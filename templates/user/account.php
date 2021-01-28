<!DOCTYPE html>
<html>
<head>
	<?php echo_template('partials/head'); ?>
	<title>account - up</title>
</head>
<body class="single-page">
	<?php echo_template('partials/header', $page); ?>

	<main>
		<div class="inner">
			<h2>Your account</h2>
			<p>You are <strong><?= htmlspecialchars($user['user']) ?></strong> (<code>#<?= $user['id'] ?></code>)</p>
			<p>You've used ? mb and the server has <?= $page['space left'] ?> left</p>

			<h2>API access</h2>
			<p>Your api key is <?= $user['api key'] ?></p>
			<details>
				<summary>ShareX config</summary>
				<pre class="code"><?= json_encode([
					'Name' => $config->get('site name') . ' - ' . $user['user'],
					'DestinationType' => 'None',
					'RequestType' => 'POST',
					'RequestURL' => $config->get('public url') . 'api/upload.php',
					'FileFormName' => 'file',
					'Headers' => [
					  'Authorization' => $user['api key']
					],
					'ResponseType' => 'Text'
				], JSON_PRETTY_PRINT) ?></pre>
			</details>

			<br>
			<h2>Invites</h2>
			<form action="account.php" method="post">
				<input type="hidden" name="action" value="create invite">
				<button type="submit">Create new invite</button>
			</form>

			<?php if ($page['status'] === 'created invite') { ?>
				<p>An invite key was just created</p>
				<p>Link people <a href="register.php?key=<?= $page['new invite key'] ?>">here</a>.
			<?php } ?>

			<?php if (count($user_invites) === 0) { ?>
				<p>You haven't created any invites yet.</p>
			<?php } else { ?>
				<ul>
				<?php foreach ($user_invites as $inv) { ?>
					<li><code><?= $inv['key'] ?></code> (<?= date('Y-m-d H:i', $inv['timestamp']) ?>)</li>
				<?php } ?>
				</ul>
			<?php } ?>

			<br>
			<h2>Password</h2>
			<?php if ($page['status'] === 'new pass too short') { ?>
			<p>The given password is too short. Min length is 8 characters</p>
			<?php } else if ($page['status'] === 'pass changed') { ?>
			<p>Your password was <strong>NOT changed (not yet coded)</strong></p>
			<?php } else { ?>
			<p>You can change your password here (not really, not yet)</p>
			<?php } ?>

			<form action="account.php" method="post">
				<input type="password" name="pass" minlength="8">
				<input type="hidden" name="action" value="change password">
				<button type="submit">Change</button>
			</form>
		</div>
	</main>

	<?php echo_template('partials/footer', $page); ?>
</body>
</html>
