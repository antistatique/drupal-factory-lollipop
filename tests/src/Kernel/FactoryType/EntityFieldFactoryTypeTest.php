<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\FieldConfigInterface;
use Drupal\field\FieldStorageConfigInterface;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\NodeType;
use Drupal\taxonomy\Entity\Vocabulary;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType
 *
 * @group factory_lollipop
 */
class EntityFieldFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The Entity Field Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType
   */
  protected $entityFieldFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->entityFieldFactoryTypeResolver = new EntityFieldFactoryType();
    $this->entityFieldFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));

    // Create a node type for testing.
    $node_type = NodeType::create(['type' => 'article', 'name' => 'Article']);
    $node_type->save();

    // Create a Vocabulary for testing.
    $vocabulary = Vocabulary::create(['vid' => 'tags', 'name' => 'Tags']);
    $vocabulary->save();
  }

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'taxonomy',
    'datetime',
    'datetime_range',
  ];

  /**
   * @covers ::create
   *
   * @dataProvider providerNodeFieldValues
   * @dataProvider providerTaxonomyFieldValues
   */
  public function testCreate($data): void {
    // Create the field.
    $this->entityFieldFactoryTypeResolver->create((object) $data);

    $field = FieldConfig::load($data['entity_type'] . '.' . $data['bundle'] . '.field_foo');
    $this->assertInstanceOf(FieldConfigInterface::class, $field);
    $field_storage = FieldStorageConfig::loadByName($data['entity_type'], $data['name']);
    $this->assertInstanceOf(FieldStorageConfigInterface::class, $field_storage);

    self::assertEquals($data['type'], $field_storage->getType());
    self::assertEquals($data['name'], $field_storage->getName());
    self::assertEquals(1, $field_storage->getCardinality());
    self::assertEquals('field_storage_config', $field_storage->getEntityTypeId());

    // @todo assert bundle.
    // @todo assert entity_type.
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateTwiceNode(): void {
    // Create another node type for testing.
    $node_type = NodeType::create(['type' => 'page', 'name' => 'Page']);
    $node_type->save();

    // Create the field the first time.
    $this->testCreate([
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => 'article',
      'type' => 'text',
    ]);

    // Attach the same Field Storage to another Node Type.
    $field_page = $this->entityFieldFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => 'page',
      'type' => 'text',
    ]);
    self::assertEquals('node.page.field_foo', $field_page->id());
    self::assertEquals('field_foo', $field_page->getName());
    self::assertEquals('page', $field_page->getTargetBundle());
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateTwiceTaxonomyTerm(): void {
    // Create another Vocabulary for testing.
    $vocabulary = Vocabulary::create(['vid' => 'category', 'name' => 'Category']);
    $vocabulary->save();

    // Create the field the first time.
    $this->testCreate([
      'entity_type' => 'taxonomy_term',
      'name' => 'field_foo',
      'bundle' => 'tags',
      'type' => 'text',
    ]);

    // Attach the same Field Storage to another Vocabulary.
    $field_page = $this->entityFieldFactoryTypeResolver->create((object) [
      'entity_type' => 'taxonomy_term',
      'name' => 'field_foo',
      'bundle' => 'category',
      'type' => 'text',
    ]);
    self::assertEquals('taxonomy_term.category.field_foo', $field_page->id());
    self::assertEquals('field_foo', $field_page->getName());
    self::assertEquals('category', $field_page->getTargetBundle());
  }

  /**
   * Prevent creating an Entity Field on none-existing Entity-Type.
   *
   * @covers ::create
   */
  public function testCreateEntityTypeMustExists(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The "foo" entity type does not exist.');
    $this->entityFieldFactoryTypeResolver->create((object) [
      'entity_type' => 'foo',
      'name' => 'field_foo',
      'bundle' => 'article',
      'type' => 'text',
    ]);
  }

  /**
   * Prevent creating an Entity Field on none-existing Bundle.
   *
   * @covers ::create
   */
  public function testCreateBundleMustExists(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage("Missing bundle entity, entity type node_type, entity id foo.");
    $this->entityFieldFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => 'foo',
      'type' => 'text',
    ]);
  }

  /**
   * Prevent creating an Entity Field of not supported Field Type.
   *
   * @covers ::create
   */
  public function testCreateFieldTypeMustExists(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage("Attempt to create a field storage of unknown type bar.");
    $this->entityFieldFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => 'article',
      'type' => 'bar',
    ]);
  }

  /**
   * @covers ::create
   */
  public function testCreateConfigSettings(): void {
    $field = $this->entityFieldFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => 'article',
      'type' => 'integer',
      'config_settings' => [
        'min' => 12,
        'max' => 24,
        'prefix' => 'ThePrefix',
      ],
    ]);
    self::assertEquals('node.article.field_foo', $field->id());
    self::assertEquals('field_foo', $field->getName());
    self::assertEquals('article', $field->getTargetBundle());
  }

  /**
   * @covers ::create
   */
  public function testCreateStorageSettings(): void {
    $field = $this->entityFieldFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => 'article',
      'type' => 'decimal',
      'storage_settings' => ['precision' => 8, 'scale' => 4],
    ]);
    self::assertEquals('node.article.field_foo', $field->id());
    self::assertEquals('field_foo', $field->getName());
    self::assertEquals('article', $field->getTargetBundle());
  }

  /**
   * @covers ::create
   */
  public function testCreateRandomTitle(): void {
    $field = $this->entityFieldFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'label' => 'Lorem',
      'name' => 'field_foo',
      'bundle' => 'article',
      'type' => 'text',
    ]);
    self::assertEquals('Lorem', $field->getLabel());

    $field = $this->entityFieldFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'name' => 'field_bar',
      'bundle' => 'article',
      'type' => 'text',
    ]);

    self::assertNotEmpty($field->getLabel());
    self::assertSame(10, strlen($field->getLabel()));
  }

  /**
   * Data provider for ::testCreate.
   *
   * @return array
   *   Data provided.
   */
  public function providerTaxonomyFieldValues(): array {
    return [
      'text field' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_foo',
          'bundle' => 'tags',
          'type' => 'text',
        ],
      ],
      'boolean field' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_foo',
          'bundle' => 'tags',
          'type' => 'boolean',
        ],
      ],
      'string field' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_foo',
          'bundle' => 'tags',
          'type' => 'string',
        ],
      ],
      'string long field' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_foo',
          'bundle' => 'tags',
          'type' => 'string_long',
        ],
      ],
      'integer field' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_foo',
          'bundle' => 'tags',
          'type' => 'integer',
        ],
      ],
      'float field' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_foo',
          'bundle' => 'tags',
          'type' => 'float',
        ],
      ],
      'decimal field' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_foo',
          'bundle' => 'tags',
          'type' => 'decimal',
        ],
      ],
      'email field' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_foo',
          'bundle' => 'tags',
          'type' => 'email',
        ],
      ],
      'datetime field' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_foo',
          'bundle' => 'tags',
          'type' => 'datetime',
        ],
      ],
      'daterange field' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_foo',
          'bundle' => 'tags',
          'type' => 'daterange',
        ],
      ],
    ];
  }

  /**
   * Data provider for ::testCreate.
   *
   * @return array
   *   Data provided.
   */
  public function providerNodeFieldValues(): array {
    return [
      'text field' => [
        [
          'entity_type' => 'node',
          'name' => 'field_foo',
          'bundle' => 'article',
          'type' => 'text',
        ],
      ],
      'boolean field' => [
        [
          'entity_type' => 'node',
          'name' => 'field_foo',
          'bundle' => 'article',
          'type' => 'boolean',
        ],
      ],
      'string field' => [
        [
          'entity_type' => 'node',
          'name' => 'field_foo',
          'bundle' => 'article',
          'type' => 'string',
        ],
      ],
      'string long field' => [
        [
          'entity_type' => 'node',
          'name' => 'field_foo',
          'bundle' => 'article',
          'type' => 'string_long',
        ],
      ],
      'integer field' => [
        [
          'entity_type' => 'node',
          'name' => 'field_foo',
          'bundle' => 'article',
          'type' => 'integer',
        ],
      ],
      'float field' => [
        [
          'entity_type' => 'node',
          'name' => 'field_foo',
          'bundle' => 'article',
          'type' => 'float',
        ],
      ],
      'decimal field' => [
        [
          'entity_type' => 'node',
          'name' => 'field_foo',
          'bundle' => 'article',
          'type' => 'decimal',
        ],
      ],
      'email field' => [
        [
          'entity_type' => 'node',
          'name' => 'field_foo',
          'bundle' => 'article',
          'type' => 'email',
        ],
      ],
      'datetime field' => [
        [
          'entity_type' => 'node',
          'name' => 'field_foo',
          'bundle' => 'article',
          'type' => 'datetime',
        ],
      ],
      'daterange field' => [
        [
          'entity_type' => 'node',
          'name' => 'field_foo',
          'bundle' => 'article',
          'type' => 'daterange',
        ],
      ],
    ];
  }

}
