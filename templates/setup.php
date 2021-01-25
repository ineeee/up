<!DOCTYPE html>
<html>
<head>
	<?php echo_template('partials/head'); ?>
	<title>setup - up</title>
</head>
<body class="single-page">
	<?php echo_template('partials/header', $page); ?>

	<main>
		<div class="inner">
			<h2>Setup</h2>

			<?php if ($page['needs setup'] === true) { ?>
			<p>These are the currently configured settings:</p>

			<table>
				<tr>
					<td>max file size</td>
					<td><pre><code><?= $page['config']->get('max file size') ?></code></pre></td>
				</tr>
				<tr>
					<td>invite expiration time</td>
					<td><pre><code><?= $page['config']->get('invite expiration time') ?></code></pre></td>
				</tr>
				<tr>
					<td>file slug length</td>
					<td><pre><code><?= $page['config']->get('file slug length') ?></code></pre></td>
				</tr>
				<tr>
					<td>public url</td>
					<td><pre><code><?= $page['config']->get('public url') ?></code></pre></td>
				</tr>
				<tr>
					<td>upload dir</td>
					<td><pre><code><?= $page['config']->get('upload dir') ?></code></pre></td>
				</tr>
			</table>
			<br>

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
