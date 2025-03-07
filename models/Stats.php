<?php

require_once __DIR__ . '/../config/Database.php';

class Stats {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Récupérer les statistiques d'un utilisateur
    public function getStatsByUserId($user_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT s.*, e.title, e.description 
                FROM stats s
                JOIN exercises e ON s.exercise_id = e.exercise_id
                WHERE s.user_id = ?
                ORDER BY s.date_taken DESC
            ");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Ajouter une nouvelle statistique
    public function addStat($user_id, $exercise_id, $score) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO stats (user_id, exercise_id, score) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$user_id, $exercise_id, $score]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Obtenir les statistiques moyennes par exercice pour un utilisateur
    public function getAverageStatsByExercise($user_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT e.title, AVG(s.score) as average_score, COUNT(s.stat_id) as attempts
                FROM stats s
                JOIN exercises e ON s.exercise_id = e.exercise_id
                WHERE s.user_id = ?
                GROUP BY s.exercise_id
                ORDER BY average_score DESC
            ");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtenir les statistiques d'évolution dans le temps
    public function getProgressOverTime($user_id, $exercise_id = null) {
        try {
            $sql = "
                SELECT DATE(s.date_taken) as day, AVG(s.score) as average_score
                FROM stats s
                WHERE s.user_id = ?
            ";
            
            if ($exercise_id) {
                $sql .= " AND s.exercise_id = ?";
                $params = [$user_id, $exercise_id];
            } else {
                $params = [$user_id];
            }
            
            $sql .= " GROUP BY DATE(s.date_taken) ORDER BY day";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}