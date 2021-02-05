<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\field\FieldConfigInterface;
use Drupal\factory_lollipop\Traits\RandomGeneratorTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Creates Drupal Entity Fields for use in tests.
 */
class EntityFieldFactoryType implements FactoryTypeInterface {
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
    return $type === 'entity field';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Entity Field.
   *
   * @param object|null $attributes
   *   Entity field attributes to use for creation.
   *
   * @return \Drupal\field\FieldConfigInterface
   *   The newly created or already existing Entity Field.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   */
  public function create(?object $attributes = NULL): FieldConfigInterface {
    $attributes = (array) $attributes;

    // The field name attribute is mandatory.
    if (!isset($attributes['name']) || empty($attributes['name'])) {
      throw new \InvalidArgumentException('The field name attribute is mandatory.');
    }

    // The field type attribute is mandatory.
    if (!isset($attributes['type']) || empty($attributes['type'])) {
      throw new \InvalidArgumentException('The field type attribute is mandatory.');
    }

    // The bundle attribute is mandatory.
    if (!isset($attributes['bundle']) || empty($attributes['bundle'])) {
      throw new \InvalidArgumentException('The bundle attribute is mandatory.');
    }

    // The field entity_type attribute is mandatory.
    if (!isset($attributes['entity_type']) || empty($attributes['entity_type'])) {
      throw new \InvalidArgumentException('The field entity type (entity_type) attribute is mandatory.');
    }

    $name = $attributes['name'] ?? $this->randomMachineName();
    $label = $attributes['label'] ?? $this->randomString(10);
    $storage_settings = $attributes['storage_settings'] ?? [];
    $config_settings = $attributes['config_settings'] ?? [];

    // Load the storage at the last moment to prevent requiring Field module
    // on shouldApply phase.
    $field_storage_config = $this->entityTypeManager->getStorage('field_storage_config');

    // Prevent creation of already existing Field.
    // @see Drupal\field\Entity\FieldStorageConfig::loadByName
    $field_storage = $field_storage_config->load($attributes['entity_type'] . '.' . $name);
    if (!$field_storage) {
      $field_storage = $field_storage_config->create([
        'field_name' => $name,
        'entity_type' => $attributes['entity_type'],
        'type' => $attributes['type'],
        'settings' => $storage_settings,
      ]);
      $field_storage->save();
    }

    // Load the storage at the last moment to prevent requiring Field module
    // on shouldApply phase.
    $field_config_storage = $this->entityTypeManager->getStorage('field_config');

    $instance = $field_config_storage->create([
      'field_storage' => $field_storage,
      'bundle' => $attributes['bundle'],
      'label' => $label,
      'settings' => $config_settings,
    ]);
    $instance->save();

    return $instance;
  }

}
