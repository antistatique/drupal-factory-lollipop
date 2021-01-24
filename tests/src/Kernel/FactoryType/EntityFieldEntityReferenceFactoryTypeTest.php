<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\FieldConfigInterface;
use Drupal\field\FieldStorageConfigInterface;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\NodeType;
use Drupal\taxonomy\Entity\Vocabulary;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType
 *
 * @group factory_lollipop
 */
class EntityFieldEntityReferenceFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The Entity Field Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType
   */
  protected $entityFieldEntityReferenceFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->entityFieldEntityReferenceFactoryTypeResolver = new EntityFieldEntityReferenceFactoryType();
    $this->entityFieldEntityReferenceFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));

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
  public static $modules = [
    'node',
    'taxonomy',
    'file',
  ];

  /**
   * @covers ::create
   *
   * @dataProvider providerNodeFieldValues
   * @dataProvider providerTaxonomyFieldValues
   */
  public function testCreate($data): void {
    // Create the field.
    $this->entityFieldEntityReferenceFactoryTypeResolver->create((object) $data);

    $field = FieldConfig::load($data['entity_type'] . '.' . $data['bundle'] . '.' . $data['name']);
    self::assertInstanceOf(FieldConfigInterface::class, $field);
    $field_storage = FieldStorageConfig::loadByName($data['entity_type'], $data['name']);
    self::assertInstanceOf(FieldStorageConfigInterface::class, $field_storage);

    self::assertEquals('entity_reference', $field_storage->getType());
    self::assertEquals($data['name'], $field_storage->getName());

    if (isset($data['cardinality'])) {
      self::assertEquals($data['cardinality'], $field_storage->getCardinality());
    }
    else {
      self::assertEquals(1, $field_storage->getCardinality());
    }

    if (isset($data['target_entity_type'])) {
      self::assertEquals($data['target_entity_type'], $field_storage->getSetting('target_type'));
    }

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
      'target_entity_type' => 'user',
    ]);

    // Attach the same Field Storage to another Entity.
    $field_page = $this->entityFieldEntityReferenceFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => 'page',
      'target_entity_type' => 'user',
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
      'target_entity_type' => 'user',
    ]);

    // Attach the same Field Storage to another taxonomy term.
    $field_category = $this->entityFieldEntityReferenceFactoryTypeResolver->create((object) [
      'entity_type' => 'taxonomy_term',
      'name' => 'field_foo',
      'bundle' => 'category',
      'target_entity_type' => 'user',
    ]);

    self::assertEquals('taxonomy_term.category.field_foo', $field_category->id());
    self::assertEquals('field_foo', $field_category->getName());
    self::assertEquals('category', $field_category->getTargetBundle());
  }

  /**
   * Prevent creating an Entity Field on none-existing Entity-Type.
   *
   * @covers ::create
   */
  public function testCreateEntityTypeMustExists(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The "bar" entity type does not exist.');
    $this->entityFieldEntityReferenceFactoryTypeResolver->create((object) [
      'entity_type' => 'bar',
      'name' => 'field_foo',
      'bundle' => 'node',
      'target_entity_type' => 'node',
      'cardinality' => 5,
    ]);
  }

  /**
   * @covers ::create
   */
  public function testCreateCardinalitySettings(): void {
    $field = $this->entityFieldEntityReferenceFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => 'article',
      'target_entity_type' => 'node',
      'cardinality' => 5,
    ]);
    $field_storage = $field->getFieldStorageDefinition();

    self::assertEquals('node.article.field_foo', $field->id());
    self::assertEquals('field_foo', $field->getName());
    self::assertEquals([
      'handler' => 'default:node',
      'handler_settings' => [],
      'target_type' => 'node',
    ], $field->getSettings());
    self::assertEquals('entity_reference', $field_storage->getType());
    self::assertEquals(5, $field_storage->getCardinality());
  }

  /**
   * @covers ::create
   */
  public function testCreateTargetTypeSettings(): void {
    $field = $this->entityFieldEntityReferenceFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => 'article',
      'target_entity_type' => 'node',
      'selection_handler_settings' => [
        'target_type' => 'page',
      ],
    ]);
    $field_storage = $field->getFieldStorageDefinition();

    self::assertEquals('node.article.field_foo', $field->id());
    self::assertEquals('field_foo', $field->getName());
    self::assertEquals([
      'handler' => 'default:node',
      'handler_settings' => [
        'target_type' => 'page',
      ],
      'target_type' => 'node',
    ], $field->getSettings());
    self::assertEquals('entity_reference', $field_storage->getType());
    self::assertEquals(1, $field_storage->getCardinality());
    self::assertEquals([
      'target_type' => 'node',
    ], $field_storage->getSettings());
  }

  /**
   * @covers ::create
   */
  public function testCreateRandomTitle(): void {
    $field = $this->entityFieldEntityReferenceFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'label' => 'Lorem',
      'name' => 'field_foo',
      'bundle' => 'article',
      'target_entity_type' => 'node',
    ]);
    self::assertEquals('Lorem', $field->getLabel());

    $field = $this->entityFieldEntityReferenceFactoryTypeResolver->create((object) [
      'entity_type' => 'node',
      'name' => 'field_bar',
      'bundle' => 'article',
      'target_entity_type' => 'node',
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
      'Test content entity reference' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_test_taxonomy_term',
          'bundle' => 'tags',
          'target_entity_type' => 'taxonomy_term',
          'label' => 'Test content entity reference',
        ],
      ],
      'Test config entity reference' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_test_taxonomy_term',
          'bundle' => 'tags',
          'target_entity_type' => 'taxonomy_vocabulary',
          'label' => 'Test config entity reference',
          'selection_handler' => 'default',
          'selection_handler_settings' => [],
          'cardinality' => 1,
        ],
      ],
      'Test node entity reference' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_test_node',
          'bundle' => 'tags',
          'target_entity_type' => 'node',
          'label' => 'Test node entity reference',
          'selection_handler' => 'default',
          'selection_handler_settings' => [],
          'cardinality' => FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED,
        ],
      ],
      'Test node page only entity reference' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_test_node',
          'bundle' => 'tags',
          'target_entity_type' => 'node',
          'label' => 'Test node page only entity reference',
          'selection_handler' => 'default',
          'selection_handler_settings' => [
            'target_type' => 'page',
          ],
          'cardinality' => FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED,
        ],
      ],
      'Test user entity reference' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_test_user',
          'bundle' => 'tags',
          'target_entity_type' => 'node',
          'label' => 'Test user entity reference',
        ],
      ],
      'Test file entity reference' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_test_file',
          'bundle' => 'tags',
          'target_entity_type' => 'file',
          'label' => 'Test file entity reference',
        ],
      ],
      'Test content custom entity reference with string ID' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_test_entity_test_string_id',
          'bundle' => 'tags',
          'target_entity_type' => 'entity_test_string_id',
          'label' => 'Test content custom entity reference with string ID',
        ],
      ],
      'Test content custom entity reference' => [
        [
          'entity_type' => 'taxonomy_term',
          'name' => 'field_test_entity_test',
          'bundle' => 'tags',
          'target_entity_type' => 'entity_test',
          'label' => 'Test content custom entity reference',
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
      'Test content entity reference' => [[
        'entity_type' => 'node',
        'name' => 'field_test_taxonomy_term',
        'bundle' => 'article',
        'target_entity_type' => 'taxonomy_term',
        'label' => 'Test content entity reference',
      ],
      ],
      'Test config entity reference' => [[
        'entity_type' => 'node',
        'name' => 'field_test_taxonomy_term',
        'bundle' => 'article',
        'target_entity_type' => 'taxonomy_vocabulary',
        'label' => 'Test config entity reference',
        'selection_handler' => 'default',
        'selection_handler_settings' => [],
        'cardinality' => 1,
      ],
      ],
      'Test node entity reference' => [[
        'entity_type' => 'node',
        'name' => 'field_test_node',
        'bundle' => 'article',
        'target_entity_type' => 'node',
        'label' => 'Test node entity reference',
        'selection_handler' => 'default',
        'selection_handler_settings' => [],
        'cardinality' => FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED,
      ],
      ],
      'Test node page only entity reference' => [[
        'entity_type' => 'node',
        'name' => 'field_test_node',
        'bundle' => 'article',
        'target_entity_type' => 'node',
        'label' => 'Test node page only entity reference',
        'selection_handler' => 'default',
        'selection_handler_settings' => [
          'target_type' => 'page',
        ],
        'cardinality' => FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED,
      ],
      ],
      'Test user entity reference' => [[
        'entity_type' => 'node',
        'name' => 'field_test_user',
        'bundle' => 'article',
        'target_entity_type' => 'node',
        'label' => 'Test user entity reference',
      ],
      ],
      'Test file entity reference' => [[
        'entity_type' => 'node',
        'name' => 'field_test_file',
        'bundle' => 'article',
        'target_entity_type' => 'file',
        'label' => 'Test file entity reference',
      ],
      ],
      'Test content custom entity reference with string ID' => [[
        'entity_type' => 'node',
        'name' => 'field_test_entity_test_string_id',
        'bundle' => 'article',
        'target_entity_type' => 'entity_test_string_id',
        'label' => 'Test content custom entity reference with string ID',
      ],
      ],
      'Test content custom entity reference' => [[
        'entity_type' => 'node',
        'name' => 'field_test_entity_test',
        'bundle' => 'article',
        'target_entity_type' => 'entity_test',
        'label' => 'Test content custom entity reference',
      ],
      ],
    ];
  }

}
