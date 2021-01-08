<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Node Type for use in tests.
 */
class NodeTypeArticleFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'node_type_article';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('node type', 'node_type_article', [
      'type' => 'article',
    ]);
  }

}
