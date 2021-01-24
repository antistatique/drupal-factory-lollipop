<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType
 *
 * @group factory_lollipop
 */
class EntityFieldEntityReferenceFactoryTypeTest extends UnitTestCase {

  /**
   * The Entity Field Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType
   */
  protected $entityFieldFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->entityFieldFactoryTypeResolver = new EntityFieldEntityReferenceFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->entityFieldFactoryTypeResolver->shouldApply('entity reference field'));
    self::assertFalse($this->entityFieldFactoryTypeResolver->shouldApply('node reference field'));
    self::assertFalse($this->entityFieldFactoryTypeResolver->shouldApply('taxonomy term reference field'));
    self::assertFalse($this->entityFieldFactoryTypeResolver->shouldApply('vocabulary reference field'));
    self::assertFalse($this->entityFieldFactoryTypeResolver->shouldApply('menu reference field'));
  }

  /**
   * Prevent creating a Node Field without name attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryAttributeName(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The field name attribute is mandatory.');
    $this->entityFieldFactoryTypeResolver->create();
  }

  /**
   * Prevent creating a Node Field without type attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryAttributeNodeType(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The bundle attribute is mandatory.');
    $this->entityFieldFactoryTypeResolver->create((object) [
      'name' => 'field_foo',
    ]);
  }

  /**
   * Prevent creating a Node Field without target entity type attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryAttributeTargetEntityType(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The target type (target_entity_type) attribute is mandatory.');
    $this->entityFieldFactoryTypeResolver->create((object) [
      'name' => 'field_foo',
      'bundle' => 'article',
      'entity_type' => 'node',
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
      'target_entity_type' => 'node',
      'bundle' => 'article',
    ]);
  }

}
