<header>
	<div class="inner">
		<h1>upload files</h1>

		<nav>
			<a <?= ($page['name'] === 'home') ? 'class="active" ' : '' ?>href=".">home</a>
			<a <?= ($page['name'] === 'about') ? 'class="active" ' : '' ?>href="about.php">about</a>
			<?php if ( isset($page['session']['logged in']) ) { ?>
			<a href="logout.php">log out</a>
			<?php } else { ?>
			<a <?= ($page['name'] === 'login') ? 'class="active" ' : '' ?>href="login.php">log in</a>
			<?php } ?>
		</nav>
	</div>
</header>
