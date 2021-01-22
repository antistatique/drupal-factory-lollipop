<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;
use Drupal\user\Entity\Role;
use Drupal\user\RoleInterface;

/**
 * Example of Factory Lollipop usage for Role.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class RoleFactoryTest extends LollipopKernelTestBase {

  /**
   * The Factory Lollipop fixture factory.
   *
   * @var \Drupal\factory_lollipop\FixtureFactory
   */
  protected $factoryLollipop;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'user',
    'factory_lollipop_test',
  ];

  /**
   * Ensure a defined node type can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\RoleFactoryType::create
   */
  public function testDefine(): void {
    $this->factoryLollipop->loadDefinitions(['role']);

    // Ensure the association (node-type generation) is made only on ::create().
    $role = Role::load('architect');
    self::assertNull($role);

    /** @var \Drupal\user\RoleInterface $role */
    $role = $this->factoryLollipop->create('role_architect');
    self::assertInstanceOf(RoleInterface::class, $role);
    self::assertEquals('architect', $role->id());
    self::assertEquals(['administer themes'], $role->getPermissions());
  }

}
