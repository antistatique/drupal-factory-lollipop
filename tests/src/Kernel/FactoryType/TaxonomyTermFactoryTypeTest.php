<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\Core\Language\LanguageInterface;
use Drupal\factory_lollipop\FactoryType\TaxonomyTermFactoryType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\taxonomy\Entity\Vocabulary;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\TaxonomyTermFactoryType
 *
 * @group factory_lollipop
 */
class TaxonomyTermFactoryTypeTest extends EntityKernelTestBase {

  /**
   * The TaxonomyTerm Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\TaxonomyTermFactoryType
   */
  protected $taxonomyTermFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('taxonomy_term');

    $this->taxonomyTermFactoryTypeResolver = new TaxonomyTermFactoryType();
    $this->taxonomyTermFactoryTypeResolver->setEntityTypeManager($this->container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'taxonomy',
  ];

  /**
   * @covers ::create
   *
   * @dataProvider providerValues
   */
  public function testCreate($data): void {
    // Create a Vocabulary for testing.
    $vocabulary = Vocabulary::create(['vid' => 'tags', 'name' => 'Tags']);
    $vocabulary->save();

    $term = $this->taxonomyTermFactoryTypeResolver->create((object) $data);

    self::assertEquals('tags', $term->bundle());
    self::assertEquals($data['name'], $term->getName());
    self::assertEquals($data['status'], $term->isPublished());
    self::assertEquals('und', $term->getTranslationLanguages()[LanguageInterface::LANGCODE_NOT_SPECIFIED]->getId());
  }

  /**
   * @covers ::create
   * @depends testCreate
   */
  public function testCreateTwice(): void {
    $this->testCreate([
      'tid' => 1,
      'vid' => 'tags',
      'name' => 'Nisl',
      'status' => TRUE,
    ]);

    $term = $this->taxonomyTermFactoryTypeResolver->create((object) [
      'tid' => 1,
      'vid' => 'tags',
      'name' => 'Nisl 2',
    ]);
    self::assertEquals('tags', $term->bundle());
    self::assertEquals('Nisl', $term->getName());
  }

  /**
   * Prevent creating a Taxonomy Term Factory without Vocabulary attribute.
   *
   * @covers ::create
   */
  public function testCreateVocabularyMustExists(): void {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('The vid attribute must be an existing vocabulary.');

    $this->taxonomyTermFactoryTypeResolver->create((object) [
      'vid' => 'foo',
    ]);
  }

  /**
   * @covers ::create
   */
  public function testCreateRandomTitle(): void {
    // Create a Vocabulary for testing.
    $vocabulary = Vocabulary::create(['vid' => 'tags', 'name' => 'Tags']);
    $vocabulary->save();

    $term = $this->taxonomyTermFactoryTypeResolver->create((object) ['vid' => 'tags']);

    self::assertNotEmpty($term->getName());
    self::assertSame(8, strlen($term->getName()));
  }

  /**
   * Data provider for ::testCreate.
   *
   * @return array
   *   Data provided.
   */
  public function providerValues(): array {
    return [
      'published taxonomy term' => [
        [
          'vid' => 'tags',
          'name' => 'At gravida',
          'status' => TRUE,
        ],
      ],
      'unpublished taxonomy term' => [
        [
          'vid' => 'tags',
          'name' => 'Eros tristique accumsan ut',
          'status' => FALSE,
        ],
      ],
    ];
  }

}
