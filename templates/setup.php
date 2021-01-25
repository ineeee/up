<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="css/page.css">
	<title>setup - up</title>
</head>
<body class="single-page">
	<?php echo_template('partials/header', $page); ?>

	<main>
		<div class="inner">
			<h2>Setup</h2>

			<?php if ($page['needs setup'] === true) { ?>
			<p>Configure the first user account</p>

			<form action="register.php" method="POST">
				<label>
					User
					<input type="text" name="user" minlength="4">
				</label>

				<label>
					Pass (min 8 chars)
					<input type="password" name="pass" minlength="8">
				</label>

				<input type="hidden" name="key" value="<?= $page['invite key']; ?>">

				<button type="submit">Register</button>
			</form>

			<?php } else { ?>
			<p>The setup is over. Go away~</p>
			<?php } ?>
		</div>
	</main>

	<?php echo_template('partials/footer', $page); ?>
</body>
</html>
