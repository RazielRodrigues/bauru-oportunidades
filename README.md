# HEROKU DEPLOY

    web: heroku-php-apache2 public/

    "compile": [
        "php bin/console doctrine:database:create --if-not-exists",
        "php bin/console doctrine:migrations:migrate "
    ]

# START PROJECT

    use the run shell

# DEPENDENCIES
    - php
    - mysql
    - docker (in the future)
    
# PROJECT ENVIRONMENTS
    - PROD: https://bauruoportunidades.herokuapp.com/
    - STAGING:
    - EDGE
