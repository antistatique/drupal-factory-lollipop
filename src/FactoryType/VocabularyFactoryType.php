<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\factory_lollipop\Traits\RandomGeneratorTrait;
use Drupal\taxonomy\VocabularyInterface;

/**
 * Creates Drupal Vocabulary for use in tests.
 */
class VocabularyFactoryType implements FactoryTypeInterface {
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
    return $type === 'vocabulary';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\taxonomy\VocabularyInterface $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Vocabulary.
   *
   * @param object|null $attributes
   *   Vocabulary attributes to use for creation.
   *
   * @return \Drupal\taxonomy\VocabularyInterface
   *   The newly created or already existing Vocabulary.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   */
  public function create(?object $attributes = NULL): VocabularyInterface {
    // Load the storage at the last moment to prevent requiring Node module
    // on shouldApply phase.
    $vocabulary_storage = $this->entityTypeManager->getStorage('taxonomy_vocabulary');

    $attributes = (array) $attributes;

    $vid = $attributes['vid'] ?? mb_strtolower($this->randomMachineName());
    $name = $attributes['name'] ?? $this->randomString();

    // Prevent creation of already existing Vocabulary.
    $vocabulary = $vocabulary_storage->load($vid);
    if ($vocabulary) {
      return $vocabulary;
    }

    // Create a vocabulary for testing.
    $values = $attributes ?? [];
    $vocabulary = $vocabulary_storage->create($values + [
      'vid' => $vid,
      'name' => $name,
    ]);
    $vocabulary->save();
    return $vocabulary;
  }

}
