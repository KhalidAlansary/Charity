<?php
require_once 'models/models.php';
$pageTitle = 'Volunteers';
ob_start();
?>

<h1>Volunteers</h1>

<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>Skills</th>
			<th>Availability</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($volunteers as $volunteer): ?>
			<tr>
				<td><?= htmlspecialchars($volunteer->id) ?></td>
				<td><?= htmlspecialchars($volunteer->name ?? "") ?></td>
				<td><?= htmlspecialchars($volunteer->email) ?></td>
				<td><?= htmlspecialchars($volunteer->skills ?? "") ?></td>
				<td><?= htmlspecialchars($volunteer->availability ?? "") ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php
$content = ob_get_clean();
require 'views/layout.php';
