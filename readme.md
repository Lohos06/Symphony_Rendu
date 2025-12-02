# Symphony


## Parametrage du projet au lancement :

- composer create-project symfony/skeleton:"7.3.x" .  // installation Symphony
- composer require --dev symfony/maker-bundle // installation du bundle de creation de controller
- composer require symfony/twig-bundle // intallation twig

## instalation des dependances quand on recupere le repo :

- composer install

## lancer le serveur :

- Lancer le repo avec laragon
- cd dans public
- php -S 127.0.0.1:8000


## commandes utilitaires :

- php bin/console make:controller ControllerName // creationde controller
- php bin/console debug:router // obtenir les routes crées