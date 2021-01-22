<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\MenuFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\MenuFactoryType
 *
 * @group factory_lollipop
 */
class MenuFactoryTypeTest extends UnitTestCase {

  /**
   * The Menu Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\MenuFactoryType
   */
  protected $menuFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->menuFactoryTypeResolver = new MenuFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->menuFactoryTypeResolver->shouldApply('menu'));
    self::assertFalse($this->menuFactoryTypeResolver->shouldApply('users'));
    self::assertFalse($this->menuFactoryTypeResolver->shouldApply('Menu'));
    self::assertFalse($this->menuFactoryTypeResolver->shouldApply('phasellus'));
  }

}
