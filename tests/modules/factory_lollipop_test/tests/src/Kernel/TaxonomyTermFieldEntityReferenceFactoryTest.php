<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\entity_test\Entity\EntityTest;
use Drupal\taxonomy\TermInterface;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Term with entity reference fields.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class TaxonomyTermFieldEntityReferenceFactoryTest extends LollipopKernelTestBase {

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
  protected function setUp() {
    parent::setUp();

    // Create an entity test.
    $entity_test = EntityTest::create([
      'type' => 'entity_test',
    ]);
    $entity_test->save();

    $this->installEntitySchema('taxonomy_term');
  }

  /**
   * Ensure defined Taxonomy Field associated to Taxonomy Term can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType::create
   */
  public function testCreateTaxonomyWithFields(): void {
    $this->factoryLollipop->loadDefinitions(['taxonomy_categories']);

    $term = $this->factoryLollipop->create('taxonomy_term_categories', ['name' => 'Duis']);

    // Assert the taxonomy term is created.
    self::assertInstanceOf(TermInterface::class, $term);
    self::assertEquals('categories', $term->bundle());
    self::assertEquals('Duis', $term->getName());

    // Assert a field w/o default values is then empty by default.
    self::assertTrue($term->hasField('field_foo_entity_test'));
    self::assertTrue($term->get('field_foo_entity_test')->isEmpty());

    // Assert a field with default values is filled by default.
    self::assertTrue($term->hasField('field_bar_entity_test'));
    self::assertFalse($term->get('field_bar_entity_test')->isEmpty());
    self::assertSame(1, $term->get('field_bar_entity_test')->target_id);
    self::assertSame('entity_test', $term->get('field_bar_entity_test')->entity->bundle());
  }

  /**
   * Ensure defined Taxonomy Field values can be overridden.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType::create
   */
  public function testCreateTaxonomyWithFieldsValues(): void {
    $this->factoryLollipop->loadDefinitions(['taxonomy_categories']);

    $term = $this->factoryLollipop->create('taxonomy_term_categories', [
      'name' => 'Duis',
      'field_foo_entity_test' => 1,
    ]);

    self::assertInstanceOf(TermInterface::class, $term);
    self::assertEquals('categories', $term->bundle());
    self::assertEquals('Duis', $term->getName());
    self::assertTrue($term->hasField('field_foo_entity_test'));
    self::assertFalse($term->get('field_foo_entity_test')->isEmpty());
    self::assertSame(1, $term->get('field_foo_entity_test')->target_id);
    self::assertSame('entity_test', $term->get('field_foo_entity_test')->entity->bundle());
  }

}
