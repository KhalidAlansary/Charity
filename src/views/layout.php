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
		<a href="/volunteers">Volunteers</a>
		<a href="/donors">Donors</a>
		<a href="/beneficiaries">Beneficiaries</a>

		<div class="navbar-end">
			<?php if (isset($_SESSION['user'])): ?>
				<a href="/profile">
					Hello, <?= $_SESSION['user']->name ?: $_SESSION['user']->email ?>
				</a>
				<a href="/logout">Log out</a>
			<?php else: ?>
				<a href="/login">Log in</a>
				<a href="/signup">Sign up</a>
			<?php endif; ?>
		</div>

	</nav>
	<main><?= $content ?></main>
</body>

</html>
