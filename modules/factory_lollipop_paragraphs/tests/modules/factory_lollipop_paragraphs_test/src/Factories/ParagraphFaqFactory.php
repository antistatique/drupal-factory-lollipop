<?php

namespace Drupal\factory_lollipop_paragraphs_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Paragraph Faq for use in tests.
 *
 * To be used for tests of Paragraphs with fields.
 */
class ParagraphFaqFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return 'paragraph_faq';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('paragraph type', 'paragraph_type_faq', [
      'id' => 'faq',
      'label' => 'Faq',
    ]);

    $lollipop->define('paragraph', 'paragraph_faq', [
      'type' => $lollipop->association('paragraph_type_faq'),
      'field_answer' => 'We have not answer for this question.',
    ]);

    // Add a Tag field without default value.
    $lollipop->define('entity field', 'paragraph_faq_field_tag', [
      'entity_type' => 'paragraph',
      'name' => 'field_tag',
      'bundle' => $lollipop->association('paragraph_type_faq'),
      'type' => 'text',
    ]);
    $lollipop->create('paragraph_faq_field_tag');

    // Add a Question field without default value.
    $lollipop->define('entity field', 'paragraph_faq_field_question', [
      'entity_type' => 'paragraph',
      'name' => 'field_question',
      'bundle' => $lollipop->association('paragraph_type_faq'),
      'type' => 'text',
    ]);
    $lollipop->create('paragraph_faq_field_question');

    // Add a Answer field with default value.
    $lollipop->define('entity field', 'paragraph_faq_field_answer', [
      'entity_type' => 'paragraph',
      'name' => 'field_answer',
      'bundle' => $lollipop->association('paragraph_type_faq'),
      'type' => 'text',
    ]);
    $lollipop->create('paragraph_faq_field_answer');
  }

}
