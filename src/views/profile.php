<?php
$pageTitle = 'Profile';
ob_start();
?>
<h1>Hello, <?= $user->name ?? $user->email ?></h1>

<?php
$content = ob_get_clean();
require 'views/layout.php';
