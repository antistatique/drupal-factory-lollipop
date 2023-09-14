<?php

namespace Drupal\factory_lollipop_paragraphs_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Paragraph Embed News for use in tests.
 *
 * To be used for tests of Paragraphs without fields.
 */
class ParagraphEmbedNewsFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return 'paragraph_embed_news';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('paragraph type', 'paragraph_type_embed_news', [
      'id' => 'embed_news',
      'label' => 'Embed News',
    ]);

    $lollipop->define('paragraph', 'paragraph_embed_news', [
      'type' => $lollipop->association('paragraph_type_embed_news'),
      'field_bar_entity_test' => ['target_id' => 1],
    ]);

    // Add a Foo field without default value.
    $lollipop->define('entity reference field', 'paragraph_embed_news_field_foo_entity_test', [
      'entity_type' => 'paragraph',
      'name' => 'field_foo_entity_test',
      'bundle' => $lollipop->association('paragraph_type_embed_news'),
      'target_entity_type' => 'entity_test',
    ]);
    $lollipop->create('paragraph_embed_news_field_foo_entity_test');

    // Add a Bar field with default value.
    $lollipop->define('entity reference field', 'paragraph_embed_news_field_bar_entity_test', [
      'entity_type' => 'paragraph',
      'name' => 'field_bar_entity_test',
      'bundle' => $lollipop->association('paragraph_type_embed_news'),
      'target_entity_type' => 'entity_test',
    ]);
    $lollipop->create('paragraph_embed_news_field_bar_entity_test');

  }

}
