<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\entity_test\Entity\EntityTest;
use Drupal\media\MediaInterface;
use Drupal\media\Plugin\media\Source\VideoFile;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Media with entity reference fields.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class MediaFieldEntityReferenceFactoryTest extends LollipopKernelTestBase {

  /**
   * The Factory Lollipop fixture factory.
   *
   * @var \Drupal\factory_lollipop\FixtureFactory
   */
  protected $factoryLollipop;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'media',
    'file',
    'image',
    'factory_lollipop_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('file');
    $this->installSchema('file', 'file_usage');
    $this->installEntitySchema('media');

    // Create an entity test.
    $entity_test = EntityTest::create([
      'type' => 'entity_test',
    ]);
    $entity_test->save();
  }

  /**
   * Ensure a defined Media Field can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\MediaTypeFactoryType::create
   */
  public function testCreateMediaWithFields(): void {
    $this->factoryLollipop->loadDefinitions(['media_video']);

    /** @var \Drupal\media\MediaInterface $media_type */
    $media = $this->factoryLollipop->create('media_video');

    // Assert the media is created.
    self::assertInstanceOf(MediaInterface::class, $media);
    self::assertInstanceOf(VideoFile::class, $media->getSource());
    self::assertEquals('media_video', $media->bundle());

    // Assert a source field has been created and is empty.
    self::assertTrue($media->hasField('field_media_video_file'));
    self::assertTrue($media->get('field_media_video_file')->isEmpty());

    // Assert a field w/o default values is then empty by default.
    self::assertTrue($media->hasField('field_foo_entity_test'));
    self::assertTrue($media->get('field_foo_entity_test')->isEmpty());

    // Assert a field with default values is filled by default.
    self::assertTrue($media->hasField('field_bar_entity_test'));
    self::assertFalse($media->get('field_bar_entity_test')->isEmpty());
    self::assertSame(1, $media->get('field_bar_entity_test')->target_id);
    self::assertSame('entity_test', $media->get('field_bar_entity_test')->entity->bundle());
  }

  /**
   * Ensure defined Media Field values can be overridden.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType::create
   */
  public function testCreateMediaWithFieldsValues(): void {
    $this->factoryLollipop->loadDefinitions(['media_video']);

    // Create an entity test.
    $entity_test = EntityTest::create([
      'type' => 'entity_test',
    ]);
    $entity_test->save();

    $media = $this->factoryLollipop->create('media_video', [
      'name' => 'Nullam',
      'field_foo_entity_test' => 2,
    ]);

    self::assertInstanceOf(MediaInterface::class, $media);
    self::assertInstanceOf(VideoFile::class, $media->getSource());
    self::assertEquals('media_video', $media->bundle());
    self::assertEquals('Nullam', $media->getName());

    self::assertTrue($media->hasField('field_foo_entity_test'));
    self::assertFalse($media->get('field_foo_entity_test')->isEmpty());
    self::assertSame(2, $media->get('field_foo_entity_test')->target_id);
    self::assertSame('entity_test', $media->get('field_foo_entity_test')->entity->bundle());
  }

}
