<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\UserFactoryType;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\UserFactoryType
 *
 * @group factory_lollipop
 */
#[CoversClass(\Drupal\factory_lollipop\FactoryType\UserFactoryType::class)]
#[CoversMethod(\Drupal\factory_lollipop\FactoryType\UserFactoryType::class, 'shouldApply')]
#[Group('factory_lollipop')]
class UserFactoryTypeTest extends UnitTestCase {

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
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->userFactoryTypeResolver->shouldApply('user'));
    self::assertFalse($this->userFactoryTypeResolver->shouldApply('users'));
    self::assertFalse($this->userFactoryTypeResolver->shouldApply('User'));
    self::assertFalse($this->userFactoryTypeResolver->shouldApply('phasellus'));
  }

}
