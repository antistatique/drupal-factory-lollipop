<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\entity_test\Entity\EntityTest;
use Drupal\node\NodeInterface;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for Node with entity reference fields.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class NodeFieldEntityReferenceFactoryTest extends LollipopKernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'node',
    'factory_lollipop_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create an entity test.
    $entity_test = EntityTest::create([
      'type' => 'entity_test',
    ]);
    $entity_test->save();
  }

  /**
   * Ensure defined Node Field Entity Reference can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType::create
   */
  public function testCreateNodeWithFields(): void {
    $this->factoryLollipop->loadDefinitions(['node_news']);

    $node = $this->factoryLollipop->create('node_news', ['title' => 'Magna cursus tempor']);

    // Assert the node is created.
    self::assertInstanceOf(NodeInterface::class, $node);
    self::assertEquals('news', $node->bundle());
    self::assertEquals('Magna cursus tempor', $node->getTitle());

    // Assert a field w/o default values is then empty by default.
    self::assertTrue($node->hasField('field_foo_entity_test'));
    self::assertTrue($node->get('field_foo_entity_test')->isEmpty());

    // Assert a field with default values is filled by default.
    self::assertTrue($node->hasField('field_bar_entity_test'));
    self::assertFalse($node->get('field_bar_entity_test')->isEmpty());
    self::assertSame(1, $node->get('field_bar_entity_test')->target_id);
    self::assertSame('entity_test', $node->get('field_bar_entity_test')->entity->bundle());
  }

  /**
   * Ensure defined Node Field Entity Reference values can be overridden.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType::create
   */
  public function testCreateNodeWithFieldsValues(): void {
    $this->factoryLollipop->loadDefinitions(['node_news']);

    $node = $this->factoryLollipop->create('node_news', [
      'title' => 'Tortor posuere ornare quisque mi vehicula nostra',
      'field_foo_entity_test' => 1,
    ]);

    self::assertInstanceOf(NodeInterface::class, $node);
    self::assertEquals('news', $node->bundle());
    self::assertEquals('Tortor posuere ornare quisque mi vehicula nostra', $node->getTitle());
    self::assertTrue($node->hasField('field_foo_entity_test'));
    self::assertFalse($node->get('field_foo_entity_test')->isEmpty());
    self::assertSame(1, $node->get('field_foo_entity_test')->target_id);
    self::assertSame('entity_test', $node->get('field_foo_entity_test')->entity->bundle());
  }

}
