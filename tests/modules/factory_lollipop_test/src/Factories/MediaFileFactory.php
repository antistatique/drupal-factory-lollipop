<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Media fieldable for use in tests.
 */
class MediaFileFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'media_file';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('media type', 'media_type_file', [
      'id' => 'media_file',
      'source' => 'file',
    ]);

    $lollipop->define('media', 'media_file', [
      'bundle' => $lollipop->association('media_type_file'),
      'status' => 1,
      'field_bar' => 'Aenean tortor convallis nibh',
    ]);

    // Add a Foo field without default value.
    $lollipop->define('entity field', 'media_type_file_field_foo', [
      'entity_type' => 'media',
      'name' => 'field_foo',
      'bundle' => $lollipop->association('media_type_file'),
      'type' => 'email',
    ]);
    $lollipop->create('media_type_file_field_foo');

    // Add a Bar field with default value.
    $lollipop->define('entity field', 'media_type_file_field_bar', [
      'entity_type' => 'media',
      'name' => 'field_bar',
      'bundle' => $lollipop->association('media_type_file'),
      'type' => 'text',
    ]);
    $lollipop->create('media_type_file_field_bar');
  }

}
