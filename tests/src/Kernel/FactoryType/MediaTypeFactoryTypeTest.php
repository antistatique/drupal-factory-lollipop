<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\factory_lollipop\FactoryType\MediaTypeFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\media\MediaSourceInterface;
use Drupal\media\Plugin\media\Source\AudioFile;
use Drupal\media\Plugin\media\Source\File;
use Drupal\media\Plugin\media\Source\Image;
use Drupal\media\Plugin\media\Source\OEmbedInterface;
use Drupal\media\Plugin\media\Source\VideoFile;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\MediaTypeFactoryType
 *
 * @group factory_lollipop
 */
class MediaTypeFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The Media Type Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\MediaTypeFactoryType
   */
  protected $mediaTypeFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->mediaTypeFactoryTypeResolver = new MediaTypeFactoryType();
    $this->mediaTypeFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'media',
    'file',
    'image',
  ];

  /**
   * @covers ::create
   *
   * @dataProvider providerMediaTypeValues
   */
  public function testCreate(array $data, string $expected_source_class, string $expected_source_field): void {
    $media_type = $this->mediaTypeFactoryTypeResolver->create((object) [
      'id' => 'camelids',
      'label' => 'Camelids',
      'description' => 'Camelids are large, strictly herbivorous animals with slender necks and long legs.',
      'source' => $data['source'],
    ]);
    self::assertEquals('camelids', $media_type->id());
    self::assertEquals('Camelids', $media_type->label());
    self::assertEquals('Camelids are large, strictly herbivorous animals with slender necks and long legs.', $media_type->getDescription());
    self::assertInstanceOf(MediaSourceInterface::class, $media_type->getSource());
    self::assertInstanceOf($expected_source_class, $media_type->getSource());

    $source_field = $media_type->getSource()->getSourceFieldDefinition($media_type);
    self::assertEquals($expected_source_field, $source_field->id());
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateTwice(): void {
    $this->testCreate(['source' => 'file'], File::class, 'media.camelids.field_media_file');
    $media_type_same = $this->mediaTypeFactoryTypeResolver->create((object) [
      'id' => 'camelids',
      'label' => 'Camelids 2',
      'source' => 'file',
    ]);
    self::assertEquals('camelids', $media_type_same->id());
    self::assertEquals('Camelids', $media_type_same->label());
  }

  /**
   * Prevent creating a Media Type Factory without source attribute.
   *
   * @covers ::create
   */
  public function testCreateNotExistingSource(): void {
    $this->expectException(PluginNotFoundException::class);
    if (method_exists($this, 'expectExceptionMessageMatches')) {
      $this->expectExceptionMessageMatches('#^The "foo" plugin does not exist\. Valid plugin IDs.+$#');
    }
    else {
      $this->expectExceptionMessageMatches('#^The "foo" plugin does not exist\. Valid plugin IDs.+$#');
    }
    $this->mediaTypeFactoryTypeResolver->create((object) [
      'source' => 'foo',
    ]);
  }

  /**
   * @covers ::create
   */
  public function testCreateRandomLabel(): void {
    $media_type = $this->mediaTypeFactoryTypeResolver->create((object) [
      'source' => 'file',
    ]);

    self::assertNotEmpty($media_type->label());
    self::assertSame(8, strlen($media_type->label()));
  }

  /**
   * @covers ::create
   */
  public function testCreateRandomId(): void {
    $media_type = $this->mediaTypeFactoryTypeResolver->create((object) [
      'source' => 'file',
    ]);

    self::assertNotEmpty($media_type->id());
    self::assertSame(8, strlen($media_type->id()));
  }

  /**
   * Data provider for ::testCreate.
   *
   * @return array
   *   Data provided.
   */
  public function providerMediaTypeValues(): array {
    return [
      'media File type' => [
        [
          'source' => 'file',
        ],
        'expected_source_class' => File::class,
        'expected_source_field' => 'media.camelids.field_media_file',
      ],
      'media Audio file type' => [
        [
          'source' => 'audio_file',
        ],
        'expected_source_class' => AudioFile::class,
        'expected_source_field' => 'media.camelids.field_media_audio_file',
      ],
      'media Image type' => [
        [
          'source' => 'image',
        ],
        'expected_source_class' => Image::class,
        'expected_source_field' => 'media.camelids.field_media_image',
      ],
      'media Video File type' => [
        [
          'source' => 'video_file',
        ],
        'expected_source_class' => VideoFile::class,
        'expected_source_field' => 'media.camelids.field_media_video_file',
      ],
      'media Oembed type' => [
        [
          'source' => 'oembed:video',
        ],
        'expected_source_class' => OEmbedInterface::class,
        'expected_source_field' => 'media.camelids.field_media_oembed_video',
      ],
    ];
  }

}
