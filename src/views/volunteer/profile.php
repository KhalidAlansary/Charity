<?php
$pageTitle = 'Volunteer Profile';
ob_start();
?>
<h1>Hello, <?= $volunteer->name ?? $volunteer->email ?></h1>

<?php
$content = ob_get_clean();
require 'views/layout.php';
