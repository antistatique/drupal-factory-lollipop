<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\MediaTypeFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\MediaTypeFactoryType
 *
 * @group factory_lollipop
 */
class MediaTypeFactoryTypeTest extends UnitTestCase {

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
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->mediaTypeFactoryTypeResolver->shouldApply('media type'));
    self::assertFalse($this->mediaTypeFactoryTypeResolver->shouldApply('media_type'));
    self::assertFalse($this->mediaTypeFactoryTypeResolver->shouldApply('Media type'));
    self::assertFalse($this->mediaTypeFactoryTypeResolver->shouldApply('phasellus'));
  }

  /**
   * Prevent creating a Media Type without media source plugin ID attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryMenuNameType(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The media source plugin ID (source) attribute is mandatory.');
    $this->mediaTypeFactoryTypeResolver->create((object) []);
  }

}
