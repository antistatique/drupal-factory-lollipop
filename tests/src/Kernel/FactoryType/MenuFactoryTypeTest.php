<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\factory_lollipop\FactoryType\MenuFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\MenuFactoryType
 *
 * @group factory_lollipop
 */
#[CoversClass(\Drupal\factory_lollipop\FactoryType\MenuFactoryType::class)]
#[CoversMethod(\Drupal\factory_lollipop\FactoryType\MenuFactoryType::class, 'create')]
#[Group('factory_lollipop')]
#[RunTestsInSeparateProcesses]
class MenuFactoryTypeTest extends EntityKernelTestBase {

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
    $this->menuFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * Prevent creating a Menu Factory without ID attribute.
   *
   * @covers ::create
   */
  public function testCreateMenuMustExists(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The entity does not have an ID.');
    $this->menuFactoryTypeResolver->create((object) ['label' => 'Main Menu']);
  }

  /**
   * @covers ::create
   */
  public function testCreate(): void {
    $menu = $this->menuFactoryTypeResolver->create((object) [
      'id' => 'main_menu',
      'label' => 'Main Menu',
    ]);
    self::assertSame('main_menu', $menu->id());
    self::assertEquals('Main Menu', $menu->label());
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateTwice(): void {
    $this->testCreate();
    $menu = $this->menuFactoryTypeResolver->create((object) [
      'id' => 'main_menu',
      'name' => 'Footer Menu',
    ]);
    self::assertSame('main_menu', $menu->id());
    self::assertEquals('Main Menu', $menu->label());
  }

}
