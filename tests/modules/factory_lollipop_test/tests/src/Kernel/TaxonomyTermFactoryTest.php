<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\Core\Language\LanguageInterface;
use Drupal\taxonomy\TermInterface;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Taxonomy Term.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class TaxonomyTermFactoryTest extends LollipopKernelTestBase {

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
   * Ensure defined Node associated to Taxonomy Term can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\TaxonomyTermFactoryType::create
   */
  public function testCreateWithAssociatedDefinition(): void {

    $this->factoryLollipop->loadDefinitions(['taxonomy_tags']);

    $term = $this->factoryLollipop->create('taxonomy_term_tags', ['name' => 'Pellentesque vestibulum cras']);

    self::assertInstanceOf(TermInterface::class, $term);
    self::assertEquals('tags', $term->bundle());
    self::assertEquals('Pellentesque vestibulum cras', $term->getName());
    self::assertTrue($term->isPublished());
    self::assertEquals('und', $term->getTranslationLanguages()[LanguageInterface::LANGCODE_NOT_SPECIFIED]->getId());

    $term_2 = $this->factoryLollipop->create('taxonomy_term_tags', [
      'name' => 'Sem erat parturient lacinia risus venenatis magna',
      'langcode' => 'en',
      'status' => FALSE,
    ]);
    self::assertInstanceOf(TermInterface::class, $term_2);
    self::assertEquals('tags', $term_2->bundle());
    self::assertEquals('Sem erat parturient lacinia risus venenatis magna', $term_2->getName());
    self::assertFalse($term_2->isPublished());
    self::assertEquals('en', $term_2->getTranslationLanguages()['en']->getId());
  }

}
