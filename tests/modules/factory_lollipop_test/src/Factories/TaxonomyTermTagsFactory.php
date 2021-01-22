<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Taxonomy Term for use in tests.
 */
class TaxonomyTermTagsFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'taxonomy_tags';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('vocabulary', 'vocabulary_tags', [
      'vid' => 'tags',
    ]);

    $lollipop->define('taxonomy term', 'taxonomy_term_tags', [
      'vid' => $lollipop->association('vocabulary_tags'),
    ]);
  }

}
