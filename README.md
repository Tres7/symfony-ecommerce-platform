# Mini-site E-Commerce ayant comme thème: La décoration intérieure

# Description
Ce projet est une plateforme de commerce électronique sur le thème de la décoration intérieure. Il permet aux utilisateurs de parcourir et d'acheter des produits en fonction des catégories disponibles, comme les meubles, les décorations murales ou les accessoires. Une partie d'administration est mise en place pour permettre aux administrateurs de gérer au mieux le site. Le projet est réalisé avec Symfony, un framework PHP, et suit les meilleures pratiques du développement web.

# Structure du projet
## Fonctionnalités principales
### Utilisateurs :
- Inscription et connexion.
- Gestion du compte utilisateur.
- Ajout et gestion des articles dans le panier.
### Produits :
- Liste des produits filtrée par catégories.
- Détails d’un produit.
- Ajout au panier.
### Commandes :
- Gestion des commandes passées.
- Statut des commandes visible dans le tableau de bord utilisateur.
### Administration :
- Gestion des produits (ajout, modification, suppression).
- Gestion des utilisateurs.
- Dashboard pour les statistiques (commandes, produits, etc.).
### Technologies utilisées
- Backend : Symfony (PHP)
- Base de données : MySQL
- Frontend : Bootstrap pour le style.
### Autres outils :
- Twig pour les templates.
- Traductions avec le composant Translation.

 # Installation
 ## Cloner le repository :
 `git clone <URL_du_repository>`
 `cd <Nom_du_projet>`

 ## Installer les dépendances PHP :
`composer install`

## Configurer la base de données :
### Modifier le fichier .env pour définir les informations de connexion à la base de données :
`DATABASE_URL="mysql://<username>:<password>@127.0.0.1:3306/<nom_de_la_base>"`
### Créer la base de données :
`php bin/console doctrine:database:create`
`php bin/console doctrine:migrations:migrate`
### Installer les dépendances JS et CSS :
`npm install`
`npm run dev`

### Lancer le serveur Symfony :
`symfony serve` ou `symfony serve:start` 
Le projet sera accessible à l'adresse http://localhost:8000.

## Fonctionnalités d'administration
### Pour accéder aux fonctionnalités d'administration, connectez-vous avec un compte ayant le rôle ROLE_ADMIN. Les fonctionnalités incluent :
- Gestion des produits.
- Gestion des utilisateurs.
- Suivi des commandes et statistiques.
### Traductions
Le projet inclut un système de traduction avec les langues suivantes :
- Français (par défaut).
- Anglais.
Pour modifier ou ajouter des traductions, modifiez les fichiers dans translations/messages.{locale}.yaml.

### Aperçu
- **Page d'accueil** : Affiche une liste des produits disponibles, triés par catégories.

- **Panier** : Les utilisateurs peuvent ajouter des produits au panier et finaliser leur commande après s'être connectés.

- **Tableau de bord Administrateur** : Un tableau de bord pour suivre les statistiques et gérer les produits et utilisateurs.
