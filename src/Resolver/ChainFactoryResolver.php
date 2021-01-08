<?php

namespace Drupal\factory_lollipop\Resolver;

use Drupal\factory_lollipop\FactoryInterface;

/**
 * Chain resolver to be used to process available Factories one by one.
 */
class ChainFactoryResolver {

  /**
   * The resolvers.
   *
   * @var \Drupal\factory_lollipop\FactoryInterface[]
   */
  protected $resolvers = [];

  /**
   * Constructs a new ChainFactoryResolver object.
   *
   * @param \Drupal\factory_lollipop\FactoryInterface[] $resolvers
   *   The resolvers.
   */
  public function __construct(array $resolvers = []) {
    $this->resolvers = $resolvers;
  }

  /**
   * Adds a Factory Type resolver.
   *
   * @param \Drupal\factory_lollipop\FactoryInterface $resolver
   *   The resolver.
   */
  public function addResolver(FactoryInterface $resolver): void {
    $this->resolvers[] = $resolver;
  }

  /**
   * Gets all added resolvers.
   *
   * @return \Drupal\factory_lollipop\FactoryInterface[]
   *   The resolvers.
   */
  public function getResolvers(): array {
    return $this->resolvers;
  }

}
