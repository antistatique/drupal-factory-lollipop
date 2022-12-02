<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\Core\Url;
use Drupal\factory_lollipop\FactoryType\MenuLinkFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\system\Entity\Menu;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\MenuLinkFactoryType
 *
 * @group factory_lollipop
 */
class MenuLinkFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The Menu Link Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\MenuLinkFactoryType
   */
  protected $menuLinkFactoryTypeResolver;

  /**
   * The menu link plugin manager.
   *
   * @var \Drupal\Core\Menu\MenuLinkManagerInterface
   */
  protected $menuLinkManager;


  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'link',
    'menu_link_content',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('menu_link_content');

    $this->menuLinkManager = \Drupal::service('plugin.manager.menu.link');

    // Create a menu for testing.
    Menu::create([
      'id' => 'menu_test',
      'label' => 'Test menu',
      'description' => 'Description text',
    ])->save();

    $this->menuLinkFactoryTypeResolver = new MenuLinkFactoryType();
    $this->menuLinkFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * Prevent creating a Menu Link Factory on not existing Menu.
   *
   * @covers ::create
   */
  public function testCreateMenuMustExists(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The menu_name attribute must be an existing menu.');
    $this->menuLinkFactoryTypeResolver->create((object) ['menu_name' => 'foo']);
  }

  /**
   * @covers ::create
   */
  public function testCreate(): void {
    $link = $this->menuLinkFactoryTypeResolver->create((object) ([
      'title' => 'Menu link test',
      'provider' => 'menu_test',
      'menu_name' => 'menu_test',
      'bundle' => 'menu_link_content',
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent'],
    ]));

    self::assertSame('Menu link test', $link->label());
    self::assertSame('Menu link test', $link->getTitle());
    self::assertInstanceOf(Url::class, $link->toUrl());
    self::assertSame('route:entity.menu_link_content.canonical;menu_link_content=1', $link->toUrl()->toUriString());
    self::assertSame('/admin/structure/menu/item/1/edit', $link->toUrl()->toString());
    self::assertSame('menu_test', $link->getMenuName());
    self::assertStringStartsWith('menu_link_content:', $link->getPluginId());
  }

  /**
   * @covers ::create
   *
   * @see \Drupal\Tests\menu_link_content\Kernel\MenuLinksTest::createLinkHierarchy
   */
  public function testCreateLinkHierarchy(): void {
    // Common options to all test links.
    $base_options = [
      'title' => 'Menu link test',
      'provider' => 'menu_test',
      'menu_name' => 'menu_test',
      'bundle' => 'menu_link_content',
    ];

    // Then create a simple link hierarchy:
    // - parent
    //   - child-1
    //     - child-1-1
    //       - child-1-2
    //   - child-2.
    $links = [];

    // Create parent (root).
    $link = $this->menuLinkFactoryTypeResolver->create((object) ($base_options + [
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent'],
    ]));
    $links['parent'] = $link->getPluginId();

    // Create child-1.
    $link = $this->menuLinkFactoryTypeResolver->create((object) ($base_options + [
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent/child'],
      'parent' => $links['parent'],
    ]));
    $links['child-1'] = $link->getPluginId();

    // Create child-1-1.
    $link = $this->menuLinkFactoryTypeResolver->create((object) ($base_options + [
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent/child2/child'],
      'parent' => $links['child-1'],
    ]));
    $links['child-1-1'] = $link->getPluginId();

    // Create child-1-2.
    $link = $this->menuLinkFactoryTypeResolver->create((object) ($base_options + [
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent/child2/child'],
      'parent' => $links['child-1'],
    ]));
    $links['child-1-2'] = $link->getPluginId();

    // Create child-2.
    $link = $this->menuLinkFactoryTypeResolver->create((object) ($base_options + [
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent/child'],
      'parent' => $links['parent'],
    ]));
    $links['child-2'] = $link->getPluginId();

    $expected_hierarchy = [
      'parent' => '',
      'child-1' => 'parent',
      'child-1-1' => 'child-1',
      'child-1-2' => 'child-1',
      'child-2' => 'parent',
    ];
    $this->assertMenuLinkParents($links, $expected_hierarchy);
  }

  /**
   * Assert that at set of links is properly parented.
   *
   * @param string[] $links
   *   Flat hierarchy of menu link content plugin ID.
   * @param string[] $expected_hierarchy
   *   Flatt list of link with direct expected parent.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   *
   * @see \Drupal\Tests\menu_link_content\Kernel\MenuLinksTest::assertMenuLinkParents
   */
  private function assertMenuLinkParents(array $links, array $expected_hierarchy): void {
    foreach ($expected_hierarchy as $id => $parent) {
      /** @var \Drupal\Core\Menu\MenuLinkInterface $menu_link_plugin  */
      $menu_link_plugin = $this->menuLinkManager->createInstance($links[$id]);
      $expected_parent = $links[$parent] ?? '';

      $this->assertEquals($menu_link_plugin->getParent(), $expected_parent, sprintf('Menu link %s has parent of %s, expected %s.', $id, $menu_link_plugin->getParent(), $expected_parent));
    }
  }

}
