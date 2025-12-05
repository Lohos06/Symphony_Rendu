# Symphony

## Titre : Symfony Rendu:
- Yasmine
- Clara
- Lorenzo
- Maxym

## Description : 
- Nous avons créer un site permettant d'ajouter des livres à la bibliothèque. Nous affichons la liste des auteurs et des genres de chaque livre . Lorsque on est en admin on peut modifié les genres et auteurs mais pas quand on est user.


## Parametrage du projet au lancement :

- composer create-project symfony/skeleton:"7.3.x" .  // installation Symphony
- composer require --dev symfony/maker-bundle // installation du bundle de creation de controller
- composer require symfony/twig-bundle // intallation twig
- composer require symfony/asset // rajouter du CSS
- composer require symfony/orm-pack // ajout de l'orm
- composer require symfony/maker-bundle --dev // surement le meme maker
- composer require symfony/form // formulaires

## instalation des dependances quand on recupere le repo :

- composer install
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate

## lancer le serveur :

- Lancer le repo avec laragon
- cd dans public
- php -S 127.0.0.1:8000


## commandes utilitaires :

- php bin/console make:controller ControllerName // creationde controller
- php bin/console debug:router // obtenir les routes crées
- php bin/console make:entity // faire une entité
- php bin/console make:migration // creer une nouvelle version de la base
- php bin/console doctrine:migrations:migrate // envoyer une migration en base



