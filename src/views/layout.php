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
			<li><a href="/volunteer">Volunteer</a></li>
			<li><a href="/donor">Donor</a></li>
			<li><a href="/beneficiary">Beneficiary</a></li>
		</ul>
	</nav>
	<main><?= $content ?></main>
	<script
		src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.6/dist/htmx.min.js"
		integrity="sha384-Akqfrbj/HpNVo8k11SXBb6TlBWmXXlYQrCSqEWmyKJe+hDm3Z/B2WVG4smwBkRVm"
		crossorigin="anonymous"></script>
</body>

</html>
