<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\menu_link_content\MenuLinkContentInterface;
use Drupal\factory_lollipop\Traits\RandomGeneratorTrait;

/**
 * Creates Drupal Menu Link for use in tests.
 */
class MenuLinkFactoryType implements FactoryTypeInterface {
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
    return $type === 'menu link';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\menu_link_content\MenuLinkContentInterface $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Menu Link.
   *
   * @param object|null $attributes
   *   Menu attributes to use for creation.
   *
   * @return \Drupal\menu_link_content\MenuLinkContentInterface
   *   The newly created or already existing Menu Link.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   */
  public function create(?object $attributes = NULL): MenuLinkContentInterface {
    $attributes = (array) $attributes;

    $title = $attributes['title'] ?? $this->randomString();

    // The menu_name attribute is mandatory.
    if (!isset($attributes['menu_name']) || empty($attributes['menu_name'])) {
      throw new \InvalidArgumentException('The menu_name attribute is mandatory.');
    }

    // Load the storage at the last moment to prevent requiring System module
    // on shouldApply phase.
    $menu_storage = $this->entityTypeManager->getStorage('menu');

    $menu = $menu_storage->load($attributes['menu_name']);
    if (!$menu) {
      throw new \InvalidArgumentException('The menu_name attribute must be an existing menu.');
    }

    // Load the storage at the last moment to prevent requiring Menu Link
    // Content module on shouldApply phase.
    $menu_link_content_storage = $this->entityTypeManager->getStorage('menu_link_content');

    $values = $attributes ?? [];

    // Create new Menu.
    /** @var \Drupal\menu_link_content\MenuLinkContentInterface $link */
    $link = $menu_link_content_storage->create($values + [
      'title' => $title,
    ]);
    $link->save();
    return $link;
  }

}
