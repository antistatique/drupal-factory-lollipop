<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Nodes fieldable for use in tests.
 */
class NodePageFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'node_page';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('node type', 'node_type_page', [
      'type' => 'page',
    ]);

    $lollipop->define('node', 'node_page', [
      'type'   => $lollipop->association('node_type_page'),
      'status' => 1,
      'field_bar' => 'Viverra iaculis',
    ]);

    // Add a Foo field without default value.
    $lollipop->define('entity field', 'node_page_field_foo', [
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => $lollipop->association('node_type_page'),
      'type' => 'email',
    ]);
    $lollipop->create('node_page_field_foo');

    // Add a Bar field with default value.
    $lollipop->define('entity field', 'node_page_field_bar', [
      'entity_type' => 'node',
      'name' => 'field_bar',
      'bundle' => $lollipop->association('node_type_page'),
      'type' => 'text',
    ]);
    $lollipop->create('node_page_field_bar');
  }

}
