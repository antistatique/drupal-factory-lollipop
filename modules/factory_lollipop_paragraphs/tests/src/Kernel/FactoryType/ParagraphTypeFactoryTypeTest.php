<?php

namespace Drupal\Tests\factory_lollipop_paragraphs\Kernel\FactoryType;

use Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphTypeFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphTypeFactoryType
 *
 * @group factory_lollipop
 * @group factory_lollipop_paragraphs
 */
class ParagraphTypeFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The Paragraph Type Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\ParagraphTypeFactoryType
   */
  protected $paragraphTypeFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->paragraphTypeFactoryTypeResolver = new ParagraphTypeFactoryType();
    $this->paragraphTypeFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'paragraphs',
    'file',
  ];

  /**
   * @covers ::create
   */
  public function testCreate(): void {
    $paragraph_type = $this->paragraphTypeFactoryTypeResolver->create((object) [
      'id' => 'test',
      'label' => 'Test',
    ]);
    self::assertEquals('test', $paragraph_type->id());
    self::assertEquals('Test', $paragraph_type->label());
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateTwice(): void {
    $this->testCreate();
    $paragraph_type_same = $this->paragraphTypeFactoryTypeResolver->create((object) [
      'id' => 'test',
      'label' => 'Test',
    ]);
    self::assertEquals('test', $paragraph_type_same->id());
    self::assertEquals('Test', $paragraph_type_same->label());
  }

  /**
   * @covers ::create
   */
  public function testCreateRandomLabel(): void {
    $paragraph_type = $this->paragraphTypeFactoryTypeResolver->create((object) [
      'id' => 'test',
    ]);

    self::assertNotEmpty($paragraph_type->label());
    self::assertSame(8, strlen($paragraph_type->label()));
  }

  /**
   * @covers ::create
   */
  public function testCreateRandomId(): void {
    $paragraph_type = $this->paragraphTypeFactoryTypeResolver->create((object) [
      'label' => 'test',
    ]);

    self::assertNotEmpty($paragraph_type->id());
    self::assertSame(8, strlen($paragraph_type->id()));
  }

}
