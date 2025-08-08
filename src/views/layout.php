<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($pageTitle ?? 'Charity') ?></title>
	<link rel="stylesheet" href="/public/styles.css">
</head>

<body>
	<nav>
		<ul>
			<li><a href="/volunteers">Volunteers</a></li>
			<li><a href="/donors">Donors</a></li>
			<li><a href="/beneficiaries">Beneficiaries</a></li>
			<li class="user-menu">
				<?php if (isset($_SESSION['user'])): ?>
					<span>Hello, <?= $_SESSION['user']->name ?></span>
					<a href="/logout">Log out</a>
				<?php else: ?>
					<a href="/login">Log in</a>
					<a href="/signup">Sign up</a>
				<?php endif; ?>
			</li>
		</ul>
	</nav>
	<main><?= $content ?></main>
	<script
		src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.6/dist/htmx.min.js"
		integrity="sha384-Akqfrbj/HpNVo8k11SXBb6TlBWmXXlYQrCSqEWmyKJe+hDm3Z/B2WVG4smwBkRVm"
		crossorigin="anonymous"></script>
</body>

</html>
