<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\TaxonomyTermFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\TaxonomyTermFactoryType
 *
 * @group factory_lollipop
 */
class TaxonomyTermFactoryTypeTest extends UnitTestCase {

  /**
   * The Taxonomy Term Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\TaxonomyTermFactoryType
   */
  protected $taxonomyTermFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->taxonomyTermFactoryTypeResolver = new TaxonomyTermFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->taxonomyTermFactoryTypeResolver->shouldApply('taxonomy term'));
    self::assertFalse($this->taxonomyTermFactoryTypeResolver->shouldApply('Taxonomy term'));
    self::assertFalse($this->taxonomyTermFactoryTypeResolver->shouldApply('TaxonomyTerm'));
    self::assertFalse($this->taxonomyTermFactoryTypeResolver->shouldApply('Taxonomy'));
    self::assertFalse($this->taxonomyTermFactoryTypeResolver->shouldApply('taxonomy'));
    self::assertFalse($this->taxonomyTermFactoryTypeResolver->shouldApply('Term'));
    self::assertFalse($this->taxonomyTermFactoryTypeResolver->shouldApply('term'));
    self::assertFalse($this->taxonomyTermFactoryTypeResolver->shouldApply('phasellus'));
  }

  /**
   * Prevent creating a Taxonomy Term Factory without vid attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryVidAttribute(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The vid attribute is mandatory.');
    $this->taxonomyTermFactoryTypeResolver->create();
  }

}
