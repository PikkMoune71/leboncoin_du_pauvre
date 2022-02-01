# leboncoin_du_pauvre
Projet Symfony HETIC - Leboncoin du pauvre

Installation du projet

- Cloner le dépôt git : https://github.com/PikkMoune71/leboncoin_du_pauvre.git

Dans la console du projet pour initialiser le projet :
- composer install
- docker-compose up
- symfony console doctrine:fixtures:load

Pour se connecter avec un user :
- Récupérer une adresse mail dans la BDD à l'aide de cette commande : 	symfony console doctrine:query:sql "SELECT * FROM user"
- Le mot de passe pour se connecter sur n'importe quel compte est : 	password

