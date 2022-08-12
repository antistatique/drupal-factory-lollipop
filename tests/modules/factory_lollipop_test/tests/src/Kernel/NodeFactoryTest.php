<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\node\NodeInterface;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Node.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class NodeFactoryTest extends LollipopKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'factory_lollipop_test',
  ];

  /**
   * Ensure defined Node associated to Node-Type can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\NodeFactoryType::create
   */
  public function testCreateWithAssociatedDefinition(): void {
    $this->factoryLollipop->loadDefinitions(['node_article']);

    $node = $this->factoryLollipop->create('node_article', ['title' => 'Magna cursus tempor']);

    self::assertInstanceOf(NodeInterface::class, $node);
    self::assertEquals('article', $node->bundle());
    self::assertEquals('Magna cursus tempor', $node->getTitle());
    self::assertTrue($node->isPublished());
  }

  /**
   * Ensure the NodeFactory is overridable.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\NodeFactoryType::create
   */
  public function testDefineOverride() {
    $this->factoryLollipop->loadDefinitions(['node_article']);

    // Override the definition of node_article.
    $this->factoryLollipop->define('node', 'node_article', [
      'type' => $this->factoryLollipop->association('node_type_article'),
      'status' => FALSE,
    ]);

    $node = $this->factoryLollipop->create('node_article', []);
    self::assertInstanceOf(NodeInterface::class, $node);
    self::assertEquals('article', $node->bundle());
    self::assertNotEmpty($node->getTitle());
    self::assertFalse($node->isPublished());
  }

}
