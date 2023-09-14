<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\Core\Url;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\menu_link_content\MenuLinkContentInterface;
use Drupal\system\Entity\Menu;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Menu Link.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class MenuLinkFactoryTest extends LollipopKernelTestBase {

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

    // Create a menu for testing.
    Menu::create([
      'id' => 'menu_test',
      'label' => 'Test menu',
      'description' => 'Description text',
    ])->save();
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
    $this->factoryLollipop->loadDefinitions(['menu_link']);

    // Ensure the Menu Link does not exist on ::loadDefinitions().
    $link = MenuLinkContent::load(1);
    self::assertNull($link);

    /** @var \Drupal\menu_link_content\MenuLinkContentInterface $link */
    $link = $this->factoryLollipop->create('menu_link_parent');
    self::assertInstanceOf(MenuLinkContentInterface::class, $link);
    self::assertSame('1', $link->id());

    self::assertSame('parent', $link->label());
    self::assertSame('parent', $link->getTitle());
    self::assertInstanceOf(Url::class, $link->toUrl());
    self::assertSame('route:entity.menu_link_content.canonical;menu_link_content=1', $link->toUrl()->toUriString());
    self::assertSame('/admin/structure/menu/item/1/edit', $link->toUrl()->toString());
    self::assertSame('menu_test', $link->getMenuName());
    self::assertStringStartsWith('menu_link_content:', $link->getPluginId());
  }

}
