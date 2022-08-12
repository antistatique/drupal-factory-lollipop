<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\factory_lollipop\FactoryType\NodeTypeFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\NodeTypeFactoryType
 *
 * @group factory_lollipop
 */
class NodeTypeFactoryTypeTest extends EntityKernelTestBase {

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
    $this->nodeTypeFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
  ];

  /**
   * @covers ::create
   */
  public function testCreate(): void {
    $node_type = $this->nodeTypeFactoryTypeResolver->create((object) [
      'type' => 'article',
      'name' => 'Article',
    ]);
    self::assertEquals('article', $node_type->id());
    self::assertEquals('Article', $node_type->label());
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateTwice(): void {
    $this->testCreate();
    $node_type_same = $this->nodeTypeFactoryTypeResolver->create((object) [
      'type' => 'article',
      'name' => 'Article 2',
    ]);
    self::assertEquals('article', $node_type_same->id());
    self::assertEquals('Article', $node_type_same->label());
  }

}
