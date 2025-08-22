<?php
$pageTitle = 'Profile';
ob_start();

function subscriptionField($value = ''): string
{
	return <<<HTML
	<div>
		<input type="text" name="subscriptions[]" value="{$value}">
		<button
			class="btn-soft btn-error"
			type="button"
			onclick="this.parentElement.remove()">
			<i data-lucide="trash-2"></i>
		</button>
	</div>
	HTML;
}

?>
<h1>Hello, <?= $user->name ?: $user->email ?></h1>

<form hx-patch="/profile" hx-swap="afterbegin" hx-target-error="find .text-error">
	<h1>Subscriptions</h1>

	<?php foreach ($user->subscriptions as $subscription): ?>
		<?= subscriptionField(htmlspecialchars($subscription)) ?>
	<?php endforeach; ?>

	<template><?= subscriptionField() ?></template>

	<button
		class="btn-soft btn-secondary"
		type="button"
		onclick="addSubscription(this)">
		Add Subscription
	</button>

	<div class="text-error"></div>

	<button type="submit">Save</button>
</form>

<script>
	function addSubscription(button) {
		const template = button.parentElement.querySelector('template');
		const clone = template.content.cloneNode(true);
		window.lucide.createIcons({
			root: clone,
			icons: window.lucide.icons
		});
		template.before(clone);
	}
</script>

<?php
$content = ob_get_clean();
require 'views/layout.php';
