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
