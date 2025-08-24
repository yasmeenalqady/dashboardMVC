<?php
return [
    'table' => 'users',
    'columns' => [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'name' => 'VARCHAR(100) NOT NULL',
        'username' => 'VARCHAR(100) NOT NULL',
        'password' => 'VARCHAR(255) NOT NULL', // الافضل 255 للتخزين بالـ hash
        'email' => 'VARCHAR(100) NOT NULL',
        'role' => "VARCHAR(50) DEFAULT 'user'", // <-- حقل الدور (admin, user, editor...)
        'description' => 'TEXT',
        'status' => 'TINYINT(1) DEFAULT 1',
        'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        'deleted_at' => 'TIMESTAMP NULL DEFAULT NULL'  // <-- soft delete
    ]
];
