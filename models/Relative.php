<?php

require_once __DIR__ . '/../config/Database.php';

class Relative {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Créer une relation parent-enfant
    public function createRelationship($parent_id, $child_id) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO relatives (parent_id, child_id) VALUES (?, ?)");
            $stmt->execute([$parent_id, $child_id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupérer tous les enfants d'un parent
    public function getChildrenByParentId($parent_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT u.* 
                FROM users u
                JOIN relatives r ON u.user_id = r.child_id
                WHERE r.parent_id = ?
            ");
            $stmt->execute([$parent_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Récupérer tous les parents d'un enfant
    public function getParentsByChildId($child_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT u.* 
                FROM users u
                JOIN relatives r ON u.user_id = r.parent_id
                WHERE r.child_id = ?
            ");
            $stmt->execute([$child_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Supprimer une relation parent-enfant
    public function deleteRelationship($parent_id, $child_id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM relatives WHERE parent_id = ? AND child_id = ?");
            $stmt->execute([$parent_id, $child_id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}