<?php
$pageTitle = 'Login';
ob_start();
?>

<h1>Login</h1>
<form hx-post="/login" hx-target-error="find .text-error" x-data="{ show: false }">
	<label>
		Email
		<input type="email" name="email" autocomplete="email" autofocus required>
	</label>

	<label>
		Password
		<input
			:type="show ? 'text' : 'password'"
			type="password"
			name="password"
			autocomplete="current-password"
			required>
		<button
			@click="show = !show"
			type="button"
			:aria-label="show ? 'Hide password' : 'Show password'">
			<i data-lucide="eye-off" x-show="!show"></i>
			<i data-lucide="eye" x-show="show"></i>
		</button>
	</label>

	<div class="text-error"></div>

	<button type="submit">Login</button>
	<button type="reset" @click="show = false">Reset</button>
	<a href="/signup">
		Don't have an account? Sign up here.
	</a>
</form>

<?php
$content = ob_get_clean();
require 'views/layout.php';
