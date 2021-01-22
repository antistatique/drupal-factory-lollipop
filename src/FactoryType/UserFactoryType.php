<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Tests\RandomGeneratorTrait;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Drupal\user\UserInterface;

/**
 * Creates Drupal Users for use in tests.
 */
class UserFactoryType implements FactoryTypeInterface {
  use RandomGeneratorTrait;
  use UserCreationTrait;

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
    return $type === 'user';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\user\UserInterface $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted User of the given type.
   *
   * @param object|null $attributes
   *   User attributes to use for creation.
   *
   * @return \Drupal\user\UserInterface
   *   The new node object.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function create(?object $attributes = NULL): UserInterface {
    // Load the storage at the last moment to prevent requiring User module
    // on shouldApply phase.
    $user_storage = $this->entityTypeManager->getStorage('user');

    $attributes = (array) $attributes;
    $uid = $attributes['uid'] ?? NULL;
    $name = $attributes['name'] ?? $this->randomMachineName();
    $mail = $attributes['mail'] ?? $name . '@example.com';
    $roles = $attributes['roles'] ?? [];
    $status = $attributes['status'] ?? 1;
    $pass = $attributes['pass'] ?? user_password();

    // Prevent creation of already existing User.
    if ($uid) {
      $user = $user_storage->load($uid);
      if ($user instanceof UserInterface) {
        return $user;
      }
    }

    $values = $attributes ?? [];

    // Create new User.
    /** @var \Drupal\user\UserInterface $user */
    $user = $user_storage->create($values + [
      'uid' => $uid,
      'name' => $name,
      'mail' => $mail,
      'pass' => $pass,
      'status' => $status,
      'roles' => $roles,
    ]);
    $user->save();
    return $user;
  }

}
