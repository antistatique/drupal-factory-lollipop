<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\node\Entity\NodeType;
use Drupal\node\NodeTypeInterface;
use Drupal\Tests\RandomGeneratorTrait;

/**
 * Creates Drupal Node Type for use in tests.
 */
class NodeTypeFactoryType implements FactoryTypeInterface {
  use RandomGeneratorTrait;

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
    $type = $attributes->type ?? mb_strtolower($this->randomMachineName());
    $name = $attributes->name ?? $this->randomString();

    // Prevent creation of already existing NodeType.
    if ($node_type = NodeType::load($type)) {
      return $node_type;
    }

    // Create a node type for testing.
    $node_type = NodeType::create(['type' => $type, 'name' => $name]);
    $node_type->save();

    return $node_type;
  }

}
