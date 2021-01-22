<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Menu Link for use in tests.
 */
class MenuLinkFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'menu_link';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('menu link', 'menu_link_parent', [
      'title' => 'parent',
      'provider' => 'menu_test',
      'menu_name' => 'menu_test',
      'bundle' => 'menu_link_content',
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent'],
    ]);
  }

}
