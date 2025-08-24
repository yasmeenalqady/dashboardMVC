<?php
require_once __DIR__ . '/app/core/Database.php';

use App\Core\Database;

// إنشاء نسخة من الكلاس
$db = new Database();
$pdo = $db->connect(); // الآن $pdo جاهز للاستخدام

$migrations = glob(__DIR__ . '/migrations/*.php');

foreach ($migrations as $file) {
    $migration = include $file;

    if (!is_array($migration) || !isset($migration['table']) || !isset($migration['columns'])) {
        echo "تخطي الملف: $file (ليس Migration صالح)\n";
        continue;
    }

    $table = $migration['table'];
    $columns = $migration['columns'];

    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
    if ($stmt->rowCount() == 0) {
        $columns_sql = [];
        foreach ($columns as $name => $type) {
            $columns_sql[] = "$name $type";
        }
        $sql = "CREATE TABLE $table (" . implode(", ", $columns_sql) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $pdo->exec($sql);
        echo "تم إنشاء الجدول $table\n";
    } else {
        echo "الجدول $table موجود بالفعل\n";
    }
}
