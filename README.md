# HEROKU DEPLOY

    web: heroku-php-apache2 public/

    "compile": [
        "php bin/console doctrine:database:create --if-not-exists",
        "php bin/console doctrine:migrations:migrate "
    ]
