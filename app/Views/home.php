<?php
$title = "الواجهة الرئيسية  ";

ob_start();
?>

<h1 class="text-3xl font-bold mb-6">Home page</h1>





<?php
$content = ob_get_clean();
include __DIR__ . '/layout/main.php';
