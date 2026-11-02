<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\MenuFactoryType;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\MenuFactoryType
 *
 * @group factory_lollipop
 */
#[CoversClass(\Drupal\factory_lollipop\FactoryType\MenuFactoryType::class)]
#[CoversMethod(\Drupal\factory_lollipop\FactoryType\MenuFactoryType::class, 'shouldApply')]
#[Group('factory_lollipop')]
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
