<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Tests\RandomGeneratorTrait;
use Drupal\media\MediaTypeInterface;

/**
 * Creates Drupal Media Type for use in tests.
 */
class MediaTypeFactoryType implements FactoryTypeInterface {
  use RandomGeneratorTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface|null
   */
  protected $entityTypeManager;

  /**
   * Sets the entity type manager.
   *
   * This is only called when the factory is instantiated.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The new entity type manager.
   */
  public function setEntityTypeManager(EntityTypeManagerInterface $entity_type_manager): void {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function shouldApply(string $type): bool {
    return $type === 'media type';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\media\MediaTypeInterface $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Media Type.
   *
   * @param object|null $attributes
   *   Media types attributes to use for creation.
   *
   * @return \Drupal\media\MediaTypeInterface
   *   The newly created or already existing Media Type.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   *
   * @see \Drupal\media\MediaTypeInterface
   * @see \Drupal\media\Entity\MediaType
   * @see \Drupal\Tests\media\Traits\MediaTypeCreationTrait
   */
  public function create(?object $attributes = NULL): MediaTypeInterface {
    $attributes = (array) $attributes;

    // The media source plugin ID attribute is mandatory.
    if (!isset($attributes['source']) || empty($attributes['source'])) {
      throw new \InvalidArgumentException('The media source plugin ID (source) attribute is mandatory.');
    }

    $id = $attributes['id'] ?? mb_strtolower($this->randomMachineName());
    $label = $attributes['label'] ?? $this->randomString();

    // Load the storage at the last moment to prevent requiring Media module
    // on shouldApply phase.
    $media_type_storage = $this->entityTypeManager->getStorage('media_type');

    // Prevent creation of already existing Media Type.
    $media_type = $media_type_storage->load($id);
    if ($media_type) {
      return $media_type;
    }

    $values = $attributes ?? [];

    // Create a Media Type for testing.
    $media_type = $media_type_storage->create($values + [
      'id' => $id,
      'label' => $label,
      'source' => $attributes['source'],
    ]);
    $media_type->save();

    // Create the source field.
    $source_field = $media_type->getSource()->createSourceField($media_type);
    $source_field->getFieldStorageDefinition()->save();
    $source_field->save();
    $media_type
      ->set('source_configuration', [
        'source_field' => $source_field->getName(),
      ])
      ->save();

    return $media_type;
  }

}
