<?php
$pageTitle = 'Admin';
ob_start();
?>

<h1>Admin Dashboard</h1>

<a href="/admin/fundraisers" class="block">Fundraisers</a>
<a href="/admin/donations" class="block">Donations</a>

<?php
$content = ob_get_clean();
require 'views/layout.php';
