<?php

namespace Drupal\Tests\factory_lollipop_paragraphs_test\Kernel;

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Paragraph.
 *
 * @group factory_lollipop
 * @group factory_lollipop_paragraphs
 * @group factory_lollipop_example
 */
class ParagraphFactoryTest extends LollipopKernelTestBase {

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
  }

  /**
   * Ensure defined Paragraph associated to Paragraph Type can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphFactoryType::create
   */
  public function testCreateWithAssociatedDefinition(): void {
    $this->factoryLollipop->loadDefinitions(['paragraph_accordion']);

    $paragraph = $this->factoryLollipop->create('paragraph_accordion');

    self::assertInstanceOf(Paragraph::class, $paragraph);
    self::assertEquals('accordion', $paragraph->bundle());
    self::assertTrue($paragraph->isPublished());
  }

  /**
   * Ensure the ParagraphFactory is overridable.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\ParagraphFactoryType::create
   */
  public function testDefineOverride() {
    $this->factoryLollipop->loadDefinitions(['paragraph_accordion']);

    // Override the definition of paragraph_accordion.
    $this->factoryLollipop->define('paragraph', 'paragraph_accordion', [
      'type' => $this->factoryLollipop->association('paragraph_type_accordion'),
      'status' => FALSE,
    ]);

    $paragraph = $this->factoryLollipop->create('paragraph_accordion', []);
    self::assertInstanceOf(Paragraph::class, $paragraph);
    self::assertEquals('accordion', $paragraph->bundle());
    self::assertFalse($paragraph->isPublished());
  }

}
