<?php

namespace App\Models;

use PDO;

class Project {
    private $db;
    private $table = 'projects';

    public function __construct($db) {
        $this->db = $db;
    }

    // ------------------- جلب جميع المشاريع مع Pagination -------------------
    public function allPaginated($limit, $offset) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ------------------- عدّ جميع المشاريع -------------------
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL");
        return $stmt->fetchColumn();
    }

    // ------------------- البحث مع Pagination -------------------
    public function searchPaginated($keyword, $limit, $offset) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE deleted_at IS NULL 
            AND (name LIKE :keyword OR manager LIKE :keyword OR field LIKE :keyword)
            ORDER BY created_at DESC 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ------------------- عدّ نتائج البحث -------------------
    public function countSearch($keyword) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM {$this->table} 
            WHERE deleted_at IS NULL 
            AND (name LIKE :keyword OR manager LIKE :keyword OR field LIKE :keyword)
        ");
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // ------------------- جلب مشروع واحد -------------------
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id AND deleted_at IS NULL LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ------------------- إنشاء مشروع جديد -------------------
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (image, name, manager, field, start_date, end_date, status) 
            VALUES (:image, :name, :manager, :field, :start_date, :end_date, :status)
        ");
        $stmt->execute([
            ':image' => $data['image'],
            ':name' => $data['name'],
            ':manager' => $data['manager'],
            ':field' => $data['field'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':status' => $data['status']
        ]);
        return $this->find($this->db->lastInsertId());
    }

    // ------------------- تحديث مشروع -------------------
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET image = :image, name = :name, manager = :manager, field = :field, 
                start_date = :start_date, end_date = :end_date, status = :status, updated_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':image' => $data['image'],
            ':name' => $data['name'],
            ':manager' => $data['manager'],
            ':field' => $data['field'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':status' => $data['status']
        ]);
    }

    // ------------------- Soft Delete (حذف مؤقت) -------------------
    public function softDelete($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ------------------- استعادة مشروع محذوف -------------------
    public function restore($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NULL WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ------------------- حذف نهائي -------------------
    public function destroy($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ------------------- جلب المشاريع المحذوفة -------------------
    public function allDeleted() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        public function countProjects() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM projects");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    // المشاريع حسب الحالة
    public function getProjectsByStatus() {
        $stmt = $this->db->prepare("SELECT status, COUNT(*) as total FROM projects GROUP BY status");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // النشاط الشهري للمستخدمين (افتراضياً حسب تاريخ الإنشاء)
    public function getMonthlyActivity() {
        $stmt = $this->db->prepare("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total
            FROM users
            GROUP BY month
            ORDER BY month ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
}
