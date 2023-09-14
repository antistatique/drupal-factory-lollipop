<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\menu_link_content\MenuLinkContentInterface;
use Drupal\system\Entity\Menu;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Menu Link Hierarchy.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class MenuLinksFactoryTest extends LollipopKernelTestBase {

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
    'link',
    'menu_link_content',
    'factory_lollipop_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('menu_link_content');
  }

  /**
   * Ensure a defined Menu Link can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\MenuLinkFactoryType::create
   */
  public function testDefine(): void {
    $this->factoryLollipop->loadDefinitions(['menu_main']);

    // Ensure the menu does not exist on ::loadDefinitions().
    $menu = Menu::Load('main');
    self::assertNull($menu);

    /** @var \Drupal\system\MenuInterface $link_parent */
    $link_parent = $this->factoryLollipop->create('menu_main_link_parent');

    /** @var \Drupal\system\MenuInterface $link_parent */
    $link_child_1 = $this->factoryLollipop->create('menu_main_link_child_1');

    /** @var \Drupal\system\MenuInterface $link_parent */
    $link_child_1_1 = $this->factoryLollipop->create('menu_main_link_child_1_1');
    /** @var \Drupal\system\MenuInterface $link_parent */
    $link_child_2 = $this->factoryLollipop->create('menu_main_link_child_2');

    self::assertInstanceOf(MenuLinkContentInterface::class, $link_parent);
    self::assertInstanceOf(MenuLinkContentInterface::class, $link_child_1);
    self::assertInstanceOf(MenuLinkContentInterface::class, $link_child_1_1);
    self::assertInstanceOf(MenuLinkContentInterface::class, $link_child_2);
  }

}
