<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Menu with Links for use in tests.
 */
class MenuMainFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'menu_main';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('menu', 'menu_main', [
      'id' => 'main',
    ]);

    // Then create a simple link hierarchy:
    // - parent
    //   - child-1
    //     - child-1-1
    //   - child-2.
    // Parent.
    $lollipop->define('menu link', 'menu_main_link_parent', [
      'title' => 'parent',
      'provider' => $lollipop->association('menu_main'),
      'menu_name' => $lollipop->association('menu_main'),
      'bundle' => 'menu_link_content',
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent'],
    ]);

    // child-1.
    $lollipop->define('menu link', 'menu_main_link_child_1', [
      'title' => 'child-1',
      'provider' => $lollipop->association('menu_main'),
      'menu_name' => $lollipop->association('menu_main'),
      'bundle' => 'menu_link_content',
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent/child-1'],
      'parent' => $lollipop->association('menu_main_link_parent'),
    ]);

    // child-1-1.
    $lollipop->define('menu link', 'menu_main_link_child_1_1', [
      'title' => 'child-1-1',
      'provider' => $lollipop->association('menu_main'),
      'menu_name' => $lollipop->association('menu_main'),
      'bundle' => 'menu_link_content',
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent/child-1/child-1-1'],
      'parent' => $lollipop->association('menu_main_link_child_1'),
    ]);

    // child-2.
    $lollipop->define('menu link', 'menu_main_link_child_2', [
      'title' => 'child-2',
      'provider' => $lollipop->association('menu_main'),
      'menu_name' => $lollipop->association('menu_main'),
      'bundle' => 'menu_link_content',
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent/child-2'],
      'parent' => $lollipop->association('menu_main_link_parent'),
    ]);
  }

}
