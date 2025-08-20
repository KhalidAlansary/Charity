<?php
$pageTitle = 'Login';
ob_start();
?>

<h1>Login</h1>
<form hx-post="/login" hx-target-error="#form-error" x-data="{ show: false }">
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
			:type="show ? 'text' : 'password'"
			type="password"
			name="password"
			autocomplete="current-password"
			required>
	</label>

	<button
		@click="show = !show"
		type="button"
		class="icon-btn"
		:aria-label="show ? 'Hide password' : 'Show password'">
		<i data-lucide="eye-off" x-show="!show"></i>
		<i data-lucide="eye" x-show="show"></i>
	</button>

	<div id="form-error"></div>

	<button type="submit">Login</button>
	<button type="reset" @click="show = false">Reset</button>
</form>

<?php
$content = ob_get_clean();
require 'views/layout.php';
