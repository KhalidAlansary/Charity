<?php
$pageTitle = 'Login';
ob_start();
?>

<h1>Login</h1>
<form hx-post="/login" hx-target-error="#form-error">
	<label>
		Email:
		<input
			type="email"
			name="email"
			autocomplete="email"
			autofocus
			required>
	</label>

	<label>
		Password:
		<input
			type="password"
			name="password"
			autocomplete="current-password"
			required>
	</label>

	<div id="form-error"></div>

	<button type="submit">Login</button>
	<button type="reset">Reset</button>
</form>

<?php
$content = ob_get_clean();
require 'views/layout.php';
