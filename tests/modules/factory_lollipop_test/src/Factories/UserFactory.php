<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Users for use in tests.
 */
class UserFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'user';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('user', 'user', []);

    $lollipop->define('user', 'user_admin', [
      'roles' => 'administrator',
    ]);

    $lollipop->define('user', 'user_moderator', [
      'roles' => 'moderator',
    ]);
  }

}
