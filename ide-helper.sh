#!/usr/bin/env bash

composer dump-autoload
php artisan ide-helper:generate -n
php artisan ide-helper:meta -n
php artisan ide-helper:models -n
