<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\FieldConfigInterface;
use Drupal\Tests\field\Traits\EntityReferenceTestTrait;
use Drupal\Tests\RandomGeneratorTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Creates Drupal Entity Reference Fields for use in tests.
 */
class EntityFieldEntityReferenceFactoryType implements FactoryTypeInterface {
  use RandomGeneratorTrait;
  use EntityReferenceTestTrait;

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
    return $type === 'entity reference field';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Entity Reference Field.
   *
   * @param object|null $attributes
   *   Entity field attributes to use for creation.
   *
   * @return \Drupal\field\FieldConfigInterface
   *   The newly created or already existing Entity Reference Field.
   *
   * @internal
   */
  public function create(?object $attributes = NULL): FieldConfigInterface {
    $attributes = (array) $attributes;

    // The field name attribute is mandatory.
    if (!isset($attributes['name']) || empty($attributes['name'])) {
      throw new \InvalidArgumentException('The field name attribute is mandatory.');
    }

    // The bundle attribute is mandatory.
    if (!isset($attributes['bundle']) || empty($attributes['bundle'])) {
      throw new \InvalidArgumentException('The bundle attribute is mandatory.');
    }

    // The field entity_type attribute is mandatory.
    if (!isset($attributes['entity_type']) || empty($attributes['entity_type'])) {
      throw new \InvalidArgumentException('The field entity type (entity_type) attribute is mandatory.');
    }

    // The target bundle attribute is mandatory.
    if (!isset($attributes['target_entity_type']) || empty($attributes['target_entity_type'])) {
      throw new \InvalidArgumentException('The target type (target_entity_type) attribute is mandatory.');
    }

    $field_name = $attributes['name'] ?? $this->randomMachineName();
    $label = $attributes['label'] ?? $this->randomString(10);
    $selection_handler = $attributes['selection_handler'] ?? 'default';
    $selection_handler_settings = $attributes['selection_handler_settings'] ?? [];
    $cardinality = $attributes['cardinality'] ?? 1;

    $this->createEntityReferenceField($attributes['entity_type'], $attributes['bundle'], $field_name, $label, $attributes['target_entity_type'], $selection_handler, $selection_handler_settings, $cardinality);

    return FieldConfig::loadByName($attributes['entity_type'], $attributes['bundle'], $field_name);
  }

}
