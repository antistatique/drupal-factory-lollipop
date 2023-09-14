<?php

namespace Drupal\factory_lollipop_paragraphs_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Paragraph Type Accordion for use in tests.
 */
class ParagraphTypeAccordionFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return 'paragraph_type_accordion';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('paragraph type', 'paragraph_type_accordion', [
      'id' => 'accordion',
      'label' => 'Accordion',
    ]);
  }

}
