#!/bin/sh
 app/console cache:clear --env=prod

 app/console cache:clear

 app/console assets:install web --symlink

 app/console assetic:dump

 composer dump-autoload --optimize