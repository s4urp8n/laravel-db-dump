# Database sql dump creator for laravel

❗ Currently supported only MySQl

## Installation

```
composer require s4urp8n/laravel-db-dump
```

## Publish config and configure

```
php artisan vendor:publish
```

## Run command

```
php artisan db:dump
```

## Result
```
<project_dir>/storage
└───database
    └───dumps
        └───2023_02_12_09_31_13
            └───mysql
                ├───admin
                │       categories.sql
                │       users.sql
                │
                ├───geoip
                │       builds.sql
                │       company.sql
                │
                └───gtf
                        access_list.sql
                        access_log.sql
```
