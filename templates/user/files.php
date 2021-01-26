<!DOCTYPE html>
<html>
<head>
	<?php echo_template('partials/head'); ?>
	<title>files - up</title>
</head>
<body class="single-page">
	<?php echo_template('partials/header', $page); ?>

	<main>
		<div class="inner">
			<h2>Your files</h2>
			<p>its just a list right now</p>

			<form method="GET">
				<table style="width: 100%">
					<tr>
						<th>x</th>
						<th>name</th>
						<th>description</th>
					</tr>
					<?php foreach ($page['files'] as $file) { ?>
					<tr>
						<td><input type="checkbox" name="file_<?= $file['id'] ?>"></td>
						<td><?= htmlspecialchars($file['filename']) ?></td>
						<td><?= htmlspecialchars($file['description']) ?></td>
					</tr>
					<?php } ?>
				</table>
			</form>
		</div>
	</main>

	<?php echo_template('partials/footer', $page); ?>
</body>
</html>
