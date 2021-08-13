<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\Core\Language\LanguageInterface;
use Drupal\factory_lollipop\FactoryType\NodeFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\NodeType;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\NodeFactoryType
 *
 * @group factory_lollipop
 */
class NodeFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The Node Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\NodeFactoryType
   */
  protected $nodeFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->nodeFactoryTypeResolver = new NodeFactoryType();
    $this->nodeFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
  ];

  /**
   * @covers ::create
   *
   * @dataProvider providerNodeValues
   */
  public function testCreate($data): void {
    // Create a node type for testing.
    $node_type = NodeType::create(['type' => 'article', 'name' => 'Article']);
    $node_type->save();

    $node = $this->nodeFactoryTypeResolver->create((object) $data);

    self::assertEquals('article', $node->bundle());
    self::assertEquals($data['title'], $node->getTitle());
    self::assertEquals($data['status'], $node->isPublished());
    self::assertEquals('und', $node->getTranslationLanguages()[LanguageInterface::LANGCODE_NOT_SPECIFIED]->getId());
  }

  /**
   * Prevent creating a Node Factory without type attribute.
   *
   * @covers ::create
   */
  public function testCreateNodeTypeMustExists(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The type attribute must be an existing node type.');
    $this->nodeFactoryTypeResolver->create((object) [
      'type' => 'article',
    ]);
  }

  /**
   * @covers ::create
   */
  public function testCreateRandomTitle(): void {
    // Create a node type for testing.
    $node_type = NodeType::create(['type' => 'article', 'name' => 'Article']);
    $node_type->save();

    $node = $this->nodeFactoryTypeResolver->create((object) ['type' => 'article']);

    self::assertNotEmpty($node->getTitle());
    self::assertSame(8, strlen($node->getTitle()));
  }

  /**
   * Data provider for ::testCreate.
   *
   * @return array
   *   Data provided.
   */
  public function providerNodeValues(): array {
    return [
      'published node' => [
        [
          'type' => 'article',
          'title' => 'Justo placerat',
          'status' => TRUE,
        ],
      ],
      'unpublished node' => [
        [
          'type' => 'article',
          'title' => 'Ligula vel ante',
          'status' => FALSE,
        ],
      ],
    ];
  }

}
