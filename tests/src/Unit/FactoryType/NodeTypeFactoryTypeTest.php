<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\NodeTypeFactoryType;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\NodeTypeFactoryType
 *
 * @group factory_lollipop
 */
#[CoversClass(\Drupal\factory_lollipop\FactoryType\NodeTypeFactoryType::class)]
#[CoversMethod(\Drupal\factory_lollipop\FactoryType\NodeTypeFactoryType::class, 'shouldApply')]
#[Group('factory_lollipop')]
class NodeTypeFactoryTypeTest extends UnitTestCase {

  /**
   * The Node Type Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\NodeTypeFactoryType
   */
  protected $nodeTypeFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->nodeTypeFactoryTypeResolver = new NodeTypeFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->nodeTypeFactoryTypeResolver->shouldApply('node type'));
    self::assertFalse($this->nodeTypeFactoryTypeResolver->shouldApply('node_type'));
    self::assertFalse($this->nodeTypeFactoryTypeResolver->shouldApply('Node type'));
    self::assertFalse($this->nodeTypeFactoryTypeResolver->shouldApply('phasellus'));
  }

}
