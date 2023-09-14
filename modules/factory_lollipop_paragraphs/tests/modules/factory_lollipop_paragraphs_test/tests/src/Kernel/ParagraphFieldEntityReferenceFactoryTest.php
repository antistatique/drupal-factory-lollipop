<?php

namespace Drupal\Tests\factory_lollipop_paragraphs_test\Kernel;

use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\entity_test\Entity\EntityTest;

/**
 * Example of Factory Lollipop usage for Paragraph with entity reference fields.
 *
 * @group factory_lollipop
 * @group factory_lollipop_paragraphs
 * @group factory_lollipop_example
 */
class ParagraphFieldEntityReferenceFactoryTest extends LollipopKernelTestBase {

  /**
   * The Factory Lollipop fixture factory.
   *
   * @var \Drupal\factory_lollipop\FixtureFactory
   */
  protected $factoryLollipop;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    // Required by Paragraphs.
    'paragraphs',
    'entity_reference_revisions',
    'file',
    // Factory Lollipop.
    'factory_lollipop_paragraphs',
    'factory_lollipop_paragraphs_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('paragraph');

    // Create an entity test.
    $entity_test = EntityTest::create([
      'type' => 'entity_test',
    ]);
    $entity_test->save();
  }

  /**
   * Ensure defined Paragraph Field Entity Reference can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType::create
   */
  public function testCreateParagraphWithFields(): void {
    $this->factoryLollipop->loadDefinitions(['paragraph_embed_news']);

    $paragraph = $this->factoryLollipop->create('paragraph_embed_news');

    // Assert the Paragraph is created.
    self::assertInstanceOf(Paragraph::class, $paragraph);
    self::assertEquals('embed_news', $paragraph->bundle());

    // Assert a field w/o default values is then empty by default.
    self::assertTrue($paragraph->hasField('field_foo_entity_test'));
    self::assertTrue($paragraph->get('field_foo_entity_test')->isEmpty());

    // Assert a field with default values is filled by default.
    self::assertTrue($paragraph->hasField('field_bar_entity_test'));
    self::assertFalse($paragraph->get('field_bar_entity_test')->isEmpty());
    self::assertSame(1, $paragraph->get('field_bar_entity_test')->target_id);
    self::assertSame('entity_test', $paragraph->get('field_bar_entity_test')->entity->bundle());
  }

  /**
   * Ensure defined Paragraph Field Entity Reference values can be overridden.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType::create
   */
  public function testCreateNodeWithFieldsValues(): void {
    $this->factoryLollipop->loadDefinitions(['paragraph_embed_news']);

    $paragraph = $this->factoryLollipop->create('paragraph_embed_news', [
      'field_foo_entity_test' => 1,
    ]);

    self::assertInstanceOf(Paragraph::class, $paragraph);
    self::assertEquals('embed_news', $paragraph->bundle());
    self::assertTrue($paragraph->hasField('field_foo_entity_test'));
    self::assertFalse($paragraph->get('field_foo_entity_test')->isEmpty());
    self::assertSame(1, $paragraph->get('field_foo_entity_test')->target_id);
    self::assertSame('entity_test', $paragraph->get('field_foo_entity_test')->entity->bundle());
  }

}
