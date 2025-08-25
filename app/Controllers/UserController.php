<?php

namespace App\Controllers;

use App\Controllers\AuthController;
use App\Models\User;
use App\Helpers\Logger;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class UserController {
    private $userModel;
    private $table = 'users'; // اسم الجدول للـ Logger

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    // جلب المستخدمين مع Pagination و Search
    public function index() {
        AuthController::check();

        $search = $_GET['search'] ?? '';
        $perPage = $_GET['per_page'] ?? 2;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max($currentPage, 1);
        $offset = ($currentPage - 1) * $perPage;

        if ($search) {
            $users = $this->userModel->searchPaginated($search, $perPage, $offset);
            $total = $this->userModel->countSearch($search);
            Logger::log($this->table, "تم البحث عن المستخدمين بالكلمة: '$search'", "INFO");
        } else {
            $users = $this->userModel->allPaginated($perPage, $offset);
            $total = $this->userModel->countAll();
            Logger::log($this->table, "تم جلب جميع المستخدمين، صفحة $currentPage", "INFO");
        }

        $totalPages = ceil($total / $perPage);

        echo json_encode([
            'data' => $users,
            'pagination' => [
                'current_page' => $currentPage,
                'per_page' => (int)$perPage,
                'total_pages' => $totalPages,
                'total' => $total
            ]
        ]);
        exit;
    }

    // جلب مستخدم واحد
    public function show($id) {
        AuthController::check();
        $user = $this->userModel->find($id);
        Logger::log($this->table, "تم جلب بيانات المستخدم بالمعرف: $id", "INFO");

        echo json_encode(['data' => $user]);
        exit;
    }

    // إنشاء مستخدم جديد
    public function store() {
        AuthController::check();
        $input = json_decode(file_get_contents("php://input"), true);
        $email = $input['email'] ?? '';

        $data = [
            'name' => $input['name'] ?? '',
            'username' => $input['username'] ?? '',
            'password' => isset($input['password']) ? password_hash($input['password'], PASSWORD_DEFAULT) : '',
            'email' => $email,
            'description' => $input['description'] ?? '',
            'status' => isset($input['status']) && $input['status'] ? 'active' : 'inactive',
            'role' => $input['role'] ?? 'user'
        ];

        $newId = $this->userModel->create($data);

        if (!$newId) {
            Logger::log($this->table, "فشل إنشاء المستخدم، البريد موجود مسبقًا: $email", "ERROR");
            echo json_encode([
                'status' => 'error',
                'message' => '❌ البريد الإلكتروني موجود مسبقًا. اختر بريدًا آخر.'
            ]);
        } else {
            Logger::log($this->table, "تم إنشاء مستخدم جديد بالبريد: $email", "INFO");
            echo json_encode([
                'status' => 'success',
                'message' => '✅ تم إضافة المستخدم بنجاح',
                'action' => 'insert',
                'id' => $newId,
                'data' => $data
            ]);
        }
        exit;
    }

    // تحديث مستخدم
    public function update($id) {
        AuthController::check();
        $input = json_decode(file_get_contents("php://input"), true);

        $data = [
            'name' => $input['name'] ?? '',
            'username' => $input['username'] ?? '',
            'email' => $input['email'] ?? '',
            'description' => $input['description'] ?? '',
            'status' => isset($input['status']) && $input['status'] ? 'active' : 'inactive',
            'role' => $input['role'] ?? 'user'
        ];

        if (!empty($input['password'])) {
            $data['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        Logger::log($this->table, "تم تحديث بيانات المستخدم بالمعرف: $id", "INFO");

        echo json_encode([
            'status' => 'success',
            'message' => '✅ تم تعديل المستخدم بنجاح',
            'action' => 'update',
            'id' => $id,
            'data' => $data
        ]);
        exit;
    }

    // حذف مستخدم (Soft Delete)
    public function destroy($id) {
        $this->userModel->softDelete($id);
        Logger::log($this->table, "تم حذف المستخدم مؤقتًا بالمعرف: $id", "WARNING");

        echo json_encode(['status' => 'success', 'message' => '🗑️ تم حذف المستخدم مؤقتًا']);
        exit;
    }

    // استعادة مستخدم محذوف
    public function restore($id) {
        AuthController::check();
        $this->userModel->restore($id);
        Logger::log($this->table, "تم استعادة المستخدم المحذوف بالمعرف: $id", "INFO");

        echo json_encode(['status' => 'success', 'message' => '✅ تم استعادة المستخدم']);
        exit;
    }

    // حذف مستخدم نهائي
    public function destroyPermanent($id) {
        AuthController::check();
        $this->userModel->destroy($id);
        Logger::log($this->table, "تم حذف المستخدم نهائيًا بالمعرف: $id", "WARNING");

        echo json_encode(['status' => 'success', 'message' => '🚫 تم حذف المستخدم نهائيًا']);
        exit;
    }

    // عرض المستخدمين المحذوفين
    public function deleted() {
        AuthController::check();
        $deletedUsers = $this->userModel->allDeleted();
        Logger::log($this->table, "تم جلب جميع المستخدمين المحذوفين", "INFO");

        header('Content-Type: application/json');
        echo json_encode(['data' => $deletedUsers]);
        exit;
    }
}
