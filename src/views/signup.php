<?php
$pageTitle = 'Sign Up';
ob_start();
?>

<h1>Sign Up</h1>
<form hx-post="/signup" hx-target-error="#form-error">
	<label>
		Name: <input type="text" name="name" autocomplete="name" autofocus>
	</label>

	<label>
		Email: <input type="email" name="email" autocomplete="email" required>
	</label>

	<label>
		Password:
		<input
			type="password"
			name="password"
			autocomplete="new-password"
			minlength="8"
			required>
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

	<div id="form-error"></div>

	<button type="submit">Signup</button>
	<button type="reset">Reset</button>
</form>

<?php
$content = ob_get_clean();
require 'views/layout.php';
