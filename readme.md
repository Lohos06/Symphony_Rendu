# Symphony


## Parametrage du projet :

- composer create-project symfony/skeleton:"7.3.x" .  // installation Symphony
- composer require --dev symfony/maker-bundle // installation du bundle de creation de controller
- composer require symfony/twig-bundle // intallation twig


## lancer le serveur :

- Lancer le repo avec laragon
- cd dans public
- php -S 127.0.0.1:8000


## commandes utilitaires :

- php bin/console make:controller ControllerName // creationde controller
- php bin/console debug:router // obtenir les routes crées