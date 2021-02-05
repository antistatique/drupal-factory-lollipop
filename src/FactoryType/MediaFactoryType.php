<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\media\MediaInterface;
use Drupal\factory_lollipop\Traits\RandomGeneratorTrait;

/**
 * Creates Drupal Medias for use in tests.
 */
class MediaFactoryType implements FactoryTypeInterface {
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
    return $type === 'media';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\media\MediaInterface $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Media of the given type.
   *
   * @param object|null $attributes
   *   Media attributes to use for creation.
   *
   * @return \Drupal\media\MediaInterface
   *   The new media object.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function create(?object $attributes = NULL): MediaInterface {
    $attributes = (array) $attributes;

    // The bundle attribute is mandatory.
    if (!isset($attributes['bundle']) || empty($attributes['bundle'])) {
      throw new \InvalidArgumentException('The bundle attribute is mandatory.');
    }

    // Load the storage at the last moment to prevent requiring Media module
    // on shouldApply phase.
    $media_type_storage = $this->entityTypeManager->getStorage('media_type');

    $media_type = $media_type_storage->load($attributes['bundle']);
    if (!$media_type) {
      throw new \InvalidArgumentException('The bundle attribute must be an existing media type.');
    }

    // Load the storage at the last moment to prevent requiring Media module
    // on shouldApply phase.
    $media_storage = $this->entityTypeManager->getStorage('media');

    $values = $attributes ?? [];
    $media = $media_storage->create($values + [
      'bundle' => 'camelids',
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ]);
    $media->save();
    return $media;
  }

}
