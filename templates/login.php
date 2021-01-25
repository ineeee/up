<!DOCTYPE html>
<html>
<head>
	<?php echo_template('partials/head'); ?>
	<title>log in - up</title>
</head>
<body class="single-page">
	<?php echo_template('partials/header', $page); ?>

	<main>
		<div class="inner">
			<h2>Log in</h2>

			<?php if ($page['status'] === 'wrong creds') { ?>
			<p>Whatever you entered is wrong</p>
			<?php } else if ($page['status'] === 'success') { ?>
			<p>All is good, redirecting</p>
			<?php } ?>

			<?php if (isset($page['session']['logged in'])) { ?>
			<p>You're already logged in as <?= $page['session']['user'] ?>
			<?php } ?>

			<form action="login.php" method="POST">
				<label>
					User
					<input type="text" name="user">
				</label>

				<label>
					Pass
					<input type="password" name="pass">
				</label>

				<button type="submit">Enter</button>
			</form>
		</div>
	</main>

	<?php echo_template('partials/footer', $page); ?>
</body>
</html>
