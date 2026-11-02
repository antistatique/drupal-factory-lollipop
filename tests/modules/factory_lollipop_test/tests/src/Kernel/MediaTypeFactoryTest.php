<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\media\Entity\MediaType;
use Drupal\media\MediaTypeInterface;
use Drupal\media\Plugin\media\Source\Image;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Example of Factory Lollipop usage for Media Type.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
#[CoversMethod(\Drupal\factory_lollipop\FixtureFactory::class, 'loadDefinitions')]
#[CoversMethod(\Drupal\factory_lollipop\FixtureFactory::class, 'define')]
#[CoversMethod(\Drupal\factory_lollipop\FixtureFactory::class, 'create')]
#[CoversMethod(\Drupal\factory_lollipop\FactoryType\MediaTypeFactoryType::class, 'create')]
#[Group('factory_lollipop')]
#[Group('factory_lollipop_example')]
#[RunTestsInSeparateProcesses]
class MediaTypeFactoryTest extends LollipopKernelTestBase {

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
   * Ensure a defined Media Type can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\MediaTypeFactoryType::create
   */
  public function testDefine(): void {
    $this->factoryLollipop->loadDefinitions(['media_type_image']);

    // Ensure the association (Media Type generation) is made on ::create().
    $media_type = MediaType::load('media_image');
    self::assertNull($media_type);

    /** @var \Drupal\media\MediaTypeInterface $media_type */
    $media_type = $this->factoryLollipop->create('media_type_image');
    self::assertInstanceOf(MediaTypeInterface::class, $media_type);
    self::assertEquals('media_image', $media_type->id());
    self::assertInstanceOf(Image::class, $media_type->getSource());

    // Media Type has been created with proper id.
    $media_type = MediaType::load('media_image');
    self::assertNotNull($media_type);
  }

}
