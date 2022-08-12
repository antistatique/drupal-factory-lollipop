<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;
use Drupal\system\Entity\Menu;
use Drupal\system\MenuInterface;

/**
 * Example of Factory Lollipop usage for Menu.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class MenuFactoryTest extends LollipopKernelTestBase {

  /**
   * The Factory Lollipop fixture factory.
   *
   * @var \Drupal\factory_lollipop\FixtureFactory
   */
  protected $factoryLollipop;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'factory_lollipop_test',
  ];

  /**
   * Ensure a defined Menu can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\MenuFactoryType::create
   */
  public function testDefine(): void {
    $this->factoryLollipop->loadDefinitions(['menu']);

    // Ensure the menu does not exist on ::loadDefinitions().
    $menu = Menu::Load('main');
    self::assertNull($menu);

    /** @var \Drupal\system\MenuInterface $menu */
    $menu = $this->factoryLollipop->create('menu_main');
    self::assertInstanceOf(MenuInterface::class, $menu);
    self::assertSame('main', $menu->id());

    // Ensure the menu footer does not exists when not called.
    $menu = Menu::Load('footer');
    self::assertNull($menu);
  }

}
