<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\factory_lollipop\FactoryType\RoleFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\RoleFactoryType
 *
 * @group factory_lollipop
 */
class RoleFactoryTypeTest extends EntityKernelTestBase {

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
    $this->roleFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'user',
  ];

  /**
   * @covers ::create
   */
  public function testCreate(): void {
    $role = $this->roleFactoryTypeResolver->create((object) [
      'rid' => 'architect',
      'name' => 'Architect 1',
      'weight' => 2,
    ]);
    self::assertEquals('architect', $role->id());
    self::assertEquals('Architect 1', $role->label());
    self::assertEquals(2, $role->getWeight());
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateTwice(): void {
    $this->testCreate();
    $role_same = $this->roleFactoryTypeResolver->create((object) [
      'rid' => 'architect',
      'name' => 'Architect 2',
    ]);
    self::assertEquals('architect', $role_same->id());
    self::assertEquals('Architect 1', $role_same->label());
  }

  /**
   * @covers ::create
   */
  public function testCreateWithPermissions(): void {
    $role = $this->roleFactoryTypeResolver->create((object) [
      'rid' => 'architect',
      'permissions' => ['administer themes'],
    ]);
    self::assertEquals('architect', $role->id());
    self::assertEquals(['administer themes'], $role->getPermissions());
  }

}
