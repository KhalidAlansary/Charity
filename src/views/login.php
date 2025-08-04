<?php
$pageTitle = 'Login';
ob_start();
?>

<h1>Login</h1>
<form hx-post="/<?= $user_type ?>/login" hx-target="#form-error">
	<label>
		Email: <input type="email" name="email" autofocus required>
	</label>

	<label>
		Password: <input type="password" name="password" minlength="8" required>
	</label>

	<div id="form-error"></div>

	<button type="submit">Login</button>
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
