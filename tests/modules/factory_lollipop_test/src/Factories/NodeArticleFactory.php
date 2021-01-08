<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Nodes for use in tests.
 */
class NodeArticleFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'node_article';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('node type', 'node_type_article', [
      'type' => 'article',
    ]);

    $lollipop->define('node', 'node_article', [
      'type'   => $lollipop->association('node_type_article'),
      'status' => 1,
    ]);
  }

}
