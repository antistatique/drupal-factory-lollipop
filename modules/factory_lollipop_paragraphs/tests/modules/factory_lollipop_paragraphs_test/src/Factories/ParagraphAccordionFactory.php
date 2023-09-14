<?php

namespace Drupal\factory_lollipop_paragraphs_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Paragraph Accordion for use in tests.
 *
 * To be used for tests of Paragraphs without fields.
 */
class ParagraphAccordionFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return 'paragraph_accordion';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('paragraph type', 'paragraph_type_accordion', [
      'id' => 'accordion',
      'label' => 'Accordion',
    ]);

    $lollipop->define('paragraph', 'paragraph_accordion', [
      'type' => $lollipop->association('paragraph_type_accordion'),
    ]);
  }

}
