<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="css/page.css">
	<title>up</title>
</head>
<body class="single-page">
	<?php echo_template('partials/header', $page); ?>

	<main>
		<div class="inner">
			<h2>Home</h2>

			<p>welcome, <?= $page['session']['user'] ?? 'nobody' ?></p>
		</div>
	</main>

	<?php echo_template('partials/footer', $page); ?>
</body>
</html>
