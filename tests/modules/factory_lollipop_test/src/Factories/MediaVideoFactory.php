<?php

namespace Drupal\factory_lollipop_test\Factories;

use Drupal\factory_lollipop\FactoryInterface;
use Drupal\factory_lollipop\FixtureFactory;

/**
 * Creates Drupal Media Type fieldable (with references) for use in tests.
 */
class MediaVideoFactory implements FactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getName():string {
    return 'media_video';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(FixtureFactory $lollipop): void {
    $lollipop->define('media type', 'media_type_video', [
      'id' => 'media_video',
      'source' => 'video_file',
    ]);

    $lollipop->define('media', 'media_video', [
      'bundle' => $lollipop->association('media_type_video'),
      'status' => 1,
      'field_bar_entity_test' => ['target_id' => 1],
    ]);

    // Add a Foo field without default value.
    $lollipop->define('entity reference field', 'media_type_video_field_foo_entity_test', [
      'entity_type' => 'media',
      'name' => 'field_foo_entity_test',
      'bundle' => $lollipop->association('media_type_video'),
      'target_entity_type' => 'entity_test',
    ]);
    $lollipop->create('media_type_video_field_foo_entity_test');

    // Add a Bar field with default value.
    $lollipop->define('entity reference field', 'media_type_video_field_bar_entity_test', [
      'entity_type' => 'media',
      'name' => 'field_bar_entity_test',
      'bundle' => $lollipop->association('media_type_video'),
      'target_entity_type' => 'entity_test',
    ]);
    $lollipop->create('media_type_video_field_bar_entity_test');
  }

}
