# SC-Diet-Nutri

## Projet 

Sandrine Coupart est une diététicienne-nutritionniste dont le cabinet est situé à Caen. En tant que
professionnelle de santé, elle prend en charge des patients dans le cadre de consultations diététiques.
Madame Coupart anime également des ateliers de prévention et d’information sur la nutrition.
Son fonctionnement habituel était de transmettre par email à ses patients des recettes santé. N’ayant pas
de site web, elle voulait profiter de l’occasion pour partager, à l’avenir, quelques-unes de ses recettes à
un plus grand nombre de visiteurs.
De plus, madame Coupart désirerait qu’il y ait un système d’authentification sur son site, afin de proposer
pour chaque patient des recettes supplémentaires adaptées à son régime.

## Fonctionnalités désirées

### US1. Se connecter

Utilisateurs concernés : Administrateur, Patients

### US2. Créer un patient

Utilisateurs concernés : Administrateur
  - Il est important de préciser la liste des allergènes pouvant provoquer une réaction au patient.
  - Plusieurs types de régime peuvent lui être associés.

### US3. Ajouter une recette

Utilisateurs concernés : Administrateur
  - Une case doit pouvoir être cochée afin que cette recette soit seulement accessible aux patients.

### US4. Voir les recettes

Utilisateurs concernés : Administrateur, Visiteurs, Patients
  - Les visiteurs voient les recettes de base.
  - Les patients accèdent aux recettes supplémentaires. De plus, un filtrage est fait automatiquement
pour ne montrer que celles qui sont adaptées à son régime et allergènes à éviter.
  - Les patients peuvent écrire un avis et donner une note (de 1 à 5). Pour plus de dynamisme, la
soumission de l’avis ne devra pas recharger la page.

### US5. Découvrir les services du cabinet

Plusieurs pages statiques devront être mises en place, et optimisées pour s’afficher correctement sur tout
support :
  - Une page d’accueil avec au moins une section "À propos" et “Mes services”,
  - Une page de contact,
  - Une page de mentions légales (Obligatoire depuis 2004),
  - Une page de politique de confidentialité (Obligatoire depuis le RGPD en 2018)

## Deploiement en local

Afin d'utiliser le site en local vous devez suivre les étapes suivantes :
  1. Cloner le repository présent sur GitHub : https://github.com/Atsuyko/SC-Diet-Nutri.git,
  2. Ouvrir le dossier dans un IDE, ouvrir le terminal de commande, se placer dans le dossier du projet et taper "composer install",
  3. Modifier les paramètres de votre base de donnée le dossier ".env" (DATABASE_URL),
  4. Dans le terminal tapez :
    - php bin/console doctrine:database:create,
    - php bin/console make:migration,
    - php bin/console doctrine:migration:migrate,
  5. Toujours le terminal tapez "symfony server:start".

Votre application est déployé en local, à ouvrir en cliquant sur le lien présent dans le terminal (lien "localhost" ou "127.0.0.1").

## Compte Admin

Vous avez deux options pour avoir accès aux administrateurs.

### Option 1 : Admin existant

En ayant effectuée l'étape 4 du déploiement en local, il vous suffit de vous connecter avec un compte administrateur présent en base de données.

### Option 2 : Créer un admin

Après avoir effectué l'étape 4 du déploiement en local :
  1. Se connecter avec un compte admin,
  2. Aller sur la page "Utilisateur" depuis la barre de navigation,
  3. Cliquer sur "Créer un patient",
  4. Remplir et soumettre le formulaire,
  5. Se rendre dans son SGBD sur la table "User" et modifier le rôle de l'utilisateur créé (['ROLE-USER'] => ['ROLE_ADMIN']).

## Comptes présent en BDD depuis la migration 

Administrateurs :
  - sc.diet-nutri@example.com 
  - backup@admin.fr

Patients :
  - julien.redondant@patient.fr
  - jerome.delatour@patient.fr
  - maryse.roussel@jourrapide.com
  - xavier.tetrault@dayrep.com
  - anais.riviere@dayrep.com

Le mot de passe pour l'ensemble des comptes est "password".