# les-devoirs-de-primaire
Site permettant aux enfants en primaire de faire des exercices de maths/français et peut être plus par la suite.

Pour un démonstration du site, vous pouvez vous rendre [ici](https://mes-devoirs.jrcan.dev/).

# Installation :
1 - Téléchargez le code

2 - Transférez-le sur un hébergement avec php (pas de base de données utilisée)

3 - Après le transfert, dans les répertoires addition, conjugaison_phrase, conjugaison_verbe, dictee, multiplication et soustraction, changez les droits en 777 pour les sous-répertoires logs, resultats et supprime

# Utilisation :
Rendez-vous sur la page d'accueil puis sélectionnez l'exercice à réaliser. La configuration des exercices (changement du temps pour les conjugaisons, des bornes des nombres pour les exercices de math, etc.).

Pour voir les résultats d'un enfant, rendez-vous sur la page d'accueil, entrez dans l'exercice pour lequel vous voulez les résultats puis, dans la barre d'adresse, modifiez le index.php par affiche_resultat.php

# TODO
La refonte graphique n'est pas demandée. Si vous voulez modifier le design, vous pouvez mais conservez le style d'origine et ne partez pas sur le design du site des impots !

1 - Créer un système de connexion avec profil (10 points) : inclut l'inscription, la connexion et la sauvegarde des différents exercices réalisés avec visualisation de stats sur son profil.

1.5 - Ajout de rôle aux utilisateurs (10 points) : ajout des rôles enfant, enseignant et parent. Les parents peuvent voir les résultats de leurs enfants. Les enseignants peuvent voir les résultats de leurs élèves. Les enfants peuvent faire des exercices. Dans l'idéal, il faudrait que les enseignants puissent configurer (voir point 5) les exercices pour les enfants.

2 - Améliorer le système de logs (3 points) : Voir les répertoires logs de chaque exercice. Faire mieux :)

3 - Utiliser une base de données (3 points) : peut facilement être combiné avec le système de connexion (point 1 et 1.5).

4 - Améliorer le système d'affichage des résultats (2 points) : Peut être naturellement combiné avec le point 1 (stats sur profil).

5 - Ajouter la possibilité de configurer les exercices (3 points) : doit donner la possibilité à l'utilisateur de configurer l'exercice. Les paramètrages possibles sont dépendants de l'exercice selectionné. Exemple : pour la multiplication, on peut, par exemple, proposer des bornes min et max. Si l'enfant est en CP, l'utilisateur choisira entre 1 et 9 pour les deux nombres. En CE2, il choisira entre 1 et 1000 pour le nombre de gauche et entre 1 et 9 pour celui de droite. En CM2, il choisir entre 1 et 1000 pour les deux nombres.

6 - Création d'une application pour faire du text-to-speech (10 points) : Application de bureau linux, en ligne de commande ou en back office de ce site. Attention ! De nombreux outils simples d'utilisation ne fournissent pas de résultat satisfisant !

7 - Documentation complète du projet (3 points) : commentaire dans le code, manuel utilisateur, manuel du développeur, document pour l'aide à l'installation, etc.

# Comment rendre son travail

Voir Cours Moodle SAES6 maintenance
