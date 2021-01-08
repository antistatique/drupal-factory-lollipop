<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeTypeInterface;
use Drupal\Tests\RandomGeneratorTrait;

/**
 * Creates Drupal Node Type for use in tests.
 */
class NodeTypeFactoryType implements FactoryTypeInterface {
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
    return $type === 'node type';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\node\NodeTypeInterface $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Node Type.
   *
   * @param object|null $attributes
   *   Node types attributes to use for creation.
   *
   * @return \Drupal\node\NodeTypeInterface
   *   The newly created or already existing Node Type.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   */
  public function create(?object $attributes = NULL): NodeTypeInterface {
    // Load the storage at the last moment to prevent requiring Node module
    // on shouldApply phase.
    $node_storage = $this->entityTypeManager->getStorage('node_type');

    $type = $attributes->type ?? mb_strtolower($this->randomMachineName());
    $name = $attributes->name ?? $this->randomString();

    // Prevent creation of already existing NodeType.
    $node_type = $node_storage->load($type);
    if ($node_type) {
      return $node_type;
    }

    // Create a node type for testing.
    $node_type = $node_storage->create([
      'type' => $type,
      'name' => $name,
    ]);
    $node_type->save();

    return $node_type;
  }

}
