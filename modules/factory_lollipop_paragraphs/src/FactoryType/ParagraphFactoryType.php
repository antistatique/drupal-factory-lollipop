<?php

namespace Drupal\factory_lollipop_paragraphs\FactoryType;

use Drupal\factory_lollipop\FactoryType\FactoryTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Creates Drupal Paragraphs for use in tests.
 */
class ParagraphFactoryType implements FactoryTypeInterface {

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
    return $type === 'paragraph';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\paragraphs\Entity\Paragraph $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Entity Paragraph.
   *
   * @param object|null $attributes
   *   Paragraph attributes to use for creation.
   *
   * @return \Drupal\paragraphs\Entity\Paragraph
   *   The newly created or already existing Paragraph.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   */
  public function create(?object $attributes = NULL): Paragraph {
    $attributes = (array) $attributes;

    // The type attribute is mandatory.
    if (!isset($attributes['type']) || empty($attributes['type'])) {
      throw new \InvalidArgumentException('The type attribute is mandatory.');
    }

    // Load the storage at the last moment to prevent requiring Paragraph Type
    // module on shouldApply phase.
    $paragraph_type_storage = $this->entityTypeManager->getStorage('paragraphs_type');

    $paragraph_type = $paragraph_type_storage->load($attributes['type']);
    if (!$paragraph_type) {
      throw new \InvalidArgumentException('The type attribute must be an existing Paragraph Type.');
    }

    // Load the storage at the last moment to prevent requiring Paragraph module
    // on shouldApply phase.
    $paragraph_storage = $this->entityTypeManager->getStorage('paragraph');

    $values = $attributes ?? [];
    $paragraph = $paragraph_storage->create($values + [
      'type' => $paragraph_type->id(),
    ]);
    $paragraph->save();
    return $paragraph;
  }

}
