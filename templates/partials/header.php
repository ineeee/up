<header>
	<div class="inner">
		<h1>upload files</h1>

		<nav>
			<a <?= ($page['name'] === 'home') ? 'class="active" ' : '' ?>href="./">home</a>
			<a <?= ($page['name'] === 'about') ? 'class="active" ' : '' ?>href="about.php">about</a>

			<?php if ( $page['logged in'] ) { ?>
			<a <?= ($page['name'] === 'account') ? 'class="active" ' : '' ?>href="account.php">account</a>
			<a <?= ($page['name'] === 'files') ? 'class="active" ' : '' ?>href="files.php">files</a>
			<a href="logout.php">log out</a>
			<?php } else { ?>
			<a <?= ($page['name'] === 'login') ? 'class="active" ' : '' ?>href="login.php">log in</a>
			<?php } ?>
		</nav>
	</div>
</header>
