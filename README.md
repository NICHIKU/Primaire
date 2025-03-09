# Projet Quiz de Primaire

## Description du projet

Ce projet est une application web interactive de quiz de calcul mental, conçue pour aider les enfants à améliorer leurs compétences en arithmétique de manière ludique. L'application propose différents types d'opérations (addition, soustraction, multiplication) et permet aux parents et aux enseignants de suivre les progrès des enfants grâce à des statistiques détaillées.

# Host en local

Pour lancer l'application en local, vous pouvez utiliser un serveur web local comme XAMPP, WAMP ou MAMP. Voici les étapes générales pour lancer l'application :

1.  **Installer un serveur local :**  Téléchargez et installez un serveur local comme XAMPP (https://www.apachefriends.org/index.html).
2.  **Configurer la base de données :**  Créez une base de données MySQL et importez le fichier `bdd.sql` pour créer les tables nécessaires.
3.  **Configurer la connexion à la base de données :**  Modifiez le fichier `config/database.php` pour configurer les logs de la base de données.
4.  **Placer les fichiers dans le répertoire racine du serveur :**  Placez les fichiers de l'application dans le répertoire racine du serveur local (ex: `htdocs` pour XAMPP).
5.  **Accéder à l'application :**  Ouvrez un navigateur web et accédez à l'URL locale correspondant au serveur (ex: `http://localhost:8888/index.php`).


## Fonctionnalités principales

*   **Quiz interactifs :**  Propose des quiz de calcul mental pour l'addition, la soustraction et la multiplication.
*   **Choix du nombre de questions :** Les utilisateurs peuvent choisir le nombre de questions par quiz.
*   **Suivi des performances :** Enregistre les résultats des quiz, incluant les réponses de l'enfant, les réponses correctes, et le temps de complétion.
*   **Statistiques détaillées :**  Fournit des statistiques individuelles pour chaque enfant, accessibles aux parents et aux enseignants, incluant :
    *   Score moyen global et par type d'opération.
    *   Nombre total de questions complétées.
    *   Historique des réponses aux questions.
    *   Date de la dernière activité.
*   **Authentification et rôles :** Système d'authentification sécurisé avec différents rôles utilisateurs (enfant, parent, enseignant).
*   **Journalisation :** Enregistrement des adresses IP pour le suivi des accès (fonction utilitaire).

## Technologies utilisées

*   **Langage de script côté serveur :** PHP
*   **Base de données :** MySQL (ou similaire, selon la configuration de `database.php`)
*   **Interface utilisateur :** HTML, CSS, Bootstrap (pour le style et la réactivité)
*   **Gestion des sessions :** Sessions PHP pour gérer l'état de l'utilisateur et du quiz.

## Structure du projet (simplifiée)

/config/database.php          # Configuration de la base de données

/controllers/AuthController.php    # Contrôleur pour la gestion de l'authentification et des rôles

/models/Stats.php   # Modèle pour la gestion des statistiques

/views/layout/header.php        # En-tête commun des pages HTML
footer.php        # Pied de page commun des pages HTML

child_stats.php # Page de détails des statistiques d'un enfant

stats.php             # Page listant les statistiques des enfants 
(pour parents/enseignants)

question.php          # Page pour les questions ( addition peut servir de modèle)

/logs/log.txt               # Fichier de log pour les adresses IP

utils.php                 # Fonctions utilitaires (ex: log_adresse_ip)
