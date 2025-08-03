<?php
ob_start();
?>

<h1>This is the homepage</h1>

<?php
$content = ob_get_clean();
require 'views/layout.php';
