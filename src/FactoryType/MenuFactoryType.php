<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Tests\RandomGeneratorTrait;
use Drupal\system\MenuInterface;

/**
 * Creates Drupal Menu for use in tests.
 */
class MenuFactoryType implements FactoryTypeInterface {
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
    return $type === 'menu';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\system\MenuInterface $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Menu.
   *
   * @param object|null $attributes
   *   Menu attributes to use for creation.
   *
   * @return \Drupal\system\MenuInterface
   *   The newly created or already existing Menu.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   */
  public function create(?object $attributes = NULL): MenuInterface {
    $attributes = (array) $attributes;
    $id = $attributes['id'] ?? NULL;
    $label = $attributes['label'] ?? $this->randomMachineName();

    // Load the storage at the last moment to prevent requiring Menu module
    // on shouldApply phase.
    $menu_storage = $this->entityTypeManager->getStorage('menu');

    // Prevent creation of already existing Menu.
    if ($id) {
      $menu = $menu_storage->load($id);
      if ($menu instanceof MenuInterface) {
        return $menu;
      }
    }

    $values = $attributes ?? [];

    // Create new Menu.
    /** @var \Drupal\system\MenuInterface $menu */
    $menu = $menu_storage->create($values + [
      'id' => $id,
      'label' => $label,
    ]);
    $menu->save();
    return $menu;
  }

}
