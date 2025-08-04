<?php
$pageTitle = 'Sign Up';
ob_start();
?>

<h1>Sign Up</h1>
<form hx-post="/<?= $user_type ?>/signup" hx-target="#form-error">
	<label>
		Name: <input type="text" name="name" autofocus>
	</label>

	<label>
		Email: <input type="email" name="email" required>
	</label>

	<label>
		Password: <input type="password" name="password" minlength="8" required>
	</label>

	<div id="form-error"></div>

	<button type="submit">Signup</button>
	<button type="reset">Reset</button>
</form>

<script>
	document.addEventListener("DOMContentLoaded", (event) => {
		document.body.addEventListener('htmx:beforeSwap', function(evt) {
			if (evt.detail.xhr.status === 403) {
				evt.detail.shouldSwap = true;
				evt.detail.isError = false;
			}
		});
	});
</script>

<?php
$content = ob_get_clean();
require 'views/layout.php';
