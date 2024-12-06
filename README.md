# Mini-site E-Commerce ayant comme thème: La décoration intérieure

# Description
Ce projet est une plateforme de commerce électronique sur le thème de la décoration intérieure. Il permet aux utilisateurs de parcourir et d'acheter des produits en fonction des catégories disponibles, comme les meubles, les décorations murales ou les accessoires. Une partie d'administration est mise en place pour permettre aux administrateurs de gérer au mieux le site. Le projet est réalisé avec Symfony, un framework PHP, et suit les meilleures pratiques du développement web.

# Structure du projet
## Fonctionnalités principales
### Utilisateurs :
- Inscription et connexion.
- Gestion du compte utilisateur.
- Ajout et gestion des articles dans le panier.
### Catalogue des produits (Boutique) :
- Liste des produits.
- Ajout au panier.
### Panier
- Ajout, suppression, et modification des quantités des produits.
- Calcul automatique du sous-total et du total.
- Paiement.
### Ajout et gestion des cartes bancaires
- Les utilisateurs peuvent enregistrer plusieurs cartes bancaires sans rechargement de la page.
- Validation automatique du numéro de carte, de la date d'expiration, et du code CVV.
- Après enregistrement de ou des cartes, affichage des cartes enregistrées (seules les 4 dernières chiffres sont visibles).
### Commandes :
- Gestion des commandes passées.
- Statut des commandes visible dans le tableau de bord utilisateur.
### Administration :
- Gestion des produits (ajout, modification, suppression).
- Gestion des utilisateurs et de leurs commandes.
- Tableau de bord pour les statistiques (Suivi des ventes par catégorie et par mois.).
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
`php bin/console make:migration`
`php bin/console doctrine:migrations:migrate`
### Exécuter les fixtures pour ajouter les données à la base de données
`php bin/console doctrine:fixtures:load`
### Installer les dépendances JS et CSS :
`npm install`
`npm run dev`

### Lancer le serveur Symfony :
`symfony serve` ou `symfony serve:start` 
Le projet sera accessible à l'adresse http://localhost:8000/home.

## Fonctionnalités d'administration
### Pour accéder aux fonctionnalités d'administration, connectez-vous avec un compte ayant le rôle ROLE_ADMIN. Les fonctionnalités incluent :
- Gestion des produits.
- Gestion des utilisateurs.
- Suivi des commandes et statistiques.
### Traductions
Le projet inclut un système de traduction avec les langues suivantes :
- Français (par défaut).
- Anglais.
Pour modifier ou ajouter des traductions, modifiez les fichiers dans translations/messages.{locale}.yaml. Comme exemple, nous pouvons avoir messages.fr.yaml.
