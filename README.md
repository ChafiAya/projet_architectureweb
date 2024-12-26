# Projet Symfony : Système de Réservation de Salles Universitaires

## Table des Matières

1. [Aperçu du Projet](#aperçu-du-projet)
2. [Fonctionnalités](#fonctionnalités)
3. [Installation](#installation)
4. [Technologies Utilisées](#technologies-utilisées)
5. [Schéma de la Base de Données](#schéma-de-la-base-de-données)
6. [Composants Clés](#composants-clés)
    - [Entités](#entités)
    - [Contrôleurs](#contrôleurs)
    - [Répertoires](#répertoires)
    - [Messenger](#messenger)
7. [Flux de Réservation](#flux-de-réservation)
8. [Défis et Solutions](#défis-et-solutions)
9. [Améliorations Futures](#améliorations-futures)
10. [Remerciements](#remerciements)

---

## Aperçu du Projet

Ce projet est une application web conçue pour simplifier la réservation des salles dans une université. Développé avec le framework Symfony, le système permet aux enseignants de réserver des salles pour diverses activités tout en gérant efficacement les disponibilités et les contraintes de capacité.

## Fonctionnalités

- **Réservation de Salles** : Les utilisateurs peuvent réserver des salles en fonction de leur disponibilité.
- **Détection de Conflits** : Empêche les réservations multiples et les surcharges de capacité.
- **Mises à Jour Dynamiques** : Utilise Symfony Messenger pour actualiser les statuts des salles.
- **Authentification** : Connexion sécurisée pour les utilisateurs autorisés.
- **Tableau de Bord Admin** : Gestion des salles, des réservations et des utilisateurs.

## Installation

1. Clonez le dépôt :
   ```bash
   git clone <repository_url>
   ```
2. Naviguez dans le répertoire du projet :
   ```bash
   cd repo_symfony
   ```
3. Installez les dépendances :
   ```bash
   composer install
   ```
4. Configurez la base de données :
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```
5. Lancez le serveur Symfony :
   ```bash
   symfony server:start
   ```

## Technologies Utilisées

- **Backend** : Framework Symfony
- **Base de données** : MySQL
- **Messagerie** : Symfony Messenger
- **Frontend** : Twig, HTML5, CSS, Bootstrap
- **Outils** : Doctrine ORM, Symfony Debug Toolbar

## Schéma de la Base de Données

Le schéma inclut les entités suivantes :

- `Enseignant`
- `Promotion`
- `Reservation`
- `Salle`
- `Matiere`
- `User`

Chaque entité a des relations pour garantir l'intégrité des données et optimiser les requêtes.

## Composants Clés

### Entités
- **`Sale`** : Représente une salle avec des attributs comme la capacité, la disponibilité et l'emplacement.
- **`Reservation`** : Contient les détails des réservations, y compris l'utilisateur, la salle, la date et l'heure.

### Contrôleurs
- **`ReserveController`** : Gère la création des réservations, vérifie les conflits et gère la disponibilité des salles.

### Répertoires
- **`ReserveRepository`** : Inclut des méthodes pour :
  - Trouver des conflits de réservations pour un enseignant.
  - Vérifier les conflits de capacité des salles.
  - Identifier les réservations qui se chevauchent.

### Messenger
- **`UpdateDisponibiliteMessage`** : Encapsule la logique de mise à jour de la disponibilité des salles.
- **`UpdateDisponibiliteMessageHandler`** : Traite les messages pour mettre à jour la base de données et enregistrer les modifications.

## Flux de Réservation

1. **Connexion de l'Utilisateur** : Les utilisateurs se connectent pour accéder au système.
2. **Sélection de la Salle** : Choix d'une salle en fonction de la date, de l'heure et des besoins en capacité.
3. **Détection de Conflits** : Le système vérifie les réservations qui se chevauchent.
4. **Confirmation de la Réservation** : Si aucun conflit n'est détecté, la réservation est confirmée.
5. **Mise à Jour des Disponibilités** : Le statut de la salle est mis à jour dynamiquement via Messenger.

## Défis et Solutions

### Défi : Assurer la Disponibilité des Salles
- **Solution** : Mise en place d'un système robuste de détection des conflits dans la couche répertoire et utilisation de Symfony Messenger pour des mises à jour dynamiques.

### Défi : Gérer les Requêtes Concurrentes
- **Solution** : Utilisation des transactions de base de données pour garantir l'intégrité des données pendant les réservations simultanées.

## Améliorations Futures

- Ajouter des rôles et permissions pour un meilleur contrôle d'accès.
- Intégrer une vue calendrier pour faciliter la réservation des salles.
- Implémenter des notifications pour les réservations à venir.
- Optimiser les performances avec des stratégies de mise en cache.

## Remerciements

Ce projet a été développé dans le cadre du cours de développement web. Un grand merci au professeur et aux camarades pour leur soutien et leurs conseils.

