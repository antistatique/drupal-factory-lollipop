<?php

namespace Drupal\Tests\factory_lollipop_paragraphs_test\Kernel;

use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Paragraph Type.
 *
 * @group factory_lollipop
 * @group factory_lollipop_paragraphs
 * @group factory_lollipop_example
 */
class ParagraphTypeFactoryTest extends LollipopKernelTestBase {

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
   * Ensure a defined Paragraph Type can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphTypeFactoryType::create
   */
  public function testDefine(): void {
    $this->factoryLollipop->loadDefinitions(['paragraph_type_accordion']);

    // Ensure the association (Paragraph Type generation) is made on ::create().
    $paragraph_type = ParagraphsType::load('accordion');
    self::assertNull($paragraph_type);

    /** @var \Drupal\paragraphs\Entity\ParagraphsType $paragraph_type */
    $paragraph_type = $this->factoryLollipop->create('paragraph_type_accordion');
    self::assertInstanceOf(ParagraphsType::class, $paragraph_type);
    self::assertEquals('accordion', $paragraph_type->id());

    // Paragraph Type has been created with proper id.
    $paragraph_type = ParagraphsType::load('accordion');
    self::assertNotNull($paragraph_type);
  }

}
