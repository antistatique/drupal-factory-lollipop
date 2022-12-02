<?php

namespace Drupal\factory_lollipop\FactoryType;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\FileInterface;
use Drupal\factory_lollipop\Traits\RandomGeneratorTrait;

/**
 * Creates Drupal Files for use in tests.
 */
class FileFactoryType implements FactoryTypeInterface {
  use RandomGeneratorTrait;

  /**
   * The system file config object.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $systemFileConfig;

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface|null
   */
  protected $entityTypeManager;

  /**
   * Constructs a new FileFactoryType object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, FileSystemInterface $file_system) {
    $this->systemFileConfig = $config_factory->get('system.file');
    $this->fileSystem = $file_system;
  }

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
    return $type === 'file';
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifier(object $factory_object) {
    return $factory_object->id();
  }

  /**
   * Create or return and existing and persisted File of the given type.
   *
   * @param object|null $attributes
   *   File attributes to use for creation.
   *
   * @return \Drupal\file\FileInterface
   *   The new file object.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function create(?object $attributes = NULL): FileInterface {
    $attributes = (array) $attributes;
    $scheme = $attributes['scheme'] ?? $this->systemFileConfig->get('default_scheme');
    $scheme .= '://';

    if (isset($attributes['path']) && !is_file($attributes['path'])) {
      throw new \InvalidArgumentException(sprintf('File "%s" does not exist.', $attributes['path']));
    }

    if (isset($attributes['path']) && !is_readable($attributes['path'])) {
      throw new \InvalidArgumentException(sprintf('File "%s" cannot be read.', $attributes['path']));
    }

    $uri = isset($attributes['destination']) ? $scheme . $attributes['destination'] : $scheme . '';
    $this->fileSystem->prepareDirectory($uri, FileSystemInterface::CREATE_DIRECTORY);
    $this->fileSystem->prepareDirectory($uri, FileSystemInterface::MODIFY_PERMISSIONS);

    // Generate random .txt content file if none given.
    if (!isset($attributes['path']) && !isset($attributes['uri'])) {
      $attributes['uri'] = $uri . $this->randomMachineName(10) . '.txt';
      file_put_contents($attributes['uri'], $this->randomString(20));
    }
    elseif (isset($attributes['path'])) {
      $file_info = pathinfo($attributes['path']);
      $attributes['uri'] = $uri . $file_info['basename'];
      file_put_contents($attributes['uri'], file_get_contents($attributes['path']));
    }

    if (isset($attributes['uri']) && !is_file($attributes['uri'])) {
      throw new \InvalidArgumentException(sprintf('File "%s" does not exist.', $attributes['uri']));
    }

    if (isset($attributes['uri']) && !is_readable($attributes['uri'])) {
      throw new \InvalidArgumentException(sprintf('File "%s" cannot be read.', $attributes['uri']));
    }

    $file_info = pathinfo($attributes['uri']);
    if (!isset($attributes['filename'])) {
      $attributes['filename'] = $file_info['filename'];
    }

    // User override values that will override calculated values.
    $values = $attributes ?? [];

    // Load the storage at the last moment to prevent requiring File module
    // on shouldApply phase.
    $file_storage = $this->entityTypeManager->getStorage('file');

    $file = $file_storage->create($values + [
      'uri' => $attributes['uri'],
      'status' => $attributes['status'] ?? FileInterface::STATUS_PERMANENT,
    ]);
    $file->save();
    return $file;
  }

}
