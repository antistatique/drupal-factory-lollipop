<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Nodes fieldable (with references) for use in tests.
 */
class NodeNewsFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'node_news';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('node type', 'node_type_news', [
      'type' => 'news',
    ]);

    $lollipop->define('node', 'node_news', [
      'type'   => $lollipop->association('node_type_news'),
      'status' => 1,
      'field_bar_entity_test' => ['target_id' => 1],
    ]);

    // Add a Foo field without default value.
    $lollipop->define('entity reference field', 'node_news_field_foo_entity_test', [
      'entity_type' => 'node',
      'name' => 'field_foo_entity_test',
      'bundle' => $lollipop->association('node_type_news'),
      'target_entity_type' => 'entity_test',
    ]);
    $lollipop->create('node_news_field_foo_entity_test');

    // Add a Bar field with default value.
    $lollipop->define('entity reference field', 'node_news_field_bar_entity_test', [
      'entity_type' => 'node',
      'name' => 'field_bar_entity_test',
      'bundle' => $lollipop->association('node_type_news'),
      'target_entity_type' => 'entity_test',
    ]);
    $lollipop->create('node_news_field_bar_entity_test');
  }

}
