<?php
namespace App\Models;

use PDO;

class User {
    private $db;
    private $table = "users";

    public function __construct($db) {
        $this->db = $db;
    }

    public function all() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE deleted_at IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleted() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE deleted_at IS NOT NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
    // التحقق من وجود البريد الإلكتروني مسبقاً
    $stmtCheck = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
    $stmtCheck->execute(['email' => $data['email']]);
    if ($stmtCheck->fetch()) {
        return false; // البريد الإلكتروني موجود مسبقاً
    }

    $stmt = $this->db->prepare("
        INSERT INTO {$this->table} 
        (name, username, password, email, description, status, role, created_at, updated_at)
        VALUES (:name, :username, :password, :email, :description, :status, :role, :created_at, :updated_at)
    ");

    $now = date('Y-m-d H:i:s');
    $data['created_at'] = $now;
    $data['updated_at'] = $now;
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    return $stmt->execute($data);
}


    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                    name = :name, 
                    username = :username, 
                    email = :email, 
                    description = :description, 
                    status = :status, 
                    role = :role, 
                    updated_at = :updated_at";

        if (!empty($data['password'])) {
            $sql .= ", password = :password"; // كلمة المرور نص عادي
        }

        $sql .= " WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);

        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function restore($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NULL WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function destroy($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function allDeleted() {
    $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE deleted_at IS NOT NULL");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function search($term) {
    $term = htmlspecialchars($term);
    $stmt = $this->db->prepare("SELECT * FROM users WHERE name LIKE ? OR username LIKE ?");
    $like = "%$term%";
    $stmt->execute([$like, $like]);
    return $stmt->fetchAll();
}
public function findByEmailOrUsername(string $login): ?array {
    $sql = "SELECT * FROM {$this->table} 
            WHERE (email = :login OR username = :login) 
            AND deleted_at IS NULL 
            LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':login', $login, \PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result ?: null;
}

 // جلب كل المستخدمين مع Pagination
public function allPaginated($limit, $offset) {
    $sql = "SELECT * FROM {$this->table} 
            WHERE deleted_at IS NULL 
            LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// حساب عدد المستخدمين الكلي (للـ pagination)
public function countAll() {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL");
    $stmt->execute();
    return $stmt->fetchColumn();
}

// البحث مع Pagination
public function searchPaginated($term, $limit, $offset) {
    $like = "%$term%";
    $sql = "SELECT * FROM {$this->table} 
            WHERE (name LIKE :term OR username LIKE :term) 
            AND deleted_at IS NULL
            LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':term', $like, PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// حساب عدد نتائج البحث
public function countSearch($term) {
    $like = "%$term%";
    $sql = "SELECT COUNT(*) FROM {$this->table} 
            WHERE (name LIKE :term OR username LIKE :term) 
            AND deleted_at IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':term', $like, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
}
  public function countUsers() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

}
