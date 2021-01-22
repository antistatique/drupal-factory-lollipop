<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\MediaFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\MediaFactoryType
 *
 * @group factory_lollipop
 */
class MediaFactoryTypeTest extends UnitTestCase {

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
    $this->mediaFactoryTypeResolver = new MediaFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->mediaFactoryTypeResolver->shouldApply('media'));
    self::assertFalse($this->mediaFactoryTypeResolver->shouldApply('Media'));
    self::assertFalse($this->mediaFactoryTypeResolver->shouldApply('phasellus'));
  }

  /**
   * Prevent creating a Media Factory without bundle attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryTypeAttribute(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The bundle attribute is mandatory.');
    $this->mediaFactoryTypeResolver->create();
  }

}
