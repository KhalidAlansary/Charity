<?php
$pageTitle = 'Donate';
ob_start();
?>

<h1>Donate</h1>

<form hx-post="/donors/donate" hx-swap="outerHTML">
	<label>
		Donation Amount:
		<input type="number" id="amount" name="amount" required min="0.01" step="0.01" autofocus>
	</label>

	<button type="submit">Donate</button>
</form>

<?php
$content = ob_get_clean();
require 'views/layout.php';
