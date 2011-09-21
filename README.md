Talkfest
==========

Talkfest is a PHP 5.3 discussion board based on the Symfony 2 framework that allows for category-based posts with commenting functionality similar to that of sites like reddit and hacker news.

## Development Status

Beta - This application is still under development and is not ready for production usage.  The core functionality is complete, however, and I'd love to have more people testing it out.

## Features

* Custom categories are used to group similar types of discussions (e.g. Funny, Politics, etc.)
* Posts represent the starting point of a specific discussion (e.g. Some random thought, some link I saw, etc.)
* Comments are responses to any specific post
* Main post stream that lists all posts across all categories
* Individual category streams for posts
* User management via the popular FOSUserBundle component

## Features In Development

* Markdown-based comment formatting
* Ability for admins to edit any comment and authors to edit their own comments
* Visual pagination controls
* Various minor user interface improvements

## Installation

1. Copy app/config/parameters.ini.dist to app/config/parameters.ini and update with your local values
2. php bin/vendors install
3. php app/console doctrine:database:create
4. php app/console init:acl
5. php app/console doctrine:schema:update --force
6. php app/console assets:install web