<?php
return [
    'table' => 'fields',
    'columns' => [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'name' => 'VARCHAR(100) NOT NULL',
        'description' => 'TEXT',
        'status' => 'TINYINT(1) DEFAULT 1',
        'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        'deleted_at' => 'TIMESTAMP NULL DEFAULT NULL'  // <-- حقل soft delete
    ]
];
