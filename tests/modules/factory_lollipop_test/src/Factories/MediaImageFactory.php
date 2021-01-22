<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Media for use in tests.
 */
class MediaImageFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'media_image';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('media type', 'media_type_image', [
      'id' => 'media_image',
      'source' => 'image',
    ]);

    $lollipop->define('media', 'media_image', [
      'bundle' => $lollipop->association('media_type_image'),
      'status' => 1,
      'name' => 'Enim lectus orci faucibus suscipit',
    ]);
  }

}
