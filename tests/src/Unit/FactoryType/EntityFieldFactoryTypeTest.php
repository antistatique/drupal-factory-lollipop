<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType
 *
 * @group factory_lollipop
 */
class EntityFieldFactoryTypeTest extends UnitTestCase {

  /**
   * The Entity Field Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType
   */
  protected $entityFieldFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->entityFieldFactoryTypeResolver = new EntityFieldFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->entityFieldFactoryTypeResolver->shouldApply('entity field'));
    self::assertFalse($this->entityFieldFactoryTypeResolver->shouldApply('node field'));
    self::assertFalse($this->entityFieldFactoryTypeResolver->shouldApply('taxonomy term field'));
    self::assertFalse($this->entityFieldFactoryTypeResolver->shouldApply('vocabulary field'));
    self::assertFalse($this->entityFieldFactoryTypeResolver->shouldApply('menu field'));
  }

  /**
   * Prevent creating an Entity Field without name attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryAttributeName(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The field name attribute is mandatory.');
    $this->entityFieldFactoryTypeResolver->create();
  }

  /**
   * Prevent creating an Entity Field without type attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryAttributeType(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The field type attribute is mandatory.');
    $this->entityFieldFactoryTypeResolver->create((object) ['name' => 'field_foo']);
  }

  /**
   * Prevent creating an Entity Field without type attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryAttributeBundle(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The bundle attribute is mandatory.');
    $this->entityFieldFactoryTypeResolver->create((object) [
      'name' => 'field_foo',
      'type' => 'foo',
    ]);
  }

  /**
   * Prevent creating an Entity Field without entity_type attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryAttributeEntityType(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The field entity type (entity_type) attribute is mandatory.');
    $this->entityFieldFactoryTypeResolver->create((object) [
      'name' => 'field_foo',
      'type' => 'foo',
      'bundle' => 'node',
    ]);
  }

}
