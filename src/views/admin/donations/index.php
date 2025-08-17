<?php
$pageTitle = 'Admin';
ob_start();
?>

<h1>Admin Dashboard</h1>

<h2>Pending donations</h2>

<?php foreach ($donations as $donation): ?>
	<div>
		<p>Donation ID: <?= htmlspecialchars($donation->id) ?></p>
		<p>Amount: <?= htmlspecialchars(number_format($donation->amount, 2)) ?> EGP</p>
		<p>Donor: <?= htmlspecialchars($donation->donor->name ?: $donation->donor->email) ?></p>

		<button
			hx-patch="/admin/donations/<?= $donation->id ?>"
			hx-target="closest div"
			hx-swap="outerHTML">
			Accept
		</button>

		<button
			hx-delete="/admin/donations/<?= $donation->id ?>"
			hx-target="closest div"
			hx-swap="outerHTML">
			Reject
		</button>

	</div>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
require 'views/layout.php';
