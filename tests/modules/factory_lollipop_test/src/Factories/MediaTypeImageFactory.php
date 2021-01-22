<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Media Type for use in tests.
 */
class MediaTypeImageFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'media_type_image';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('media type', 'media_type_image', [
      'id' => 'media_image',
      'source' => 'image',
    ]);
  }

}
