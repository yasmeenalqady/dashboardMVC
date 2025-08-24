<?php
namespace App\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthController {
  public static function check() {
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => '⚠️ يجب تسجيل الدخول أولاً.'
        ]);
        exit; // يوقف التنفيذ بعد إرسال الرد مباشرة
    }
}




    public static function isAdmin() {
        self::check();
        return $_SESSION['user']['role'] === 'admin';
    }

    public static function logout() {
        session_destroy();
        header("Location: /MVC/public/login");
        exit;
    }
}
