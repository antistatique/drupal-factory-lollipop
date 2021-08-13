<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;
use Drupal\user\UserInterface;

/**
 * Example of Factory Lollipop usage for User.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class UserFactoryTest extends LollipopKernelTestBase {

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
    'user',
    'factory_lollipop_test',
  ];

  /**
   * Ensure a defined node type can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\UserFactoryType::create
   */
  public function testDefine(): void {
    $this->factoryLollipop->loadDefinitions(['user']);

    $role = Role::create([
      'id' => 'moderator',
      'permissions' => ['administer comments'],
    ]);
    $role->save();

    // Ensure the association (node-type generation) is made only on ::create().
    $role = User::load(0);
    self::assertNull($role);

    // Ensure the 1st created user has the moderator role with permission.
    /** @var \Drupal\user\UserInterface $user */
    $user = $this->factoryLollipop->create('user_moderator');
    self::assertInstanceOf(UserInterface::class, $user);
    self::assertSame('1', $user->id());
    self::assertEquals(['authenticated', 'moderator'], $user->getRoles());
    self::assertTrue($user->hasPermission('administer comments'));

    // Ensure the 2nd created user has the administrator custom role.
    /** @var \Drupal\user\UserInterface $user */
    $user = $this->factoryLollipop->create('user_admin');
    self::assertInstanceOf(UserInterface::class, $user);
    self::assertSame('2', $user->id());
    self::assertEquals(['authenticated', 'administrator'], $user->getRoles());
    self::assertFalse($user->hasPermission('administer comments'));

    // Ensure the 3rd created user is just authenticated without permissions.
    /** @var \Drupal\user\UserInterface $user */
    $user = $this->factoryLollipop->create('user');
    self::assertInstanceOf(UserInterface::class, $user);
    self::assertSame('3', $user->id());
    self::assertEquals(['authenticated'], $user->getRoles());
    self::assertFalse($user->hasPermission('administer comments'));

    // Ensure Anonymous user has not permission.
    /** @var \Drupal\user\UserInterface $user */
    $user = $this->factoryLollipop->create('user', ['uid' => 0]);
    self::assertInstanceOf(UserInterface::class, $user);
    self::assertSame('0', $user->id());
    self::assertEquals(['anonymous'], $user->getRoles());
    self::assertFalse($user->hasPermission('administer comments'));
  }

}
