<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\taxonomy\TermInterface;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Taxonomy Term with fields.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class TaxonomyTermFieldFactoryTest extends LollipopKernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'taxonomy',
    'factory_lollipop_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('taxonomy_term');
  }

  /**
   * Ensure defined Taxonomy Field associated to Taxonomy Term can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType::create
   */
  public function testCreateTaxonomyWithFields(): void {
    $this->factoryLollipop->loadDefinitions(['taxonomy_countries']);

    $term = $this->factoryLollipop->create('taxonomy_term_countries', ['name' => 'Zurich']);

    // Assert the taxonomy term is created.
    self::assertInstanceOf(TermInterface::class, $term);
    self::assertEquals('countries', $term->bundle());
    self::assertEquals('Zurich', $term->getName());

    // Assert a field w/o default values is then empty by default.
    self::assertTrue($term->hasField('field_foo'));
    self::assertTrue($term->get('field_foo')->isEmpty());

    // Assert a field with default values is filled by default.
    self::assertTrue($term->hasField('field_bar'));
    self::assertFalse($term->get('field_bar')->isEmpty());
    self::assertSame('Diam aliquam facilisis non netus', $term->get('field_bar')->value);
  }

  /**
   * Ensure defined Taxonomy Field values can be overridden.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType::create
   */
  public function testCreateTaxonomyWithFieldsValues(): void {
    $this->factoryLollipop->loadDefinitions(['taxonomy_countries']);

    $term = $this->factoryLollipop->create('taxonomy_term_countries', [
      'name' => 'Zurich',
      'field_foo' => 'foo@bar.com',
    ]);

    self::assertInstanceOf(TermInterface::class, $term);
    self::assertEquals('countries', $term->bundle());
    self::assertEquals('Zurich', $term->getName());
    self::assertTrue($term->hasField('field_foo'));
    self::assertFalse($term->get('field_foo')->isEmpty());
    self::assertSame('foo@bar.com', $term->get('field_foo')->value);
  }

}
