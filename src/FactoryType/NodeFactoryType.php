<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;
use Drupal\Tests\RandomGeneratorTrait;

/**
 * Creates Drupal Nodes for use in tests.
 */
class NodeFactoryType implements FactoryTypeInterface {
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
    return $type === 'node';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\node\NodeInterface $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Node of the given type.
   *
   * @param object|null $attributes
   *   Node attributes to use for creation.
   *
   * @return \Drupal\node\NodeInterface
   *   The new node object.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function create(?object $attributes = NULL): NodeInterface {
    $attributes = (array) $attributes;

    // The type attribute is mandatory.
    if (!isset($attributes['type']) || empty($attributes['type'])) {
      throw new \InvalidArgumentException('The type attribute is mandatory.');
    }

    // Load the storage at the last moment to prevent requiring Node module
    // on shouldApply phase.
    $node_type_storage = $this->entityTypeManager->getStorage('node_type');

    $node_type = $node_type_storage->load($attributes['type']);
    if (!$node_type) {
      throw new \InvalidArgumentException('The type attribute must be an existing node type.');
    }

    // Load the storage at the last moment to prevent requiring Node module
    // on shouldApply phase.
    $node_storage = $this->entityTypeManager->getStorage('node');

    $values = $attributes ?? [];
    $node = $node_storage->create($values + [
      'title' => $this->randomString(8),
      'type' => $node_type->id(),
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ]);
    $node->save();
    return $node;
  }

}
