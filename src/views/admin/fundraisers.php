<?php
$pageTitle = 'Fundraisers';
ob_start();
?>

<h1>Fundraisers</h1>

<h2>Create Fundraiser</h2>
<form
	hx-post="/admin/fundraisers"
	hx-target="#fundraisers > tbody"
	hx-swap="beforeend"
	hx-target-error="#error">
	<div id="error"></div>
	<label>
		Title:
		<input type="text" name="title" required autofocus>
	</label>

	<input type="date" name="date" required>

	<button type="submit">Create Fundraiser</button>
</form>

<table id="fundraisers">
	<thead>
		<tr>
			<th>Title</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($fundraisers as $fundraiser): ?>
			<tr>
				<td><?= htmlspecialchars($fundraiser->title) ?></td>
				<td><?= htmlspecialchars($fundraiser->date) ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php
$content = ob_get_clean();
require 'views/layout.php';
