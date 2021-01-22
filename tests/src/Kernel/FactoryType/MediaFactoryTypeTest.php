<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\Core\Language\LanguageInterface;
use Drupal\factory_lollipop\FactoryType\MediaFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\media\Entity\MediaType;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\MediaFactoryType
 *
 * @group factory_lollipop
 */
class MediaFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The Media Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\MediaFactoryType
   */
  protected $mediaFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('file');
    $this->installSchema('file', 'file_usage');
    $this->installEntitySchema('media');

    // Create a Media Type for testing.
    $media_type = MediaType::create(['id' => 'media_file', 'source' => 'file']);
    $media_type->save();

    // Create the source field.
    $source_field = $media_type->getSource()->createSourceField($media_type);
    $source_field->getFieldStorageDefinition()->save();
    $source_field->save();
    $media_type
      ->set('source_configuration', [
        'source_field' => $source_field->getName(),
      ])
      ->save();

    $this->mediaFactoryTypeResolver = new MediaFactoryType();
    $this->mediaFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'media',
    'file',
    'image',
  ];

  /**
   * @covers ::create
   *
   * @dataProvider providerMediaValues
   */
  public function testCreate(array $data): void {
    /** @var \Drupal\media\Entity\Media $media */
    $media = $this->mediaFactoryTypeResolver->create((object) $data);

    self::assertEquals('media_file', $media->bundle());
    self::assertEquals($data['name'], $media->getName());
    self::assertEquals($data['status'], $media->isPublished());
    self::assertEquals('und', $media->getTranslationLanguages()[LanguageInterface::LANGCODE_NOT_SPECIFIED]->getId());
  }

  /**
   * Prevent creating a Media Factory without bundle attribute.
   *
   * @covers ::create
   */
  public function testCreateMediaTypeMustExists(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The bundle attribute must be an existing media type.');
    $this->mediaFactoryTypeResolver->create((object) ['bundle' => 'foo']);
  }

  /**
   * Data provider for ::testCreate.
   *
   * @return array
   *   Data provided.
   */
  public function providerMediaValues(): array {
    return [
      'published media file' => [
        [
          'bundle' => 'media_file',
          'name' => 'Fusce placerat pulvinar',
          'status' => TRUE,
        ],
      ],
      'unpublished media file' => [
        [
          'bundle' => 'media_file',
          'name' => 'Interdum',
          'status' => FALSE,
        ],
      ],
    ];
  }

}
