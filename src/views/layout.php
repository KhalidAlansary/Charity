<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($pageTitle ?? 'Charity') ?></title>
	<link rel="stylesheet" href="/public/css/styles.css">
	<script src="/public/js/app.js" defer></script>
</head>

<body hx-ext="response-targets">
	<nav>
		<ul>
			<li><a href="/volunteers">Volunteers</a></li>
			<li><a href="/donors">Donors</a></li>
			<li><a href="/beneficiaries">Beneficiaries</a></li>
			<li class="user-menu">
				<?php if (isset($_SESSION['user'])): ?>
					<span>Hello, <?= $_SESSION['user']->name ?: $_SESSION['user']->email ?></span>
					<a href="/logout">Log out</a>
				<?php else: ?>
					<a href="/login">Log in</a>
					<a href="/signup">Sign up</a>
				<?php endif; ?>
			</li>
		</ul>
	</nav>
	<main><?= $content ?></main>
</body>

</html>
