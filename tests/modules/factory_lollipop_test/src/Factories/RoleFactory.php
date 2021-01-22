<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Roles for use in tests.
 */
class RoleFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'role';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('role', 'role_architect', [
      'rid' => 'architect',
      'permissions' => ['administer themes'],
    ]);

    $lollipop->define('role', 'role_superuser', [
      'rid' => 'superuser',
    ]);
  }

}
