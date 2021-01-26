<!DOCTYPE html>
<html>
<head>
	<?php echo_template('partials/head'); ?>
	<link rel="stylesheet" type="text/css" href="assets/uppy.min.css">
	<title>up</title>
</head>
<body class="single-page">
	<?php echo_template('partials/header', $page); ?>

	<main>
		<div class="inner">
			<h2>Hello, <?= $page['session']['user'] ?></h2>
			<br>

			<div id="uppy"></div>
		</div>
	</main>

	<?php echo_template('partials/footer', $page); ?>

	<!-- ugh, 300 kb of javascript -->
	<script src="assets/bundle.min.js"></script>
	<script>
		const uppy = Uppy.Core({
			debug: true,
			autoProceed: false,
			restrictions: {
				maxFileSize: <?= $page['uppy']['max size'] ?>
			}
		});

		uppy.use(Uppy.Dashboard, {
			inline: true,
			target: '#uppy',
			width: '100%',
			height: 600,

			note: 'Up to <?= $page['uppy']['max size'] / 1024 / 1024 ?> mb',

			showLinkToFileUploadResult: true,
			showProgressDetails: true,
		});

		uppy.use(Uppy.XHRUpload, {
			endpoint: '<?= $page['uppy']['endpoint'] ?>',
			method: 'post',
			formData: true,
			fieldName: 'file',
			headers: {
				'Authorization': '<?= $page['session']['api key']; ?>'
			},
			bundle: false,
			getResponseData: function(responseText, response) {
				return {
					url: responseText
				};
			},
			getResponseError: function(responseText, response) {
				return responseText;
			}
		});
	</script>
</body>
</html>
