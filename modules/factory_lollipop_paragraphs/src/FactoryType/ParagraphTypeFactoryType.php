<?php

namespace Drupal\factory_lollipop_paragraphs\FactoryType;

use Drupal\factory_lollipop\FactoryType\FactoryTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\factory_lollipop\Traits\RandomGeneratorTrait;
use Drupal\paragraphs\Entity\ParagraphsType;

/**
 * Creates Drupal Paragraphs for use in tests.
 */
class ParagraphTypeFactoryType implements FactoryTypeInterface {
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
    return $type === 'paragraph type';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\paragraphs\Entity\ParagraphsType $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Paragraph Type.
   *
   * @param object|null $attributes
   *   Paragraph types attributes to use for creation.
   *
   * @return \Drupal\paragraphs\Entity\ParagraphsType
   *   The newly created or already existing Paragraph Type.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   */
  public function create(?object $attributes = NULL): ParagraphsType {
    $attributes = (array) $attributes;

    $id = $attributes['id'] ?? mb_strtolower($this->randomMachineName());
    $label = $attributes['label'] ?? $this->randomString();

    // Load the storage at the last moment to prevent requiring Paragraph Type
    // module on shouldApply phase.
    $paragraph_type_storage = $this->entityTypeManager->getStorage('paragraphs_type');

    // Prevent creation of already existing Paragraph Type.
    $paragraph_type = $paragraph_type_storage->load($id);
    if ($paragraph_type) {
      return $paragraph_type;
    }

    $values = $attributes ?? [];

    // Create a Paragraph Type for testing.
    $paragraph_type = $paragraph_type_storage->create($values + [
      'id' => $id,
      'label' => $label,
    ]);
    $paragraph_type->save();

    return $paragraph_type;
  }

}
