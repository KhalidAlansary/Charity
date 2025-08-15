<?php
require_once 'models/users.php';
$pageTitle = 'Donors';
ob_start();
?>

<h1>Donors</h1>

<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>Donation Method</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($donors as $donor): ?>
			<tr>
				<td><?= htmlspecialchars($donor->id) ?></td>
				<td><?= htmlspecialchars($donor->name ?? '') ?></td>
				<td><?= htmlspecialchars($donor->email) ?></td>
				<td><?= htmlspecialchars($donor->donationMethod) ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<a href="/donors/donate/">Donate</a>

<?php
$content = ob_get_clean();
require 'views/layout.php';
