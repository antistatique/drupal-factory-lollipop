<?php

namespace Drupal\factory_lollipop\FactoryType;

/**
 * Provides the base interface for Factory Type.
 *
 * Factory Type define how a certain type of data (Nodes, Tersm, ...)
 * will created.
 * Many Factory Type may be available but only one will be applied to create
 * the final entity for testing.
 *
 * Examples: node creation, term creation, fields creation & binding, etc.
 */
interface FactoryTypeInterface {

  /**
   * Determine if this Factory must be used.
   *
   * @param string $type
   *   The attributes type to be passed if used.
   *
   * @return bool
   *   Should the factory be applied for creation.
   */
  public function shouldApply(string $type): bool;

  /**
   * Create or return and existing and persisted Drupal Entity|Object.
   *
   * @param object|null $attributes
   *   Attributes to be used for creation.
   *
   * @return mixed
   *   The newly created or already existing Drupal Entity|Object.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   */
  public function create(?object $attributes = NULL);

  /**
   * Get factory Drupal identifier.
   *
   * @param object $factory_object
   *   The Drupal factory to get identifier of.
   *
   * @return mixed|null
   *   The object identifier.
   */
  public function getIdentifier(object $factory_object);

}
