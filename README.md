# Factory Lollipop

The goal of this package is to enable the rapid creation of objects (vocabularies, terms, content types, nodes) for the purpose of testing.

The primary aim of Factory Lollipop is to a fill a void in the world of Drupal Unit testing.

|       Tests-CI        |        Style-CI         |        Downloads        |         Releases         |
|:----------------------:|:-----------------------:|:-----------------------:|:------------------------:|
| [![Build Status](https://github.com/antistatique/drupal-factory-lollipop/workflows/Continuous%20integration/badge.svg)](https://github.com/antistatique/drupal-factory-lollipop/actions?query=workflow%3A%22Continuous+integration%22) | [![Code styles](https://github.com/antistatique/drupal-factory-lollipop/workflows/Code%20styles/badge.svg)](https://github.com/antistatique/drupal-factory-lollipop/actions?query=workflow%3A%22Code+styles%22) |  |  |

This module allows you to create templates/blueprints for valid and re-usable Drupal objects.

We created this project in response to fixtures which where fixed global records that had to be loaded into the database.

With Factories, you have more customization when you instantiate the objects & they aim to ensure that you have a valid object to work with.
They can be used anywhere in your tests and in your before and after test setup.

## Use Factory Lollipop if

  * You are working on a client project containing multiple custom modules and want to test them against specific Client project scenarios (Nodes, Types, Fields, ...).

    Indeed, the Drupal Unit Test system will never load your custom Profile or Drupal Sync Configuration. Therefore, you will need - for each test - to re-create Nodes-Types, Nodes Data, Fields, etc ...
    By using Factory Lollipop, you will be able to create Blueprint (factory) to be loaded and used by scenarios.

  * You have a Contrib module/theme for Drupal and want to test it against coherent data/scenario.

    By using Factory design pattern throughout Factory Lollipop, you will be able to create Factory blueprint for your scenarios and ensure data consistency across your tests suits.

## Usage

Check out the [official documentation](https://www.drupal.org/docs/contributed-modules/factory-lollipop) about Factory Lollipop.

You may also be interested in this article about Factory Lollipop:

  * [Kevin Wenger's](https://github.com/wengerk) article is available [here]().

Two submodules provide examples of implementing Factory Lollipop.

  * factory_lollipop_example - A carefully documented implementation of Factories scenario, designed to walk you through the basic concepts of the Drupal Factory Test framework.
  * factory_lollipop_example_advanced (still in progress) - Examples of more advanced techniques for Drupal factories.

If you want more information, the following resources are available to you:

  * Our [test suite](tests) may also be useful to you.
  * Our [advanced test suite](tests/modules/factory_lollipop_test) may also be useful to you.

## Supported Types

At present, Factory Lollipop has support for the following types of data in Drupal:

  * Content Types (Node Types)
  * Nodes
  * Field
  * Vocabularies
  * Taxonomy Terms
  * Users
  * Roles
  * Menus
  * Menu Links
  * File
  * Media Type
  * Media

You can easily add/override any FactoryType by using the FactoryType Resolver. See the [official documentation](https://www.drupal.org/docs/contributed-modules/factory-lollipop) about it.

## Getting Started

We highly recommend you to install the module using `composer`.

```bash
$ composer require --dev drupal/factory_lollipop
```

## Upgrading

It may be useful for existing users to check out the [upgrade guide](UPGRADING.md).

## Versions

Factory Lollipop is available for both Drupal 8 and Drupal 9!

### Which version should I use?

|Drupal Core|Factory Lollipop|
|:---------:|:--------------:|
|8.9.x      |1.0.x           |
|9.x        |1.0.x           |

## Roadmap

This outlines the development plan from a high level and will be updated as progress are made toward version 1.0.

  * Drush's integration to facilitate the creation of factories based on existing project configuration.
  * Drupal Commerce's Factories (Store, Price, Product, Variation, Payment Method, Workflow, ...).

_Everything contained before is in draft form and subject to change. We do not guarantee the accuracy of the information contained in this roadmap, and the information is provided “as is” with no representations or warranties, express or implied._

## Dependencies

The Drupal 8 and Drupal 9 version of Factory Lollipop requires nothing !
Feel free to use it.

Factory Lollipop requires PHP 7.3+ to works properly. We recommend updating to at least PHP 7.4 if possible, which is supported as of Drupal 8.8.3 (release date: March 7, 2018).

## Similar modules

  * [Factory Drone](https://www.drupal.org/project/factorydrone) A comprehensive solution for using the factory pattern to generate short-lived test data on Drupal 7.

## Similar libraries

  * [Factory Muffin](https://github.com/thephpleague/factory-muffin) Enables the rapid creation of objects for testing. It's basically a "Factory Bot", simplified for use with PHP.
  * [Foundry](https://github.com/zenstruck/foundry) A model factory library for creating expressive, auto-completable, on-demand dev/test fixtures with Symfony and Doctrine.
  * [Phactory](https://github.com/chriskite/phactory) A Database Factory for PHP Unit Tests.
  * [Factory Bot](https://github.com/thoughtbot/factory_bot) A library for setting up Ruby objects as test data.
  * [Sham](https://github.com/panthomakos/sham/) Lightweight flexible factories for Ruby and Rails testing.

## Supporting organizations

This project is sponsored by [Antistatique](https://www.antistatique.net), a Swiss Web Agency.
Visit us at [www.antistatique.net](https://www.antistatique.net) or
[Contact us](mailto:info@antistatique.net).

## Credits

Factory Lollipop is currently maintained by [Kevin Wenger](https://github.com/wengerk). Thank you to all our wonderful [contributors](https://github.com/antistatique/drupal-factory-lollipop/contributors) too.
