<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\factory_lollipop\Traits\RandomGeneratorTrait;
use Drupal\factory_lollipop\Traits\UserCreationTrait;
use Drupal\user\RoleInterface;

/**
 * Creates Drupal Role for use in tests.
 */
class RoleFactoryType implements FactoryTypeInterface {
  use UserCreationTrait;
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
    return $type === 'role';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\user\RoleInterface $factory_object */
    return [$factory_object->id() => $factory_object->label()];
  }

  /**
   * Create or return and existing and persisted Role.
   *
   * @param object|null $attributes
   *   Role attributes to use for creation.
   *
   * @return \Drupal\user\RoleInterface
   *   The newly created or already existing Role.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   *
   * @see Drupal\Tests\user\Traits\UserCreationTrait::createRole
   */
  public function create(?object $attributes = NULL): RoleInterface {
    // Load the storage at the last moment to prevent requiring User module
    // on shouldApply phase.
    $role_storage = $this->entityTypeManager->getStorage('user_role');

    $attributes = (array) $attributes;
    // Generate a random, lowercase machine name if none was passed.
    $rid = $attributes['rid'] ?? strtolower($this->randomMachineName(8));
    // Generate a random label.
    $name = $attributes['name'] ?? trim($this->randomString(8));
    $permissions = $attributes['permissions'] ?? [];
    $weight = $attributes['weight'] ?? NULL;

    // Prevent creation of already existing Role.
    $role = $role_storage->load($rid);
    if ($role) {
      return $role;
    }

    // Create new role.
    /** @var \Drupal\user\RoleInterface $role */
    $role = $role_storage->create([
      'id' => $rid,
      'label' => $name,
    ]);
    if (isset($weight)) {
      $role->set('weight', $weight);
    }
    $role->save();

    // Grant permissions to role.
    if (isset($permissions)) {
      $this->grantPermissions($role, $permissions);
    }
    return $role;
  }

}
