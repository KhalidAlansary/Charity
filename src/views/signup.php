<?php
$pageTitle = 'Sign Up';
ob_start();
?>

<h1>Sign Up</h1>
<form hx-post="/signup" hx-target-error="find .text-error" x-data="{ show: false }">
	<label>
		Name: <input type="text" name="name" autocomplete="name" autofocus>
	</label>

	<label>
		Email: <input type="email" name="email" autocomplete="email" required>
	</label>

	<label>
		Password:
		<input
			:type="show ? 'text' : 'password'"
			type="password"
			name="password"
			autocomplete="new-password"
			minlength="8"
			required>
		<button
			@click="show = !show"
			type="button"
			:aria-label="show ? 'Hide password' : 'Show password'">
			<i data-lucide="eye-off" x-show="!show"></i>
			<i data-lucide="eye" x-show="show"></i>
		</button>
	</label>

	<label>
		Type:
		<select name="type" required>
			<option value="admin">Admin</option>
			<option value="volunteer">Volunteer</option>
			<option value="donor">Donor</option>
			<option value="beneficiary">Beneficiary</option>
		</select>
	</label>

	<div class="text-error"></div>

	<button type="submit">Signup</button>
	<button type="reset" @click="show = false">Reset</button>
	<a href="/login">Already have an account? Login here.</a>
</form>

<?php
$content = ob_get_clean();
require 'views/layout.php';
