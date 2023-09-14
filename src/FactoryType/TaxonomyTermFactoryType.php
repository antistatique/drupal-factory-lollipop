<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\factory_lollipop\Traits\RandomGeneratorTrait;
use Drupal\taxonomy\TermInterface;

/**
 * Creates Drupal Taxonomy Term for use in tests.
 */
class TaxonomyTermFactoryType implements FactoryTypeInterface {
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
    return $type === 'taxonomy term';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    /** @var \Drupal\taxonomy\TermInterface $factory_object */
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted Taxonomy Term.
   *
   * @param object|null $attributes
   *   TaxonomyTerm attributes to use for creation.
   *
   * @return \Drupal\taxonomy\TermInterface
   *   The newly created or already existing Taxonomy Term.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @internal
   */
  public function create(?object $attributes = NULL): TermInterface {
    $attributes = (array) $attributes;

    // The vid attribute is mandatory.
    if (!isset($attributes['vid']) || empty($attributes['vid'])) {
      throw new \InvalidArgumentException('The vid attribute is mandatory.');
    }

    // Load the storage at the last moment to prevent requiring Taxonomy module
    // on shouldApply phase.
    $vocabulary_storage = $this->entityTypeManager->getStorage('taxonomy_vocabulary');

    $vocabulary = $vocabulary_storage->load($attributes['vid']);
    if (!$vocabulary) {
      throw new \InvalidArgumentException('The vid attribute must be an existing vocabulary.');
    }

    // Load the storage at the last moment to prevent requiring Taxonomy module
    // on shouldApply phase.
    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');

    // Prevent creation of already existing Taxonomy Term.
    if (isset($attributes['tid'])) {
      $term = $term_storage->load($attributes['tid']);
      if ($term instanceof TermInterface) {
        return $term;
      }
    }

    $name = $attributes['name'] ?? $this->randomString();

    // Create a taxonomy term for testing.
    $values = $attributes ?? [];
    $term = $term_storage->create($values + [
      'vid' => $attributes['vid'],
      'name' => $name,
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ]);
    $term->save();
    return $term;
  }

}
