<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FixtureFactory
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class FixtureFactoryTest extends LollipopKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'taxonomy',
    'file',
    'media',
    'factory_lollipop_test',
  ];

  /**
   * @covers ::loadAllDefinitions
   * @covers ::getDefinitions
   */
  public function testLoadAllDefinitions(): void {
    $this->factoryLollipop->loadAllDefinitions();

    // Get all definitions defined by module "factory_lollipop_test" via
    // service-tag 'factory_lollipop.factory_resolver'.
    $definitions = $this->factoryLollipop->getDefinitions();

    self::assertCount(44, $definitions);
    self::assertEqualsCanonicalizing([
      'media_type_video',
      'media_video',
      'media_type_video_field_foo_entity_test',
      'media_type_video_field_bar_entity_test',
      'media_type_file',
      'media_file',
      'media_type_file_field_foo',
      'media_type_file_field_bar',
      'media_type_image',
      'media_image',
      'vocabulary_tags',
      'vocabulary_categories',
      'taxonomy_term_categories',
      'taxonomy_term_categorie_field_foo_entity_test',
      'taxonomy_term_categorie_field_bar_entity_test',
      'vocabulary_countries',
      'taxonomy_term_countries',
      'taxonomy_term_countries_field_foo',
      'taxonomy_term_countries_field_bar',
      'taxonomy_term_tags',
      'menu_main',
      'menu_main_link_parent',
      'menu_main_link_child_1',
      'menu_main_link_child_1_1',
      'menu_main_link_child_2',
      'menu_link_parent',
      'menu_footer',
      'user',
      'user_admin',
      'user_moderator',
      'role_architect',
      'role_superuser',
      'file_tmp',
      'file_public',
      'node_type_article',
      'node_type_news',
      'node_news',
      'node_news_field_foo_entity_test',
      'node_news_field_bar_entity_test',
      'node_type_page',
      'node_page',
      'node_page_field_foo',
      'node_page_field_bar',
      'node_article',
    ], array_keys($definitions));
  }

  /**
   * @covers ::loadAllDefinitions
   * @covers ::getDefinitions
   */
  public function testLoadAllDefinitionsExceptedExcluded(): void {
    $this->factoryLollipop->loadAllDefinitions(['node_news']);

    // Get all definitions defined by module "factory_lollipop_test" via
    // service-tag 'factory_lollipop.factory_resolver'.
    $definitions = $this->factoryLollipop->getDefinitions();

    self::assertCount(40, $definitions);
    self::assertEqualsCanonicalizing([
      'media_type_video',
      'media_video',
      'media_type_video_field_foo_entity_test',
      'media_type_video_field_bar_entity_test',
      'media_type_file',
      'media_file',
      'media_type_file_field_foo',
      'media_type_file_field_bar',
      'media_type_image',
      'media_image',
      'vocabulary_tags',
      'vocabulary_categories',
      'taxonomy_term_categories',
      'taxonomy_term_categorie_field_foo_entity_test',
      'taxonomy_term_categorie_field_bar_entity_test',
      'vocabulary_countries',
      'taxonomy_term_countries',
      'taxonomy_term_countries_field_foo',
      'taxonomy_term_countries_field_bar',
      'taxonomy_term_tags',
      'menu_main',
      'menu_main_link_parent',
      'menu_main_link_child_1',
      'menu_main_link_child_1_1',
      'menu_main_link_child_2',
      'menu_link_parent',
      'menu_footer',
      'user',
      'user_admin',
      'user_moderator',
      'role_architect',
      'role_superuser',
      'file_tmp',
      'file_public',
      'node_type_article',
      'node_type_page',
      'node_page',
      'node_page_field_foo',
      'node_page_field_bar',
      'node_article',
    ], array_keys($definitions));
  }

  /**
   * @covers ::loadDefinitions
   * @covers ::getDefinitions
   */
  public function testLoadDefinitions(): void {
    $this->factoryLollipop->loadDefinitions(['node_news']);

    // Get definition from service-tag 'factory_lollipop.factory_resolver' on
    // module "factory_lollipop_test". Filter definition to load only service
    // named "node_news".
    $definitions = $this->factoryLollipop->getDefinitions();

    self::assertCount(4, $definitions);
    self::assertSame([
      'node_type_news',
      'node_news',
      'node_news_field_foo_entity_test',
      'node_news_field_bar_entity_test',
    ], array_keys($definitions));
  }

}
