Talkfest
==========

Talkfest is a PHP 5.3 forum based on the Symfony 2 framework.

## Installation

1. Copy app/config/parameters.ini.dist to app/config/parameters.ini and update with your local values
2. php bin/vendors install
3. php app/console doctrine:database:create
4. php app/console init:acl
5. php app/console doctrine:schema:update --force
6. php app/console assets:install web