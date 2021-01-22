<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Menus for use in tests.
 */
class MenuFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'menu';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('menu', 'menu_main', [
      'id' => 'main',
      'label' => 'Main Menu',
    ]);

    $lollipop->define('menu', 'menu_footer', [
      'id' => 'footer',
      'label' => 'Footer Menu',
    ]);
  }

}
