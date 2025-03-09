<?php
require_once __DIR__ . '/../config/database.php';

class QuestionResult {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getResultsByChildId($childId) {
        $stmt = $this->db->prepare("SELECT * FROM question_results WHERE user_id = :user_id ORDER BY completion_time DESC");
        $stmt->execute([':user_id' => $childId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentResultsByChildId($childId, $limit = 10) {
        $stmt = $this->db->prepare("SELECT * FROM question_results WHERE user_id = :user_id ORDER BY completion_time DESC LIMIT :limit");
        $stmt->bindParam(':user_id', $childId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatsByChildId($childId) {
        // Statistiques globales
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_questions,
                SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct_answers,
                ROUND((SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as success_rate
            FROM question_results 
            WHERE user_id = :user_id
        ");
        $stmt->execute([':user_id' => $childId]);
        $globalStats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Statistiques par type d'opération
        $stmt = $this->db->prepare("
            SELECT 
                operation_type,
                COUNT(*) as total_questions,
                SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct_answers,
                ROUND((SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as success_rate
            FROM question_results 
            WHERE user_id = :user_id
            GROUP BY operation_type
        ");
        $stmt->execute([':user_id' => $childId]);
        $operationStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Statistiques par niveau de difficulté
        $stmt = $this->db->prepare("
            SELECT 
                difficulty,
                COUNT(*) as total_questions,
                SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct_answers,
                ROUND((SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as success_rate
            FROM question_results 
            WHERE user_id = :user_id
            GROUP BY difficulty
        ");
        $stmt->execute([':user_id' => $childId]);
        $difficultyStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Progression dans le temps (par semaine)
        $stmt = $this->db->prepare("
            SELECT 
                DATE_FORMAT(completion_time, '%Y-%u') as week,
                COUNT(*) as total_questions,
                SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct_answers,
                ROUND((SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as success_rate
            FROM question_results 
            WHERE user_id = :user_id
            GROUP BY week
            ORDER BY week DESC
            LIMIT 10
        ");
        $stmt->execute([':user_id' => $childId]);
        $weeklyStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'global' => $globalStats,
            'operations' => $operationStats,
            'difficulties' => $difficultyStats,
            'weekly' => $weeklyStats
        ];
    }
}