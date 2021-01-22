<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\RoleFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\RoleFactoryType
 *
 * @group factory_lollipop
 */
class RoleFactoryTypeTest extends UnitTestCase {

  /**
   * The Role Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\RoleFactoryType
   */
  protected $roleFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->roleFactoryTypeResolver = new RoleFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->roleFactoryTypeResolver->shouldApply('role'));
    self::assertFalse($this->roleFactoryTypeResolver->shouldApply('user role'));
    self::assertFalse($this->roleFactoryTypeResolver->shouldApply('Role'));
    self::assertFalse($this->roleFactoryTypeResolver->shouldApply('phasellus'));
  }

}
