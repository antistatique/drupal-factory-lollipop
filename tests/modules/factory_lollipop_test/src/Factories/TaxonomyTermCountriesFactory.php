<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Taxonomy fieldable for use in tests.
 */
class TaxonomyTermCountriesFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'taxonomy_countries';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('vocabulary', 'vocabulary_countries', [
      'vid' => 'countries',
    ]);

    $lollipop->define('taxonomy term', 'taxonomy_term_countries', [
      'vid' => $lollipop->association('vocabulary_countries'),
      'field_bar' => 'Diam aliquam facilisis non netus',
    ]);

    // Add a Foo field without default value.
    $lollipop->define('entity field', 'taxonomy_term_countries_field_foo', [
      'entity_type' => 'taxonomy_term',
      'name' => 'field_foo',
      'bundle' => $lollipop->association('vocabulary_countries'),
      'type' => 'email',
    ]);
    $lollipop->create('taxonomy_term_countries_field_foo');

    // Add a Bar field with default value.
    $lollipop->define('entity field', 'taxonomy_term_countries_field_bar', [
      'entity_type' => 'taxonomy_term',
      'name' => 'field_bar',
      'bundle' => $lollipop->association('vocabulary_countries'),
      'type' => 'text',
    ]);
    $lollipop->create('taxonomy_term_countries_field_bar');
  }

}
