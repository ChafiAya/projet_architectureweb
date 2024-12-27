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
10. [Captures de l'application](#Capture-de-l'application)
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
   git clone https://github.com/ChafiAya/projet_architectureweb.git
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
   symfony console doctrine:database:create
   symfony console meke:migration
   symfony console doctrine:migrations:migrate
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

 ## Gestion de l'authentification et des rôles

### Authentification
L'application implémente un système sécurisé d'authentification basé sur des rôles. Voici les étapes pour la gestion des utilisateurs et des rôles :

1. **Connexion Administrateur** :
   - L'administrateur s'authentifie en utilisant ses identifiants.
   - Une fois connecté, il a la possibilité de gérer les utilisateurs.

2. **Création des utilisateurs** :
   - L'administrateur peut créer des utilisateurs avec les rôles suivants :
     - **Enseignant** (`ROLE_ENSEIGNANT`)
     - **Étudiant** (`ROLE_ETUDIANT`)
   - Lors de la création, l'administrateur doit définir :
     - Une **adresse e-mail** respectant le format suivant :
       - Pour un enseignant : `nom.prenom.ens@univ-lille.fr`
       - Pour un étudiant : `nom.prenom.etu@univ-lille.fr`
     - Un **mot de passe** de **6 caractères minimum**.

### Gestion des rôles et privilèges
L'application gère trois rôles avec des privilèges spécifiques :

1. **Administrateur** (`ROLE_ADMIN`) :
   - Privilèges : **Lecture et écriture**.
   - Accès complet à toutes les fonctionnalités, y compris la gestion des utilisateurs, la création et la modification des réservations.

2. **Enseignant** (`ROLE_ENSEIGNANT`) :
   - Privilèges : **Lecture et écriture** sur les réservations.
   - Peut créer, consulter et modifier ses propres réservations.

3. **Étudiant** (`ROLE_ETUDIANT`) :
   - Privilèges : **Lecture seule**.
   - Peut uniquement consulter les réservations liées à sa promotion.

### Sécurité
- Le système impose des règles strictes pour les adresses e-mail et les mots de passe pour garantir la sécurité des comptes utilisateurs.
- Chaque rôle dispose de permissions spécifiques pour assurer une séparation claire des responsabilités et des accès.


## Flux de Réservation

1. **Connexion de l'Utilisateur** : Les utilisateurs se connectent pour accéder au système.
2. **Sélection de la Salle** : Choix d'une salle en fonction de la date, de l'heure et des besoins en capacité.
3. **Détection de Conflits** : Le système vérifie les réservations qui se chevauchent.
4. **Confirmation de la Réservation** : Si aucun conflit n'est détecté, la réservation est confirmée.

## Défis et Solutions

### Défi : Assurer la Disponibilité des Salles
- **Solution** : Mise en place d'un système robuste de détection des conflits dans la couche répertoire et utilisation de Symfony Messenger pour des mises à jour dynamiques.

### Défi : Gérer les Requêtes Concurrentes
- **Solution** : Utilisation des transactions de base de données pour garantir l'intégrité des données pendant les réservations simultanées.

## Améliorations Futures
- Implémenter des notifications pour les réservations à venir.
- Optimiser les performances avec des stratégies de mise en cache.

## Captures de l'application
### Page Auth 
<img width="803" alt="page auth" src="https://github.com/user-attachments/assets/2d096a75-5986-4cc2-a660-58ebf69c2119" />

### Page Admin
<img width="938" alt="page_admin" src="https://github.com/user-attachments/assets/19361765-4935-4d98-a73e-41e283c685ab" />

### ajout de role par l'admin
<img width="889" alt="ajout_de_role_par_l'admin" src="https://github.com/user-attachments/assets/8a79209f-a050-4dc6-a33f-d4cc51b55331" />

### Creation de reservation par un enseignant
<img width="902" alt="creation_reservation_par_enseignant" src="https://github.com/user-attachments/assets/1df54253-5328-487d-b633-4e1b7dfb48e2" />


### Liste reservation enseignant 
<img width="821" alt="reserve_enseignant" src="https://github.com/user-attachments/assets/52abc82a-932e-47c3-aa0a-327578f38255" />

### Conflit de reservation 
<img width="930" alt="conflit_reservation" src="https://github.com/user-attachments/assets/bd44afe9-2ef9-431d-869a-8dad8986f674" />

### Liste de reservation pour la promo 1
<img width="893" alt="reservation_pour_etudiant" src="https://github.com/user-attachments/assets/ba399b0b-c696-4575-abb0-1e0f2138b7c9" />

### Liste de reservation pour la promo 2
<img width="917" alt="reservation_etudiant_autre_promo" src="https://github.com/user-attachments/assets/4d892f20-af6b-4808-a38f-96bcac5cb873" />

### Calendrier de reservation 
<img width="547" alt="calendrier de reservation" src="https://github.com/user-attachments/assets/9ecb622f-aaf5-42e7-a10f-9d0682146e7d" />




