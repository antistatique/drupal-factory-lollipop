<?php

namespace Drupal\factory_lollipop;

/**
 * Provides the base interface for Factory.
 *
 * Factory will be loaded on tests and resolved one by one.
 * The Factory define one or many FactoryType to be use with it's default data.
 *
 * Examples: Article content type having 3 custom fields. Article are attached
 * to a Tag Vocabulary. The Factory define Articles being publish by default.
 */
interface FactoryInterface {

  /**
   * Get the resolver unique name to distinct it.
   *
   * This is the name use to load the definition resolver.
   *
   * @return string
   *   The resolver unique name.
   */
  public function getName(): string;

  /**
   * Resolve the factory by defining stuff.
   *
   * @param \Drupal\factory_lollipop\FixtureFactory $lollipop
   *   Fixtures factory.
   *
   * @internal
   */
  public function resolve(FixtureFactory $lollipop);

}
