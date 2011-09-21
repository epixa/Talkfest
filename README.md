Talkfest
==========

Talkfest is a PHP 5.3 discussion board based on the Symfony 2 framework that allows for category-based posts with commenting functionality similar to that of sites like reddit and hacker news.

## Development Status

Beta - This application is still under development and is not ready for production usage.  The core functionality is complete, however, and I'd love to have more people testing it out.

## Features

* Custom categories are used to group similar types of discussions (e.g. Funny, Politics, etc.)
* Posts represent the starting point of a specific discussion (e.g. Some random thought, some link I saw, etc.)
* Comments are responses to any specific post that can be modified by their author or admins
* Main post stream that lists all posts across all categories
* Individual category streams for posts
* User management via the popular FOSUserBundle component

## Features Intended for Stable Release

* Markdown-based comment formatting
* Various user interface improvements
* Command line tools

## Installation

Most of the installation is done through symfony's command line tools.  After cloning this repository, follow these steps:

1. Copy app/config/parameters.ini.dist to app/config/parameters.ini and update with your local values
2. php bin/vendors install
3. php app/console doctrine:database:create
4. php app/console init:acl
5. php app/console doctrine:schema:update --force
6. php app/console assets:install web
7. Create a new super admin: php app/console fos:user:create myadminusername --super-admin
8. Log into this new super admin account at /login
9. Create your first category at /category/add

New users can be created via the signup page (/signup).