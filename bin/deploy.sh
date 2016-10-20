#!/bin/bash
chmod 0775 ./app/console
composer install --optimize-autoloader --no-interaction
composer deploy

