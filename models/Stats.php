<?php

require_once __DIR__ . '/../config/database.php';

class Stats {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupérer toutes les statistiques de question_results d'un utilisateur, triées par date,
     * et filtrées par type d'opération si spécifié.
     *
     * @param int $user_id
     * @param string|null $operation_type (addition, subtraction, multiplication, or null for all)
     * @return array
     */
    public function getStatsByUserId($user_id, $operation_type = null) {
        try {
            $sql = "SELECT qr.* FROM question_results qr WHERE qr.user_id = :user_id";
            $params = [':user_id' => $user_id];

            if ($operation_type) {
                $sql .= " AND qr.operation = :operation_type";
                $params[':operation_type'] = $operation_type;
            }

            $sql .= " ORDER BY qr.completion_time DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Récupérer les statistiques récentes de question_results d'un utilisateur, filtrées par type d'opération si spécifié.
     *
     * @param int $user_id
     * @param int $limit
     * @param string|null $operation_type
     * @return array
     */
    public function getRecentStatsByUserId($user_id, $limit = 10, $operation_type = null) {
        try {
            $sql = "
                SELECT qr.*
                FROM question_results qr
                WHERE qr.user_id = :user_id";
            $params = [':user_id' => $user_id];

            if ($operation_type) {
                $sql .= " AND qr.operation = :operation_type";
                $params[':operation_type'] = $operation_type;
            }

            $sql .= " ORDER BY qr.completion_time DESC LIMIT :limit";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Récupérer les statistiques globales d'un utilisateur, filtrées par type d'opération si spécifié.
     *
     * @param int $user_id
     * @param string|null $operation_type
     * @return array
     */
    public function getGlobalStatsByUserId($user_id, $operation_type = null) {
        try {
            $sql = "
                SELECT
                    COUNT(*) as total_exercises,
                    AVG(qr.is_correct) * 10 as average_score
                FROM question_results qr
                WHERE qr.user_id = :user_id";
            $params = [':user_id' => $user_id];

            if ($operation_type) {
                $sql .= " AND qr.operation = :operation_type";
                $params[':operation_type'] = $operation_type;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }


    public function getStatsByExerciseId($user_id) {
        return []; // Fonction non utilisée, garde la version vide
    }


    /**
     * Obtenir les statistiques d'évolution dans le temps, filtrées par type d'opération si spécifié.
     *
     * @param int $user_id
     * @param string|null $operation_type
     * @return array
     */
    public function getProgressOverTime($user_id, $operation_type = null) {
        try {
            $sql = "
                SELECT DATE(qr.completion_time) as day, AVG(qr.is_correct) * 10 as average_score
                FROM question_results qr
                WHERE qr.user_id = :user_id";
            $params = [':user_id' => $user_id];

            if ($operation_type) {
                $sql .= " AND qr.operation = :operation_type";
                $params[':operation_type'] = $operation_type;
            }

            $sql .= " GROUP BY DATE(qr.completion_time) ORDER BY day";


            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Récupérer toutes les statistiques pertinentes pour un utilisateur, formatées pour la vue.
     * Utilise les fonctions modifiées pour permettre le filtrage par type d'opération.
     *
     * @param int $user_id
     * @param string|null $operation_type
     * @return array
     */
    public function getAllStatsFormattedByUserId($user_id, $operation_type = null) {
        return [
            'global' => $this->getGlobalStatsByUserId($user_id, $operation_type),
            'exercises' => $this->getStatsByExerciseId($user_id), // Toujours inclus, mais pourrait être vide
            'progress' => $this->getProgressOverTime($user_id, $operation_type),
            'recent' => $this->getRecentStatsByUserId($user_id, 10, $operation_type)
        ];
    }
}