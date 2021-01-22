<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\factory_lollipop\FactoryType\VocabularyFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\VocabularyFactoryType
 *
 * @group factory_lollipop
 */
class VocabularyFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The Vocabulary Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\VocabularyFactoryType
   */
  protected $vocabularyFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->vocabularyFactoryTypeResolver = new VocabularyFactoryType();
    $this->vocabularyFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'taxonomy',
  ];

  /**
   * @covers ::create
   */
  public function testCreate(): void {
    $vocabulary = $this->vocabularyFactoryTypeResolver->create((object) [
      'vid' => 'tags',
      'name' => 'Tags',
    ]);
    self::assertEquals('tags', $vocabulary->id());
    self::assertEquals('Tags', $vocabulary->label());
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateTwice(): void {
    $this->testCreate();
    $vocabulary = $this->vocabularyFactoryTypeResolver->create((object) [
      'vid' => 'tags',
      'name' => 'Tags',
    ]);
    self::assertEquals('tags', $vocabulary->id());
    self::assertEquals('Tags', $vocabulary->label());
  }

  /**
   * @covers ::create
   */
  public function testCreateRandomVid(): void {
    $vocabulary = $this->vocabularyFactoryTypeResolver->create((object) [
      'name' => 'Tags',
    ]);
    self::assertEquals('Tags', $vocabulary->label());
    self::assertNotEmpty($vocabulary->id());
    self::assertSame(8, strlen($vocabulary->id()));
  }

  /**
   * @covers ::create
   */
  public function testCreateRandomName(): void {
    $vocabulary = $this->vocabularyFactoryTypeResolver->create((object) [
      'vid' => 'tags',
    ]);
    self::assertEquals('tags', $vocabulary->id());
    self::assertNotEmpty($vocabulary->label());
    self::assertSame(8, strlen($vocabulary->label()));
  }

}
