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

	<button type="button" popovertarget="cally-popover" class="input input-border" id="cally" style="anchor-name:--cally">
		Pick a date
	</button>
	<div popover id="cally-popover" class="dropdown bg-base-100 rounded-box shadow-lg" style="position-anchor:--cally">
		<calendar-date class="cally" onchange="updateDate(this)">
			<i data-lucide="chevron-left" aria-label="Previous" slot="previous"></i>
			<i data-lucide="chevron-right" aria-label="Next" slot="next"></i>
			<calendar-month></calendar-month>
		</calendar-date>
	</div>
	<input type="hidden" name="date" required>

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

<script>
	function updateDate(datePicker) {
		const dateInput = document.querySelector('input[name="date"]');
		const dateButton = document.getElementById('cally');
		dateInput.value = datePicker.value;
		dateButton.textContent = new Date(datePicker.value).toLocaleDateString();
	}
</script>

<?php
$content = ob_get_clean();
require 'views/layout.php';
