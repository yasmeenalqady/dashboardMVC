<?php
namespace App\Models;

use PDO;

class Field {
    private PDO $db;
    private string $table = "fields";

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /** جلب جميع المجالات (غير المحذوفة) */
    public function all(): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** جلب مجال محدد */
    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /** إضافة مجال جديد */
    public function create(array $data): bool {
        $sql = "INSERT INTO {$this->table} 
                (name, description, status, created_at, updated_at) 
                VALUES (:name, :description, :status, :created_at, :updated_at)";

        $stmt = $this->db->prepare($sql);

        $now = date('Y-m-d H:i:s');
        $params = [
            ':name'        => $data['name'],
            ':description' => $data['description'],
            ':status'      => $data['status'] ?? 0,
            ':created_at'  => $now,
            ':updated_at'  => $now
        ];

        return $stmt->execute($params);
    }

    /** تحديث مجال */
    public function update(int $id, array $data): bool {
        $sql = "UPDATE {$this->table} SET 
                    name = :name, 
                    description = :description, 
                    status = :status, 
                    updated_at = :updated_at
                WHERE id = :id AND deleted_at IS NULL";

        $stmt = $this->db->prepare($sql);

        $params = [
            ':id'          => $id,
            ':name'        => $data['name'],
            ':description' => $data['description'],
            ':status'      => $data['status'] ?? 0,
            ':updated_at'  => date('Y-m-d H:i:s')
        ];

        return $stmt->execute($params);
    }

    /** الحذف المؤقت (Soft Delete) */
    public function softDelete(int $id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** استرجاع مجال محذوف */
    public function restore(int $id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NULL WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** الحذف النهائي */
    public function destroy(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** جلب المجالات المحذوفة */
    public function allDeleted(): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE deleted_at IS NOT NULL ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** البحث */
    public function search(string $term): array {
        $like = "%$term%";
        $sql = "SELECT * FROM {$this->table} 
                WHERE (name LIKE :term OR description LIKE :term) 
                AND deleted_at IS NULL
                ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':term', $like, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** جلب مع Pagination */
    public function allPaginated(int $limit, int $offset): array {
        $sql = "SELECT * FROM {$this->table} 
                WHERE deleted_at IS NULL 
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** عدد المجالات الكلي */
    public function countAll(): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /** البحث مع Pagination */
    public function searchPaginated(string $term, int $limit, int $offset): array {
        $like = "%$term%";
        $sql = "SELECT * FROM {$this->table} 
                WHERE (name LIKE :term OR description LIKE :term) 
                AND deleted_at IS NULL
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':term', $like, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** عدد نتائج البحث */
    public function countSearch(string $term): int {
        $like = "%$term%";
        $sql = "SELECT COUNT(*) FROM {$this->table} 
                WHERE (name LIKE :term OR description LIKE :term) 
                AND deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':term', $like, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    public function allDeletedPaginated($limit, $offset) {
    $stmt = $this->db->prepare("SELECT * FROM fields WHERE deleted_at IS NOT NULL LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
