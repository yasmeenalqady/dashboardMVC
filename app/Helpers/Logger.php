<?php

namespace App\Helpers;

class Logger
{
    private static $logDir = __DIR__ . '/../../logs/'; // مجلد اللوجات

    /**
     * تسجيل رسالة في ملف اللوج الخاص بالجدول
     *
     * @param string $table اسم الجدول
     * @param string $message نص الرسالة
     * @param string $level مستوى الرسالة (INFO, WARNING, ERROR, DEBUG)
     */
    public static function log(string $table, string $message, string $level = 'INFO')
    {
        // التأكد من وجود مجلد logs
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }

        // ملف اللوج الخاص بالجدول
        $logFile = self::$logDir . $table . '.log';

        $time = date('Y-m-d H:i:s');
        $logMessage = "[$time] [$level] $message" . PHP_EOL;

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
