<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\UserFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\UserFactoryType
 *
 * @group factory_lollipop
 */
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
