<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Files for use in tests.
 */
class FileFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'file';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('file', 'file_tmp', [
      'scheme' => 'temporary',
    ]);

    $lollipop->define('file', 'file_public', [
      'scheme' => 'public',
    ]);
  }

}
