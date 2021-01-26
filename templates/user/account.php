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
			<p>You are <strong><?= htmlspecialchars($page['session']['user']) ?></strong> (<code>#<?= $page['session']['id'] ?></code>)</p>
			<p>You've used ? mb and the server has <?= $page['space left'] ?> left</p>

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

			<?php if (count($page['user invites']) === 0) { ?>
				<p>You haven't created any invites yet.</p>
			<?php } else { ?>
				<ul>
				<?php foreach ($page['user invites'] as $inv) { ?>
					<li><code><?= $inv['key'] ?></code> (<?= date('Y-m-d H:i', $inv['timestamp']) ?>)</li>
				<?php } ?>
				</ul>
			<?php } ?>

			<br>
			<h2>Password</h2>
			<p>You can change your password here (not really, not yet)</p>
			<form action="account.php" method="post">
				<input type="password" name="pass">
				<input type="hidden" name="action" value="change password">
				<button type="submit">Change</button>
			</form>
		</div>
	</main>

	<?php echo_template('partials/footer', $page); ?>
</body>
</html>
