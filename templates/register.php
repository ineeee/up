<!DOCTYPE html>
<html>
<head>
	<?php echo_template('partials/head'); ?>
	<title>register - up</title>
</head>
<body class="single-page">
	<?php echo_template('partials/header', $page); ?>

	<main>
		<div class="inner">
			<h2>Register</h2>

			<?php if ($data['status'] === 'no key') { ?>
			<p>This is invite only.</p>
			<p>Go away~</p>

			<?php } else if ($data['status'] === 'invalid key') { ?>
			<p>That invite key isn't valid</p>
			<p>Go away~</p>

			<?php } else if ($data['status'] === 'expired key') { ?>
			<p>That invite key expired</p>
			<p>Go away~</p>

			<?php } else if ($data['status'] === 'name taken') { ?>
			<p>That user name is taken</p>
			<p>Go back and try something else</p>

			<?php } else if ($data['status'] === 'valid key') { ?>
			<p>You've been invited by <?= $data['user']; ?>!</p>

			<form action="register.php" method="POST">
				<label>
					User
					<input type="text" name="user" minlength="4">
				</label>

				<label>
					Pass (min 8 chars)
					<input type="password" name="pass" minlength="8">
				</label>

				<input type="hidden" name="key" value="<?= $data['key']; ?>">

				<button type="submit">Register</button>
			</form>

			<?php } else if ($data['status'] === 'missing stuff') { ?>
			<p>Your registration couldn't be completed because you didn't provide enough info.</p>
			<p>Go back and fill the form, nerd</p>

			<?php } else if ($data['status'] === 'success') { ?>
			<p>Your registration is complete</p>
			<p>You can now <a href="login.php">log in</a></p>

			<?php } else { ?>
			<p>idk</p>
			<?php } ?>
		</div>
	</main>

	<?php echo_template('partials/footer', $page); ?>
</body>
</html>
