<?php

namespace Drupal\Tests\factory_lollipop\Kernel\FactoryType;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FixtureFactory
 *
 * @group factory_lollipop
 */
class FixtureFactoryTest extends EntityKernelTestBase {

  /**
   * The Factory Lollipop fixture factory.
   *
   * @var \Drupal\factory_lollipop\FixtureFactory
   */
  protected $factoryLollipop;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'factory_lollipop',
    'node',
    'taxonomy',
    'menu_link_content',
    'link',
    'file',
    'image',
    'media',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('menu_link_content');
    $this->installEntitySchema('file');
    $this->installEntitySchema('media');
    $this->installSchema('file', 'file_usage');

    $this->factoryLollipop = $this->container->get('factory_lollipop.fixture_factory');
  }

  /**
   * @covers ::association
   * @covers \Drupal\factory_lollipop\FactoryType\NodeTypeFactoryType::getIdentifier
   * @covers \Drupal\factory_lollipop\FactoryType\NodeFactoryType::getIdentifier
   */
  public function testAssociationNode(): void {
    // Ensure Association for Node Type.
    $this->factoryLollipop->define('node type', 'node_type_article', [
      'type' => 'article',
    ]);
    $node_type_id = $this->factoryLollipop->association('node_type_article')();
    self::assertSame('article', $node_type_id);

    // Ensure Association for Node.
    $this->factoryLollipop->define('node', 'node_article', [
      'type' => $this->factoryLollipop->association('node_type_article'),
    ]);
    $node_id = $this->factoryLollipop->association('node_article')();
    self::assertSame('1', $node_id);
  }

  /**
   * @covers ::association
   * @covers \Drupal\factory_lollipop\FactoryType\VocabularyFactoryType::getIdentifier
   * @covers \Drupal\factory_lollipop\FactoryType\TaxonomyTermFactoryType::getIdentifier
   */
  public function testAssociationTaxonomy(): void {
    // Ensure Association for Vocabulary.
    $this->factoryLollipop->define('vocabulary', 'vocabulary_tags', [
      'vid' => 'tags',
    ]);
    $vid = $this->factoryLollipop->association('vocabulary_tags')();
    self::assertSame('tags', $vid);

    // Ensure Association for Taxonomy Term.
    $this->factoryLollipop->define('taxonomy term', 'taxonomy_term_tags', [
      'vid' => $this->factoryLollipop->association('vocabulary_tags'),
    ]);
    $tid = $this->factoryLollipop->association('taxonomy_term_tags')();
    self::assertSame('1', $tid);
  }

  /**
   * @covers ::association
   * @covers \Drupal\factory_lollipop\FactoryType\FileFactoryType::getIdentifier
   * @covers \Drupal\factory_lollipop\FactoryType\MediaTypeFactoryType::getIdentifier
   * @covers \Drupal\factory_lollipop\FactoryType\MediaFactoryType::getIdentifier
   */
  public function testAssociationMedia(): void {
    // Ensure Association for File.
    $this->factoryLollipop->define('file', 'file_tmp', [
      'scheme' => 'temporary',
    ]);
    $fid = $this->factoryLollipop->association('file_tmp')();
    self::assertSame('1', $fid);

    // Ensure Association for Media Type.
    $this->factoryLollipop->define('media type', 'media_type_file', [
      'id' => 'media_file',
      'source' => 'file',
    ]);
    $media_type = $this->factoryLollipop->association('media_type_file')();
    self::assertSame('media_file', $media_type);

    // Ensure Association for Media.
    $this->factoryLollipop->define('media', 'media_file', [
      'bundle' => $this->factoryLollipop->association('media_type_file'),
      'status' => 1,
      'field_bar' => 'Aenean tortor convallis nibh',
    ]);
    $mid = $this->factoryLollipop->association('media_file')();
    self::assertSame('1', $mid);
  }

  /**
   * @covers ::association
   * @covers \Drupal\factory_lollipop\FactoryType\UserFactoryType::getIdentifier
   * @covers \Drupal\factory_lollipop\FactoryType\RoleFactoryType::getIdentifier
   */
  public function testAssociationUser(): void {
    // Ensure Association for User.
    $this->factoryLollipop->define('user', 'user', []);
    $uid = $this->factoryLollipop->association('user')();
    self::assertSame('1', $uid);

    // Ensure Association for Role.
    $this->factoryLollipop->define('role', 'role_architect', [
      'rid' => 'architect',
      'name' => 'Architect',
      'permissions' => ['administer themes'],
    ]);
    $role = $this->factoryLollipop->association('role_architect')();
    self::assertSame(['architect' => 'Architect'], $role);
  }

  /**
   * @covers ::association
   * @covers \Drupal\factory_lollipop\FactoryType\MenuFactoryType::getIdentifier
   * @covers \Drupal\factory_lollipop\FactoryType\MenuLinkFactoryType::getIdentifier
   */
  public function testAssociationMenu(): void {
    // Ensure Association for Menu.
    $this->factoryLollipop->define('menu', 'menu_main', [
      'id' => 'main',
    ]);
    $menu = $this->factoryLollipop->association('menu_main')();
    self::assertSame('main', $menu);

    // Ensure Association for Menu Link with Hierarchy works.
    $this->factoryLollipop->define('menu link', 'menu_main_link_parent', [
      'title' => 'parent',
      'provider' => $this->factoryLollipop->association('menu_main'),
      'menu_name' => $this->factoryLollipop->association('menu_main'),
      'bundle' => 'menu_link_content',
      'link' => ['uri' => 'internal:/menu-test/hierarchy/parent'],
    ]);
    $link = $this->factoryLollipop->association('menu_main_link_parent')();
    self::assertSame('1', $link);
  }

  /**
   * @covers ::association
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldFactoryType::getIdentifier
   */
  public function testAssociationEntityField(): void {
    // Ensure Association for Node Type.
    $this->factoryLollipop->define('node type', 'node_type_page', [
      'type' => 'page',
    ]);

    // Ensure Association for Entity Field.
    $this->factoryLollipop->define('entity field', 'node_page_field_foo', [
      'entity_type' => 'node',
      'name' => 'field_foo',
      'bundle' => $this->factoryLollipop->association('node_type_page'),
      'type' => 'text',
    ]);
    $field = $this->factoryLollipop->association('node_page_field_foo')();
    self::assertSame('node.page.field_foo', $field);
  }

  /**
   * @covers ::association
   * @covers \Drupal\factory_lollipop\FactoryType\EntityFieldEntityReferenceFactoryType::getIdentifier
   */
  public function testAssociationEntityFieldEntityReference(): void {
    // Ensure Association for Node Type.
    $this->factoryLollipop->define('node type', 'node_type_news', [
      'type' => 'news',
    ]);

    // Ensure Association for Entity Reference Field.
    $this->factoryLollipop->define('entity reference field', 'node_news_field_foo_entity_test', [
      'entity_type' => 'node',
      'name' => 'field_foo_entity_test',
      'bundle' => $this->factoryLollipop->association('node_type_news'),
      'target_entity_type' => 'node',
    ]);
    $field = $this->factoryLollipop->association('node_news_field_foo_entity_test')();
    self::assertSame('node.news.field_foo_entity_test', $field);
  }

}
