<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Vocabulary for use in tests.
 */
class VocabularyFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'vocabulary';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('vocabulary', 'vocabulary_tags', [
      'vid' => 'tags',
    ]);

    $lollipop->define('vocabulary', 'vocabulary_categories', []);
  }

}
