<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\factory_lollipop\FactoryType\UserFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\user\Entity\Role;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\UserFactoryType
 *
 * @group factory_lollipop
 */
class UserFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The User Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\UserFactoryType
   */
  protected $userFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->userFactoryTypeResolver = new UserFactoryType();
    $this->userFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
    $this->userFactoryTypeResolver->setPasswordGenerator($this->container->get('password_generator'));
  }

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'user',
  ];

  /**
   * @covers ::create
   */
  public function testCreate(): void {
    $user = $this->userFactoryTypeResolver->create((object) ['name' => 'John']);
    self::assertSame('1', $user->id());
    self::assertEquals('John', $user->getDisplayName());
    self::assertEquals(['authenticated'], $user->getRoles());
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateTwice(): void {
    $this->testCreate();
    $user = $this->userFactoryTypeResolver->create((object) [
      'uid' => 1,
      'name' => 'Jane',
    ]);
    self::assertSame('1', $user->id());
    self::assertEquals('John', $user->getDisplayName());
    self::assertEquals(['authenticated'], $user->getRoles());
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateAnonymous(): void {
    $this->testCreate();
    $user = $this->userFactoryTypeResolver->create((object) [
      'uid' => 0,
      'name' => 'Jane',
    ]);
    self::assertSame('0', $user->id());
    self::assertEquals('Jane', $user->getDisplayName());
    self::assertEquals(['anonymous'], $user->getRoles());
  }

  /**
   * @covers ::create
   */
  public function testCreateWithRoles(): void {
    $role1 = Role::create([
      'id' => 'test_role1',
      'label' => $this->randomString(),
    ]);
    $role1->save();

    $user = $this->userFactoryTypeResolver->create((object) [
      'name' => 'John',
      'roles' => ['test_role1'],
    ]);
    self::assertSame('1', $user->id());
    self::assertEquals('John', $user->getDisplayName());
    self::assertEquals(['authenticated', 'test_role1'], $user->getRoles());
  }

  /**
   * @covers ::create
   */
  public function testCreateWithNotExistingRoles(): void {
    $user = $this->userFactoryTypeResolver->create((object) [
      'name' => 'John',
      'roles' => ['foo'],
    ]);
    self::assertSame('1', $user->id());
    self::assertEquals('John', $user->getDisplayName());
    self::assertEquals(['authenticated', 'foo'], $user->getRoles());
  }

}
