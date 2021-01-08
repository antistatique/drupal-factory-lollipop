<?php

namespace Drupal\factory_lollipop\Resolver;

use Drupal\factory_lollipop\FactoryType\FactoryTypeInterface;

/**
 * Chain resolver to be used to process available Factory Type one by one.
 */
class ChainFactoryTypeResolver {

  /**
   * The resolvers.
   *
   * @var \Drupal\factory_lollipop\FactoryType\FactoryTypeInterface[]
   */
  protected $resolvers = [];

  /**
   * Constructs a new ChainFactoryTypeResolver object.
   *
   * @param \Drupal\factory_lollipop\FactoryType\FactoryTypeInterface[] $resolvers
   *   The resolvers.
   */
  public function __construct(array $resolvers = []) {
    $this->resolvers = $resolvers;
  }

  /**
   * Adds a Factory Type resolver.
   *
   * @param \Drupal\factory_lollipop\FactoryType\FactoryTypeInterface $resolver
   *   The resolver.
   */
  public function addResolver(FactoryTypeInterface $resolver): void {
    $this->resolvers[] = $resolver;
  }

  /**
   * Gets all added resolvers.
   *
   * @return \Drupal\factory_lollipop\FactoryType\FactoryTypeInterface[]
   *   The resolvers.
   */
  public function getResolvers(): array {
    return $this->resolvers;
  }

}
