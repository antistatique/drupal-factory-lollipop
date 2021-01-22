<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\MenuLinkFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\MenuLinkFactoryType
 *
 * @group factory_lollipop
 */
class MenuLinkFactoryTypeTest extends UnitTestCase {

  /**
   * The Menu Link Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\MenuLinkFactoryType
   */
  protected $menuLinkFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->menuLinkFactoryTypeResolver = new MenuLinkFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->menuLinkFactoryTypeResolver->shouldApply('menu link'));
    self::assertFalse($this->menuLinkFactoryTypeResolver->shouldApply('links'));
    self::assertFalse($this->menuLinkFactoryTypeResolver->shouldApply('menu links'));
    self::assertFalse($this->menuLinkFactoryTypeResolver->shouldApply('menu_link'));
    self::assertFalse($this->menuLinkFactoryTypeResolver->shouldApply('Menu Links'));
    self::assertFalse($this->menuLinkFactoryTypeResolver->shouldApply('phasellus'));
  }

  /**
   * Prevent creating a Menu Content Link without Menu-Name attribute.
   *
   * @covers ::create
   */
  public function testCreateMandatoryMenuNameType(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The menu_name attribute is mandatory.');
    $this->menuLinkFactoryTypeResolver->create((object) []);
  }

}
