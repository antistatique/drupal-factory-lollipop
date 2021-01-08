# Developing on Factory Lollipop

* Pull requests can be made against
https://github.com/antistatique/drupal-factory-lollipop/pulls

## 📦 Repositories

Github repo

  ```bash
  git remote add github \
  https://github.com/antistatique/drupal-factory-lollipop.git
  ```

## 🔧 Prerequisites

First of all, you need to have the following tools installed globally
on your environment:

  * drush
  * The latest dev release of Factory Lollipop
  * docker
  * docker-compose

### Project bootstrap

Once run, you will be able to access to your fresh installed Drupal on `localhost::8888`.

    docker-compose build --pull --build-arg BASE_IMAGE_TAG=8.9 drupal
    # (get a coffee, this will take some time...)
    docker-compose up -d drupal
    docker-compose exec -u www-data drupal drush site-install standard --db-url="mysql://drupal:drupal@db/drupal" -y

    # You may be interesed by reseting the admin password of your Docker.
    docker-compose exec drupal drush user:password admin admin

    # Enable the module to use it.
    docker-compose exec drupal drush en factory_lollipop

## 🏆 Tests

We use the [Docker for Drupal Contrib images](https://hub.docker.com/r/wengerk/drupal-for-contrib) to run testing on our project.

Run testing by stopping at first failure using the following command:

    docker-compose exec -u www-data drupal phpunit --group=factory_lollipop --no-coverage --stop-on-failure --configuration=/var/www/html/phpunit.xml

## 🚔 Check Drupal coding standards & Drupal best practices

During Docker build, the following Static Analyzers will be installed on the Docker `drupal` via Composer:

- `drupal/coder^8.3.1`  (including `squizlabs/php_codesniffer` & `phpstan/phpstan`),

The following Analyzer will be downloaded & installed as PHAR:

- `phpmd/phpmd`

### Command Line Usage

    ./scripts/hooks/post-commit
    # or run command on the container itself
    docker-compose exec drupal bash

#### Running Code Sniffer Drupal & DrupalPractice

https://github.com/squizlabs/PHP_CodeSniffer

PHP_CodeSniffer is a set of two PHP scripts; the main `phpcs` script that tokenizes PHP, JavaScript and CSS files to
detect violations of a defined coding standard, and a second `phpcbf` script to automatically correct coding standard
violations.
PHP_CodeSniffer is an essential development tool that ensures your code remains clean and consistent.

  ```
  $ docker-compose exec drupal ./vendor/bin/phpcs ./web/modules/contrib/factory_lollipop/
  ```

Automatically fix coding standards

  ```
  $ docker-compose exec drupal ./vendor/bin/phpcbf ./web/modules/contrib/factory_lollipop/
  ```

#### Running PHP Mess Detector

https://github.com/phpmd/phpmd

Detect overcomplicated expressions & Unused parameters, methods, properties.

  ```
  $ docker-compose exec drupal phpmd ./web/modules/contrib/factory_lollipop/ text ./phpmd.xml \
  --suffixes php,module,inc,install,test,profile,theme,css,info,txt --exclude *Test.php
  ```

### Enforce code standards with git hooks

Maintaining code quality by adding the custom post-commit hook to yours.

  ```bash
  cat ./scripts/hooks/post-commit >> ./.git/hooks/post-commit
  ```
