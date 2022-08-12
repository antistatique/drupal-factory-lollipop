<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\node\NodeInterface;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Node with fields.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class NodeFieldFactoryTest extends LollipopKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'factory_lollipop_test',
  ];

  /**
   * Ensure defined Node-Field associated to Node can be created with fields..
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType::create
   */
  public function testCreateNodeWithFields(): void {
    $this->factoryLollipop->loadDefinitions(['node_page']);

    $node = $this->factoryLollipop->create('node_page', ['title' => 'Magna cursus tempor']);

    // Assert the node is created.
    self::assertInstanceOf(NodeInterface::class, $node);
    self::assertEquals('page', $node->bundle());
    self::assertEquals('Magna cursus tempor', $node->getTitle());

    // Assert a field w/o default values is then empty by default.
    self::assertTrue($node->hasField('field_foo'));
    self::assertTrue($node->get('field_foo')->isEmpty());

    // Assert a field with default values is filled by default.
    self::assertTrue($node->hasField('field_bar'));
    self::assertFalse($node->get('field_bar')->isEmpty());
    self::assertSame('Viverra iaculis', $node->get('field_bar')->value);
  }

  /**
   * Ensure defined Node Field values can be overridden.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType::create
   */
  public function testCreateNodeWithFieldsValues(): void {
    $this->factoryLollipop->loadDefinitions(['node_page']);

    $node = $this->factoryLollipop->create('node_page', [
      'title' => 'Tortor posuere ornare quisque mi vehicula nostra',
      'field_foo' => 'foo@bar.com',
    ]);

    self::assertInstanceOf(NodeInterface::class, $node);
    self::assertEquals('page', $node->bundle());
    self::assertEquals('Tortor posuere ornare quisque mi vehicula nostra', $node->getTitle());
    self::assertTrue($node->hasField('field_foo'));
    self::assertFalse($node->get('field_foo')->isEmpty());
    self::assertSame('foo@bar.com', $node->get('field_foo')->value);
  }

}
