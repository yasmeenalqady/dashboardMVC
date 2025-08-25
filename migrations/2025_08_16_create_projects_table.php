<?php
return [
    'table' => 'projects',
    'columns' => [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'image' => 'VARCHAR(255) DEFAULT NULL', // رابط أو اسم ملف الصورة
        'name' => 'VARCHAR(150) NOT NULL', // اسم المشروع
        'manager' => 'VARCHAR(100) NOT NULL', // مدير المشروع
        'field' => 'VARCHAR(100) NOT NULL', // المجال
        'start_date' => 'DATE NOT NULL', // تاريخ البداية
        'end_date' => 'DATE DEFAULT NULL', // تاريخ الانتهاء
        'status' => 'VARCHAR(20) NOT NULL', // الحالة
        'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        'deleted_at' => 'TIMESTAMP NULL DEFAULT NULL' // soft delete
    ]
];
