# Manuel Développeur - Quiz de Primaire

## Introduction

Ce manuel fournit des informations techniques pour les développeurs souhaitant comprendre, modifier ou étendre l'application web de Quiz de Primaire.

## Architecture et composants

L'application suit une architecture MVC simplifiée.

*   **Modèles (`/models/`) :**  Contiennent la logique d'accès aux données et de gestion de la base de données.  `Stats.php` est le modèle principal pour la gestion des statistiques.
*   **Contrôleurs (`/controllers/`) :**  Gèrent la logique applicative et l'interaction entre les modèles et les vues. `AuthController.php` gère l'authentification et l'autorisation.
*   **Vues (`/views/`) :**  Contiennent le code HTML pour l'interface utilisateur et l'affichage des données. Les fichiers sont organisés en layouts (`/views/layout/`) pour les parties communes (header, footer) et vues spécifiques (statistiques, pages d'accueil, questions).
*   **Configuration (`/config/`) :**  Contient les fichiers de configuration, comme `database.php` pour les paramètres de connexion à la base de données.
*   **Utilitaires (`utils.php`) :**  Regroupe des fonctions utilitaires, comme `log_adresse_ip` pour la journalisation.
*   **Logs (`/logs/`) :**  Stocke les fichiers de logs, par exemple, `log.txt` pour les adresses IP.

## Base de données

Le schéma de la base de données n'est pas explicitement fourni ici, mais les tables principales utilisées sont :

*   **`users` :**  Stocke les informations des utilisateurs (enfants, parents, enseignants), incluant `user_id`, `username`, `password`, `role`, etc.
*   **`question_results` :** Enregistre les résultats de chaque question posée lors des quiz, avec les champs suivants (entre autres) :
    *   `result_id` (Clé primaire)
    *   `user_id` (Clé étrangère vers la table `users`)
    *   `question_number` (Numéro de la question dans le quiz)
    *   `operation` (Type d'opération : 'addition', 'subtraction', 'multiplication')
    *   `user_answer` (Réponse fournie par l'utilisateur)
    *   `correct_answer` (Réponse correcte)
    *   `is_correct` (Booléen indiquant si la réponse est correcte)
    *   `completion_time` (Date et heure de complétion de la question)

La table `question_results` est centrale pour la fonctionnalité de statistiques.

## Statistiques - Fonctionnement détaillé

Le modèle `Stats.php` contient les fonctions pour récupérer et calculer les statistiques. Les fonctions clés et leurs adaptations pour gérer les différents types d'opérations sont :

*   **`getStatsByUserId($user_id, $operation_type = null)` :**
    *   Récupère toutes les statistiques `question_results` pour un `user_id` donné.
    *   `$operation_type` (optionnel) :  Filtre les résultats pour un type d'opération spécifique ('addition', 'subtraction', 'multiplication'). Si non spécifié, retourne toutes les opérations.
    *   Retourne un tableau associatif des résultats de `question_results`, triés par `completion_time` décroissant.

*   **`getGlobalStatsByUserId($user_id, $operation_type = null)` :**
    *   Calcule les statistiques globales pour un `user_id`.
    *   `$operation_type` (optionnel) : Filtre les calculs pour un type d'opération spécifique.
    *   Statistiques calculées :
        *   `total_exercises` : Nombre total de questions résolues (filtré par `operation_type` si spécifié).
        *   `average_score` : Score moyen (sur 10) basé sur le champ `is_correct` (filtré par `operation_type` si spécifié).
    *   Retourne un tableau associatif contenant `total_exercises` et `average_score`.

*   **`getProgressOverTime($user_id, $operation_type = null)` :**
    *   Calcule l'évolution des scores dans le temps pour un `user_id`.
    *   `$operation_type` (optionnel) : Filtre les résultats par type d'opération.
    *   Regroupe les résultats par jour (`DATE(qr.completion_time)`) et calcule le score moyen (`AVG(qr.is_correct) * 10`) pour chaque jour.
    *   Retourne un tableau associatif avec les colonnes `day` (date) et `average_score`.

*   **`getAllStatsFormattedByUserId($user_id, $operation_type = null)` :**
    *   Fonction de regroupement qui appelle les autres fonctions du modèle pour récupérer toutes les statistiques pertinentes et les formater dans un tableau associatif.
    *   Utilise les paramètres `$user_id` et `$operation_type` pour passer le filtre aux fonctions sous-jacentes.
    *   Retourne un tableau associatif contenant les statistiques globales, les statistiques par exercice (actuellement vide), la progression dans le temps et les statistiques récentes.

## Adaptation des pages d'accueil

Des pages d'accueil séparées ont été créées pour chaque type d'opération (`index_.php`). Les principales adaptations sont :

*   **Titres et en-têtes :**  Adaptés pour refléter le type d'opération du quiz (ex: "Quiz d'Additions !").
*   **Actions de formulaire :**  Les formulaires de sélection du nombre de questions dans chaque page d'accueil pointent vers la page de questions correspondante (`question.php` pour toutes les versions).
*   **Liens "Commencer le Quiz" :**  Les liens pour démarrer le quiz pointent également vers la page de questions appropriée.
*   **Log de l'adresse IP :**  La fonction `log_adresse_ip` dans chaque page d'accueil est adaptée pour enregistrer le nom de la page (`index_addition.php`, etc.) dans le fichier de logs.

##  Points d'amélioration possibles

*   **Gestion des questions :**  Actuellement, la logique de génération des questions et des réponses correctes n'est pas détaillée.  Il faudrait implémenter un système robuste de gestion des questions, potentiellement avec une base de données de questions et un mécanisme de sélection aléatoire.
*   **Types d'opérations supplémentaires :** Ajouter la division comme type d'opération supplémentaire.
*   **Niveaux de difficulté :** Introduire des niveaux de difficulté pour adapter les quiz aux compétences de chaque enfant.
*   **Interface utilisateur/expérience utilisateur (UI/UX) :** Améliorer le design et l'ergonomie de l'application pour une meilleure expérience utilisateur, notamment sur mobile.
*   **Tests unitaires :** Mettre en place des tests unitaires pour assurer la qualité et la stabilité du code.

## Contact développeur

Rémi Synave - remi.synave@univ-littoral.fr