<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\node\Entity\NodeType;
use Drupal\node\NodeTypeInterface;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Node Type.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class NodeTypeFactoryTest extends LollipopKernelTestBase {

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
    'node',
    'factory_lollipop_test',
  ];

  /**
   * Ensure a defined node type can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\NodeTypeFactoryType::create
   */
  public function testDefine(): void {
    $this->factoryLollipop->loadDefinitions(['node_type_article']);

    // Ensure the association (node-type generation) is made only on ::create().
    $node_type = NodeType::load('article');
    self::assertNull($node_type);

    /** @var \Drupal\node\NodeTypeInterface $node_type */
    $node_type = $this->factoryLollipop->create('node_type_article');
    self::assertInstanceOf(NodeTypeInterface::class, $node_type);
    self::assertEquals('article', $node_type->id());
  }

}
