<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\Core\Language\LanguageInterface;
use Drupal\media\MediaInterface;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Media.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class MediaFactoryTest extends LollipopKernelTestBase {

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
   * Ensure defined Media associated to Media-Type can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\MediaFactoryType::create
   */
  public function testCreateWithAssociatedDefinition(): void {
    $this->factoryLollipop->loadDefinitions(['media_image']);

    $media = $this->factoryLollipop->create('media_image');

    self::assertInstanceOf(MediaInterface::class, $media);
    self::assertEquals('media_image', $media->bundle());
    self::assertEquals('Enim lectus orci faucibus suscipit', $media->getName());
    self::assertTrue($media->isPublished());
    self::assertEquals('und', $media->getTranslationLanguages()[LanguageInterface::LANGCODE_NOT_SPECIFIED]->getId());

    $media = $this->factoryLollipop->create('media_image', [
      'name' => 'Massa adipiscing ornare gravida ut ullamcorper rhoncus nisl',
      'langcode' => 'en',
      'status' => FALSE,
    ]);
    self::assertInstanceOf(MediaInterface::class, $media);
    self::assertEquals('media_image', $media->bundle());
    self::assertEquals('Massa adipiscing ornare gravida ut ullamcorper rhoncus nisl', $media->getName());
    self::assertFalse($media->isPublished());
    self::assertEquals('en', $media->getTranslationLanguages()['en']->getId());
  }

}
