<?php

namespace App\Controllers;

use App\Controllers\AuthController;
use App\Models\Field;
use App\Helpers\Logger;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class FieldController {
    private $fieldModel;
    private $table = 'fields';

    public function __construct($db) {
        $this->fieldModel = new Field($db);
    }

    // جلب المجالات مع Pagination و Search
    public function index() {
        AuthController::check();

        $search = $_GET['search'] ?? '';
        $perPage = $_GET['per_page'] ?? 5;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max($currentPage, 1);
        $offset = ($currentPage - 1) * $perPage;

        if ($search) {
            $fields = $this->fieldModel->searchPaginated($search, $perPage, $offset);
            $total = $this->fieldModel->countSearch($search);
            Logger::log($this->table, "تم البحث عن المجالات بالكلمة: '$search'", "INFO");
        } else {
            $fields = $this->fieldModel->allPaginated($perPage, $offset);
            $total = $this->fieldModel->countAll();
            Logger::log($this->table, "تم جلب جميع المجالات، صفحة $currentPage", "INFO");
        }

        $totalPages = ceil($total / $perPage);

        echo json_encode([
            'data' => $fields,
            'pagination' => [
                'current_page' => $currentPage,
                'per_page' => (int)$perPage,
                'total_pages' => $totalPages,
                'total' => $total
            ]
        ]);
        exit;
    }

    // جلب مجال واحد
    public function show($id) {
        AuthController::check();
        $field = $this->fieldModel->find($id);
        Logger::log($this->table, "تم جلب بيانات المجال بالمعرف: $id", "INFO");

        echo json_encode(['data' => $field]);
        exit;
    }

    // إنشاء مجال جديد
    public function store() {
        AuthController::check();
        $input = json_decode(file_get_contents("php://input"), true);

          $data = [
           'name' => $input['name'] ?? '',
           'description' => $input['description'] ?? '',
           'status' => !empty($input['status']) ? 1 : 0  // تحويل تلقائي إلى 1 أو 0
           ];


        $result = $this->fieldModel->create($data);

        if (!$result) {
            Logger::log($this->table, "فشل إنشاء المجال", "ERROR");
            echo json_encode(['status' => 'error', 'message' => '❌ فشل إنشاء المجال.']);
        } else {
            Logger::log($this->table, "تم إنشاء مجال جديد: {$data['name']}", "INFO");
            echo json_encode(['status' => 'success', 'data' => $data]);
        }
        exit;
    }

    // تحديث مجال
    public function update($id) {
        AuthController::check();
        $input = json_decode(file_get_contents("php://input"), true);

        $data = [
            'name' => $input['name'] ?? '',
            'description' => $input['description'] ?? '',
            // 🚀 هنا أيضاً
            'status' => isset($input['status']) ? (int) $input['status'] : 0
        ];

        $this->fieldModel->update($id, $data);
        Logger::log($this->table, "تم تحديث بيانات المجال بالمعرف: $id", "INFO");

        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    // حذف مجال (Soft Delete)
    public function destroy($id) {
        $this->fieldModel->softDelete($id);
        Logger::log($this->table, "تم حذف المجال مؤقتًا بالمعرف: $id", "WARNING");

        echo json_encode(['status' => 'success']);
        exit;
    }

    // استعادة مجال محذوف مع Pagination
    public function restore($id) {
        AuthController::check();
        $this->fieldModel->restore($id);
        Logger::log($this->table, "تم استعادة المجال المحذوف بالمعرف: $id", "INFO");

        echo json_encode(['status' => 'success']);
        exit;
    }

    // حذف مجال نهائي
    public function destroyPermanent($id) {
        AuthController::check();
        $this->fieldModel->destroy($id);
        Logger::log($this->table, "تم حذف المجال نهائيًا بالمعرف: $id", "WARNING");

        echo json_encode(['status' => 'success']);
        exit;
    }

    // عرض المجالات المحذوفة مع Pagination
     public function deleted() {
    AuthController::check(); // تأكد من تسجيل الدخول

    // جلب جميع المجالات المحذوفة بدون Pagination
    $deletedFields = $this->fieldModel->allDeleted();

    // إعادة البيانات بصيغة JSON
    echo json_encode([
        'data' => $deletedFields
    ]);
    exit;
}

}
