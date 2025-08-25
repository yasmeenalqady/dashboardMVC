<?php

namespace App\Controllers;

use App\Controllers\AuthController;
use App\Models\Project;
use App\Helpers\Logger;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ProjectController {
    private $projectModel;
    private $table = 'projects';

    public function __construct($db) {
        $this->projectModel = new Project($db);
    }

    // جلب المشاريع مع البحث و Pagination
    public function index() {
        $search = $_GET['search'] ?? '';
        $perPage = $_GET['per_page'] ?? 10;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max($currentPage, 1);
        $offset = ($currentPage - 1) * $perPage;

        if ($search) {
            $projects = $this->projectModel->searchPaginated($search, $perPage, $offset);
            $total = $this->projectModel->countSearch($search);
            Logger::log($this->table, "تم البحث عن المشاريع بالكلمة: '$search'", "INFO");
        } else {
            $projects = $this->projectModel->allPaginated($perPage, $offset);
            $total = $this->projectModel->countAll();
            Logger::log($this->table, "تم جلب جميع المشاريع، صفحة $currentPage", "INFO");
        }

        $totalPages = ceil($total / $perPage);

        echo json_encode([
            'status' => 'success',
            'data' => $projects,
            'pagination' => [
                'current_page' => $currentPage,
                'per_page' => (int)$perPage,
                'total_pages' => $totalPages,
                'total' => $total
            ]
        ]);
        exit;
    }

    // جلب مشروع واحد
    public function show($id) {
        AuthController::check();
        $project = $this->projectModel->find($id);

        if (!$project) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => '❌ المشروع غير موجود']);
            exit;
        }

        Logger::log($this->table, "تم جلب بيانات المشروع بالمعرف: $id", "INFO");
        echo json_encode(['status' => 'success', 'data' => $project]);
        exit;
    }

    // إنشاء مشروع جديد
    public function store() {
        AuthController::check();
        $input = $_POST;
        $imagePath = null;

        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . "/../../public/uploads/projects/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $imageName = time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
            $imagePath = "uploads/projects/" . $imageName;
        }

        $data = [
            'image' => $imagePath,
            'name' => $input['name'] ?? '',
            'manager' => $input['manager'] ?? '',
            'field' => $input['field'] ?? '',
            'start_date' => $input['start_date'] ?? null,
            'end_date' => $input['end_date'] ?? null,
            'status' => $input['status'] ?? 'نشط'
        ];

        $id = $this->projectModel->create($data);

        if (!$id) {
            Logger::log($this->table, "فشل إنشاء المشروع: {$data['name']}", "ERROR");
            echo json_encode([
                'status' => 'error',
                'action' => 'add',
                'message' => '❌ فشل إنشاء المشروع'
            ]);
        } else {
            Logger::log($this->table, "تم إنشاء مشروع جديد: {$data['name']}", "INFO");
            echo json_encode([
                'status' => 'success',
                'action' => 'add',
                'message' => '✅ تم إضافة المشروع بنجاح',
                'data' => array_merge(['id'=>$id], $data)
            ]);
        }
        exit;
    }

    // تحديث مشروع
    public function update($id) {
        AuthController::check();
        $input = $_POST;

        if (isset($input['_method']) && $input['_method'] === 'PUT') {
            unset($input['_method']);
        }

        $imagePath = $input['old_image'] ?? null;
        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . "/../../public/uploads/projects/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $imageName = time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
            $imagePath = "uploads/projects/" . $imageName;
        }

        $data = [
            'image' => $imagePath,
            'name' => $input['name'] ?? '',
            'manager' => $input['manager'] ?? '',
            'field' => $input['field'] ?? '',
            'start_date' => $input['start_date'] ?? null,
            'end_date' => $input['end_date'] ?? null,
            'status' => $input['status'] ?? 'نشط'
        ];

        $updated = $this->projectModel->update($id, $data);

        if (!$updated) {
            Logger::log($this->table, "فشل تحديث المشروع بالمعرف: $id", "ERROR");
            echo json_encode([
                'status' => 'error',
                'action' => 'edit',
                'message' => '❌ فشل تعديل المشروع'
            ]);
        } else {
            Logger::log($this->table, "تم تحديث المشروع بالمعرف: $id", "INFO");
            echo json_encode([
                'status' => 'success',
                'action' => 'edit',
                'message' => '✅ تم تعديل المشروع بنجاح',
                'data' => array_merge(['id'=>$id], $data)
            ]);
        }
        exit;
    }

    // حذف مشروع مؤقت
    public function destroy($id) {
        $this->projectModel->softDelete($id);
        Logger::log($this->table, "تم حذف المشروع مؤقتًا بالمعرف: $id", "WARNING");

        echo json_encode([
            'status' => 'success',
            'action' => 'delete',
            'message' => '✅ تم حذف المشروع بنجاح'
        ]);
        exit;
    }

    // استعادة مشروع محذوف
    public function restore($id) {
        AuthController::check();
        $this->projectModel->restore($id);
        Logger::log($this->table, "تم استعادة المشروع المحذوف بالمعرف: $id", "INFO");

        echo json_encode([
            'status' => 'success',
            'action' => 'restore',
            'message' => '✅ تم استعادة المشروع بنجاح'
        ]);
        exit;
    }

    // حذف مشروع نهائي
    public function destroyPermanent($id) {
        AuthController::check();
        $this->projectModel->destroy($id);
        Logger::log($this->table, "تم حذف المشروع نهائيًا بالمعرف: $id", "WARNING");

        echo json_encode([
            'status' => 'success',
            'action' => 'delete',
            'message' => '✅ تم حذف المشروع نهائيًا'
        ]);
        exit;
    }

    // عرض المشاريع المحذوفة
    public function deleted() {
        AuthController::check();
        $deletedProjects = $this->projectModel->allDeleted();
        Logger::log($this->table, "تم جلب جميع المشاريع المحذوفة", "INFO");

        echo json_encode([
            'status' => 'success',
            'data' => $deletedProjects
        ]);
        exit;
    }
}
