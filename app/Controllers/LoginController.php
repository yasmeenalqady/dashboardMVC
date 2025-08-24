<?php
namespace App\Controllers;

use App\Models\User;
use App\Helpers\Logger;

class LoginController {
    private $userModel;
    private $table = 'login'; // ملف لوج منفصل لتسجيل الدخول

    public function __construct($db) {
        $this->userModel = new User($db);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // تسجيل الدخول
    public function loginprocess() {
    // قراءة بيانات JSON من الطلب
    $input = json_decode(file_get_contents('php://input'), true);
    $login = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    // البحث عن المستخدم بالبريد الإلكتروني أو اسم المستخدم
    $user = $this->userModel->findByEmailOrUsername($login);

    if ($user && password_verify($password, $user['password'])) {
        // تخزين بيانات المستخدم في الجلسة (session)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
          $_SESSION['user'] = [
       'id' => $user['id'],
       'username' => $user['username'],
       'email' => $user['email'],
       'role' => $user['role']
        ];

        // تسجيل الدخول في ملف اللوج
        Logger::log($this->table, "تم تسجيل الدخول بنجاح للبريد/المستخدم: $login", "INFO");

        // الرد على العميل بالنجاح وبيانات المستخدم
        $this->respond([
            'status' => 'success',
            'message' => '✅ تم تسجيل الدخول بنجاح',
            'user' => $_SESSION['user']
        ]);
    } else {
        // تسجيل فشل الدخول
        Logger::log($this->table, "فشل تسجيل الدخول للبريد/المستخدم: $login", "ERROR");

        // الرد بالخطأ
        $this->respond([
            'status' => 'error',
            'message' => '❌ البريد الإلكتروني / اسم المستخدم أو كلمة المرور غير صحيحة.'
        ], 401);
    }
}


    // تسجيل الخروج
    public function logout() {
        $user = $_SESSION['user']['email'] ?? 'غير معروف';
        session_destroy();

        Logger::log($this->table, "تم تسجيل الخروج للمستخدم: $user", "INFO");

        $this->respond(['status' => 'success', 'message' => '🚪 تم تسجيل الخروج بنجاح']);
    }

    // دالة مساعدة لإرسال JSON
    private function respond($data, $status = 200) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
