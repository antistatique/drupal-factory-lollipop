<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Taxonomy fieldable (with references) for use in tests.
 */
class TaxonomyTermCategoriesFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'taxonomy_categories';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('vocabulary', 'vocabulary_categories', [
      'vid' => 'categories',
    ]);

    $lollipop->define('taxonomy term', 'taxonomy_term_categories', [
      'vid' => $lollipop->association('vocabulary_categories'),
      'field_bar_entity_test' => ['target_id' => 1],
    ]);

    // Add a Foo field without default value.
    $lollipop->define('entity reference field', 'taxonomy_term_categorie_field_foo_entity_test', [
      'entity_type' => 'taxonomy_term',
      'name' => 'field_foo_entity_test',
      'bundle' => $lollipop->association('vocabulary_categories'),
      'target_entity_type' => 'entity_test',
    ]);
    $lollipop->create('taxonomy_term_categorie_field_foo_entity_test');

    // Add a Bar field with default value.
    $lollipop->define('entity reference field', 'taxonomy_term_categorie_field_bar_entity_test', [
      'entity_type' => 'taxonomy_term',
      'name' => 'field_bar_entity_test',
      'bundle' => $lollipop->association('vocabulary_categories'),
      'target_entity_type' => 'entity_test',
    ]);
    $lollipop->create('taxonomy_term_categorie_field_bar_entity_test');
  }

}
