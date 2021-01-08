<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\NodeFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\NodeFactoryType
 *
 * @group factory_lollipop
 */
class NodeFactoryTypeTest extends UnitTestCase {

  /**
   * The Node Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\NodeFactoryType
   */
  protected $nodeFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->nodeFactoryTypeResolver = new NodeFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->nodeFactoryTypeResolver->shouldApply('node'));
    self::assertFalse($this->nodeFactoryTypeResolver->shouldApply('Node'));
    self::assertFalse($this->nodeFactoryTypeResolver->shouldApply('phasellus'));
  }

  /**
   * Prevent creating a Node Factory without type attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryTypeAttribute(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The type attribute is mandatory.');
    $this->nodeFactoryTypeResolver->create();
  }

}
