<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\file\Entity\File as EntityFile;
use Drupal\media\MediaInterface;
use Drupal\media\Plugin\media\Source\File;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Media with fields.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class MediaFieldFactoryTest extends LollipopKernelTestBase {

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
    $this->factoryLollipop->loadDefinitions(['media_file']);

    /** @var \Drupal\media\MediaInterface $media_type */
    $media = $this->factoryLollipop->create('media_file');

    // Assert the media is created.
    self::assertInstanceOf(MediaInterface::class, $media);
    self::assertInstanceOf(File::class, $media->getSource());
    self::assertEquals('media_file', $media->bundle());

    // Assert a source field has been created and is empty.
    self::assertTrue($media->hasField('field_media_file'));
    self::assertTrue($media->get('field_media_file')->isEmpty());

    // Assert a field w/o default values is then empty by default.
    self::assertTrue($media->hasField('field_foo'));
    self::assertTrue($media->get('field_foo')->isEmpty());

    // Assert a field with default values is filled by default.
    self::assertTrue($media->hasField('field_bar'));
    self::assertFalse($media->get('field_bar')->isEmpty());
    self::assertSame('Aenean tortor convallis nibh', $media->get('field_bar')->value);
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
    $this->factoryLollipop->loadDefinitions(['media_file']);

    $media = $this->factoryLollipop->create('media_file', [
      'name' => 'Sit vulputate et eros per netus fusce nisl congue dignissim curabitur augue',
      'field_foo' => 'foo@bar.com',
    ]);

    self::assertInstanceOf(MediaInterface::class, $media);
    self::assertInstanceOf(File::class, $media->getSource());
    self::assertEquals('media_file', $media->bundle());
    self::assertEquals('Sit vulputate et eros per netus fusce nisl congue dignissim curabitur augue', $media->getName());
    self::assertTrue($media->hasField('field_foo'));
    self::assertFalse($media->get('field_foo')->isEmpty());
    self::assertSame('foo@bar.com', $media->get('field_foo')->value);
  }

  /**
   * Ensure defined Media can be associated with a defined file.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType::create
   */
  public function testCreateWithAssociatedDefinitionFile(): void {
    $this->factoryLollipop->loadDefinitions(['media_file']);

    $file = EntityFile::create([
      'uri' => 'temporary://example.txt',
      'filename' => 'example.txt',
    ]);
    $file->save();

    $media = $this->factoryLollipop->create('media_file', [
      'name' => 'Per nascetur aptent',
      'field_media_file' => [
        'target_id' => $file->id(),
      ],
    ]);

    self::assertInstanceOf(MediaInterface::class, $media);
    self::assertInstanceOf(File::class, $media->getSource());
    self::assertEquals('media_file', $media->bundle());
    self::assertEquals('Per nascetur aptent', $media->getName());

    // Assert a source field has been created and is not empty.
    self::assertTrue($media->hasField('field_media_file'));
    self::assertFalse($media->get('field_media_file')->isEmpty());
    self::assertInstanceOf(EntityFile::class, $media->field_media_file->entity);
  }

}
